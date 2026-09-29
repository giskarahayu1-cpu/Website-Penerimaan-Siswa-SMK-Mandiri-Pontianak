<?php

namespace Tests\Feature;

use App\Models\CalonSiswa;
use App\Models\Pendaftaran;
use App\Models\Pembayaran;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FonnteNotificationTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        // Set configuration variables for testing
        config(['services.fonnte.token' => 'test-fonnte-token-123']);
        config(['services.fonnte.url' => 'https://api.fonnte.com/send']);
    }

    public function test_whatsapp_notification_sent_when_status_updated_to_diterima(): void
    {
        Http::fake([
            'https://api.fonnte.com/send' => Http::response(['status' => true], 200)
        ]);

        // 1. Create Admin
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        // 2. Create Student and their registration status
        $studentUser = User::factory()->create([
            'role' => 'siswa',
        ]);

        $siswa = CalonSiswa::create([
            'user_id' => $studentUser->id,
            'nisn' => '1234567890',
            'nama' => 'Giska Rahayu', // Saved as GISKA RAHAYU in DB due to strtoupper mutator
            'jenis_kelamin' => 'P',
            'tempat_lahir' => 'Pontianak',
            'tanggal_lahir' => '2008-01-01',
            'agama' => 'Islam',
            'kewarganegaraan' => 'WNI',
            'alamat' => 'Jl. Merdeka No. 10',
            'kelurahan' => 'Mariana',
            'kecamatan' => 'Pontianak Kota',
            'kota' => 'Pontianak',
            'provinsi' => 'Kalimantan Barat',
            'tinggi_badan' => 160,
            'penyakit' => 'Tidak Ada',
            'jumlah_saudara' => 2,
            'anak_ke' => 1,
            'no_hp' => '081234567890',
            'email' => $studentUser->email,
            'jurusan' => 'Akuntansi',
            'nama_ayah' => 'Ayah Giska',
            'nama_ibu' => 'Ibu Giska',
        ]);

        $pendaftaran = Pendaftaran::create([
            'id_siswa' => $siswa->id_siswa,
            'tanggal_daftar' => now(),
            'status' => 'menunggu',
        ]);

        // 3. Admin updates status to 'diterima'
        $response = $this->actingAs($admin)->post(route('admin.students.verify', $siswa->id_siswa), [
            'status' => 'diterima',
            'keterangan' => 'Lulus seleksi berkas dan nilai.',
        ]);

        // Assert redirect back with success message
        $response->assertRedirect(route('admin.students.show', $siswa->id_siswa));
        $response->assertSessionHas('success', 'Status pendaftaran berhasil diperbarui.');

        // Verify status updated in DB
        $this->assertEquals('diterima', $pendaftaran->fresh()->status);

        // Verify Http request sent to Fonnte
        Http::assertSent(function ($request) {
            $expectedMessage = "PPDB SMK Mandiri Pontianak\n\n"
                . "PENGUMUMAN HASIL SELEKSI\n\n"
                . "Halo, Giska Rahayu.\n\n"
                . "Selamat!\n\n"
                . "Berdasarkan hasil seleksi PPDB, Anda dinyatakan DITERIMA sebagai peserta didik baru di SMK Mandiri Pontianak.\n\n"
                . "Silakan login ke sistem PPDB untuk melihat informasi selengkapnya.\n\n"
                . "Terima kasih.";

            return $request->url() === 'https://api.fonnte.com/send'
                && $request->hasHeader('Authorization', 'test-fonnte-token-123')
                && $request['target'] === '6281234567890'
                && $request['message'] === $expectedMessage;
        });
    }

    public function test_whatsapp_notification_not_sent_when_status_updated_to_other_than_diterima(): void
    {
        Http::fake();

        // 1. Create Admin
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        // 2. Create Student and their registration status
        $studentUser = User::factory()->create([
            'role' => 'siswa',
        ]);

        $siswa = CalonSiswa::create([
            'user_id' => $studentUser->id,
            'nisn' => '1234567890',
            'nama' => 'Giska Rahayu',
            'jenis_kelamin' => 'P',
            'tempat_lahir' => 'Pontianak',
            'tanggal_lahir' => '2008-01-01',
            'agama' => 'Islam',
            'kewarganegaraan' => 'WNI',
            'alamat' => 'Jl. Merdeka No. 10',
            'kelurahan' => 'Mariana',
            'kecamatan' => 'Pontianak Kota',
            'kota' => 'Pontianak',
            'provinsi' => 'Kalimantan Barat',
            'tinggi_badan' => 160,
            'penyakit' => 'Tidak Ada',
            'jumlah_saudara' => 2,
            'anak_ke' => 1,
            'no_hp' => '081234567890',
            'email' => $studentUser->email,
            'jurusan' => 'Akuntansi',
            'nama_ayah' => 'Ayah Giska',
            'nama_ibu' => 'Ibu Giska',
        ]);

        $pendaftaran = Pendaftaran::create([
            'id_siswa' => $siswa->id_siswa,
            'tanggal_daftar' => now(),
            'status' => 'menunggu',
        ]);

        // 3. Admin updates status to 'verifikasi' (not 'diterima')
        $response = $this->actingAs($admin)->post(route('admin.students.verify', $siswa->id_siswa), [
            'status' => 'verifikasi',
            'keterangan' => 'Dokumen dalam proses verifikasi.',
        ]);

        $response->assertRedirect(route('admin.students.show', $siswa->id_siswa));
        $this->assertEquals('verifikasi', $pendaftaran->fresh()->status);

        // Verify Http request NOT sent to Fonnte
        Http::assertNothingSent();
    }

    public function test_whatsapp_notification_not_sent_again_if_status_already_diterima(): void
    {
        Http::fake();

        // 1. Create Admin
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        // 2. Create Student and their registration status (already 'diterima')
        $studentUser = User::factory()->create([
            'role' => 'siswa',
        ]);

        $siswa = CalonSiswa::create([
            'user_id' => $studentUser->id,
            'nisn' => '1234567890',
            'nama' => 'Giska Rahayu',
            'jenis_kelamin' => 'P',
            'tempat_lahir' => 'Pontianak',
            'tanggal_lahir' => '2008-01-01',
            'agama' => 'Islam',
            'kewarganegaraan' => 'WNI',
            'alamat' => 'Jl. Merdeka No. 10',
            'kelurahan' => 'Mariana',
            'kecamatan' => 'Pontianak Kota',
            'kota' => 'Pontianak',
            'provinsi' => 'Kalimantan Barat',
            'tinggi_badan' => 160,
            'penyakit' => 'Tidak Ada',
            'jumlah_saudara' => 2,
            'anak_ke' => 1,
            'no_hp' => '081234567890',
            'email' => $studentUser->email,
            'jurusan' => 'Akuntansi',
            'nama_ayah' => 'Ayah Giska',
            'nama_ibu' => 'Ibu Giska',
        ]);

        $pendaftaran = Pendaftaran::create([
            'id_siswa' => $siswa->id_siswa,
            'tanggal_daftar' => now(),
            'status' => 'diterima',
        ]);

        // 3. Admin updates status to 'diterima' again (e.g. to update the note/keterangan)
        $response = $this->actingAs($admin)->post(route('admin.students.verify', $siswa->id_siswa), [
            'status' => 'diterima',
            'keterangan' => 'Catatan diperbarui.',
        ]);

        $response->assertRedirect(route('admin.students.show', $siswa->id_siswa));
        $this->assertEquals('diterima', $pendaftaran->fresh()->status);
        $this->assertEquals('Catatan diperbarui.', $pendaftaran->fresh()->keterangan);

        // Verify Http request NOT sent to Fonnte since the status did not transition to 'diterima' (it was already 'diterima')
        Http::assertNothingSent();
    }

    public function test_whatsapp_notification_not_sent_when_student_uploads_payment(): void
    {
        Storage::fake('public');
        Http::fake();

        $studentUser = User::factory()->create(['role' => 'siswa']);
        $siswa = CalonSiswa::create([
            'user_id' => $studentUser->id,
            'nisn' => '1234567891',
            'nama' => 'Ade Kurniawan',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Pontianak',
            'tanggal_lahir' => '2008-01-01',
            'agama' => 'Islam',
            'kewarganegaraan' => 'WNI',
            'alamat' => 'Jl. Khatulistiwa',
            'kelurahan' => 'Siantan',
            'kecamatan' => 'Pontianak Utara',
            'kota' => 'Pontianak',
            'provinsi' => 'Kalimantan Barat',
            'tinggi_badan' => 170,
            'penyakit' => 'Tidak Ada',
            'jumlah_saudara' => 1,
            'anak_ke' => 1,
            'no_hp' => '089876543210',
            'email' => $studentUser->email,
            'jurusan' => 'Pemasaran',
            'nama_ayah' => 'Ayah Ade',
            'nama_ibu' => 'Ibu Ade',
        ]);

        $pendaftaran = Pendaftaran::create([
            'id_siswa' => $siswa->id_siswa,
            'tanggal_daftar' => now(),
            'status' => 'menunggu',
        ]);

        foreach (['ijazah', 'kk', 'akta', 'ktp orang tua'] as $doc) {
            \App\Models\Berkas::create([
                'id_daftar' => $pendaftaran->id_daftar,
                'nama_berkas' => $doc,
                'file' => 'uploads/berkas/dummy.pdf',
            ]);
        }

        $file = UploadedFile::fake()->image('bukti.jpg');

        $response = $this->actingAs($studentUser)->post(route('student.upload-pembayaran'), [
            'angsuran_ke' => 1,
            'metode_pembayaran' => 'transfer',
            'jumlah' => 1500000,
            'bukti_bayar' => $file,
        ]);

        $response->assertRedirect(route('student.dashboard'));
        $this->assertDatabaseHas('pembayaran', [
            'id_daftar' => $pendaftaran->id_daftar,
            'angsuran_ke' => 1,
            'status' => 'menunggu',
        ]);

        // Verify Http request NOT sent to Fonnte on upload
        Http::assertNothingSent();
    }

    public function test_whatsapp_notification_sent_when_admin_verifies_payment(): void
    {
        Http::fake([
            'https://api.fonnte.com/send' => Http::response(['status' => true], 200)
        ]);

        $admin = User::factory()->create(['role' => 'admin']);
        $studentUser = User::factory()->create(['role' => 'siswa']);
        $siswa = CalonSiswa::create([
            'user_id' => $studentUser->id,
            'nisn' => '1234567892',
            'nama' => 'Budi Santoso',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Pontianak',
            'tanggal_lahir' => '2008-01-01',
            'agama' => 'Islam',
            'kewarganegaraan' => 'WNI',
            'alamat' => 'Jl. Veteran',
            'kelurahan' => 'Benua Melayu Laut',
            'kecamatan' => 'Pontianak Selatan',
            'kota' => 'Pontianak',
            'provinsi' => 'Kalimantan Barat',
            'tinggi_badan' => 165,
            'penyakit' => 'Tidak Ada',
            'jumlah_saudara' => 2,
            'anak_ke' => 1,
            'no_hp' => '085211223344',
            'email' => $studentUser->email,
            'jurusan' => 'Akuntansi',
            'nama_ayah' => 'Ayah Budi',
            'nama_ibu' => 'Ibu Budi',
        ]);

        $pendaftaran = Pendaftaran::create([
            'id_siswa' => $siswa->id_siswa,
            'tanggal_daftar' => now(),
            'status' => 'menunggu',
        ]);

        $pembayaran = Pembayaran::create([
            'id_daftar' => $pendaftaran->id_daftar,
            'angsuran_ke' => 1,
            'metode_pembayaran' => 'transfer',
            'tanggal_bayar' => now(),
            'jumlah' => 1500000,
            'bukti_bayar' => 'uploads/pembayaran/dummy.jpg',
            'status' => 'menunggu',
        ]);

        $response = $this->actingAs($admin)->post(route('admin.payments.verify', $pembayaran->id_pembayaran), [
            'status' => 'valid',
        ]);

        $response->assertRedirect(route('admin.payments.index'));
        $this->assertEquals('valid', $pembayaran->fresh()->status);

        // Verify Http request sent to student via Fonnte
        Http::assertSent(function ($request) {
            return $request['target'] === '6285211223344'
                && str_contains($request['message'], 'VERIFIKASI PEMBAYARAN: VALID')
                && str_contains($request['message'], 'Rp 1.500.000');
        });
    }
}
