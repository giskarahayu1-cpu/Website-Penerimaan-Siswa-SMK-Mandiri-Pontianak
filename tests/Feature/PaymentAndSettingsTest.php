<?php

namespace Tests\Feature;

use App\Models\CalonSiswa;
use App\Models\Pendaftaran;
use App\Models\Pembayaran;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PaymentAndSettingsTest extends TestCase
{
    use DatabaseTransactions;

    public function test_admin_can_update_bank_settings(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)->post(route('admin.settings.update-bank'), [
            'bank_name' => 'Bank Kalbar Syariah',
            'bank_account_number' => '123-45678-900',
            'bank_account_name' => 'SMK MANDIRI TEST',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Informasi rekening transfer bank berhasil diperbarui.');

        $this->assertEquals('Bank Kalbar Syariah', Setting::get('bank_name'));
        $this->assertEquals('123-45678-900', Setting::get('bank_account_number'));
        $this->assertEquals('SMK MANDIRI TEST', Setting::get('bank_account_name'));
    }

    public function test_student_can_pay_off_early_on_pembayaran_2(): void
    {
        Storage::fake('public');

        $user = User::factory()->create([
            'role' => 'siswa',
        ]);

        $siswa = CalonSiswa::create([
            'user_id' => $user->id,
            'nisn' => '1234567890',
            'nama' => 'Test Student Payoff',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Pontianak',
            'tanggal_lahir' => '2008-01-01',
            'agama' => 'Islam',
            'kewarganegaraan' => 'WNI',
            'alamat' => 'Jl. Test',
            'kelurahan' => 'Test',
            'kecamatan' => 'Test',
            'kota' => 'Pontianak',
            'provinsi' => 'Kalbar',
            'tinggi_badan' => 170,
            'penyakit' => 'Tidak Ada',
            'jumlah_saudara' => 2,
            'anak_ke' => 1,
            'no_hp' => '081234567890',
            'email' => $user->email,
            'jurusan' => 'Animasi',
            'nama_ayah' => 'Ayah',
            'nama_ibu' => 'Ibu',
        ]);

        $pendaftaran = Pendaftaran::create([
            'id_siswa' => $siswa->id_siswa,
            'tanggal_daftar' => now(),
            'status' => 'menunggu',
        ]);

        // Setup mandatory files so the student is allowed to pay
        $docs = ['ijazah', 'kk', 'akta', 'ktp orang tua'];
        foreach ($docs as $doc) {
            $pendaftaran->berkas()->create([
                'nama_berkas' => $doc,
                'file' => 'uploads/berkas/test.png'
            ]);
        }

        // 1. Pay first installment (Rp 1.500.000)
        Pembayaran::create([
            'id_daftar' => $pendaftaran->id_daftar,
            'angsuran_ke' => 1,
            'metode_pembayaran' => 'transfer',
            'tanggal_bayar' => now(),
            'jumlah' => 1500000,
            'bukti_bayar' => 'uploads/pembayaran/bukti1.png',
            'status' => 'valid',
        ]);

        // Remaining sisa bayar should be 3.100.000 - 1.500.000 = 1.600.000.
        // Let's test that uploading Pembayaran 2 with Rp 600.000 (above standard limit of 500k, but not sisaBayar) fails validation
        $file = UploadedFile::fake()->image('bukti2.jpg');
        $responseFail = $this->actingAs($user)->post(route('student.upload-pembayaran'), [
            'jumlah' => 600000,
            'bukti_bayar' => $file,
            'angsuran_ke' => 2,
            'metode_pembayaran' => 'transfer',
        ]);

        $responseFail->assertRedirect(route('student.dashboard', ['tab' => 'administrasi']));
        $responseFail->assertSessionHas('error');

        // Let's test that uploading Pembayaran 2 with exactly Rp 1.600.000 (sisa bayar) passes validation
        $responseSuccess = $this->actingAs($user)->post(route('student.upload-pembayaran'), [
            'jumlah' => 1600000,
            'bukti_bayar' => $file,
            'angsuran_ke' => 2,
            'metode_pembayaran' => 'transfer',
        ]);

        $responseSuccess->assertRedirect(route('student.dashboard'));
        $responseSuccess->assertSessionHas('success');

        // Assert payment is created in database with status 'menunggu'
        $this->assertDatabaseHas('pembayaran', [
            'id_daftar' => $pendaftaran->id_daftar,
            'angsuran_ke' => 2,
            'jumlah' => 1600000,
            'status' => 'menunggu',
        ]);
    }
}
