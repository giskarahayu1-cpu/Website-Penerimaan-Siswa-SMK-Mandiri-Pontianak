<?php

namespace Tests\Feature;

use App\Models\CalonSiswa;
use App\Models\Pendaftaran;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class StudentEditTest extends TestCase
{
    use DatabaseTransactions;

    public function test_student_cannot_edit_data_diri_if_status_is_diterima(): void
    {
        // 1. Create a user with role 'siswa'
        $user = User::factory()->create([
            'role' => 'siswa',
        ]);

        // 2. Create the associated CalonSiswa
        $siswa = CalonSiswa::create([
            'user_id' => $user->id,
            'nisn' => '1234567890',
            'nama' => 'Test Student',
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

        // 3. Create Pendaftaran with status 'diterima'
        $pendaftaran = Pendaftaran::create([
            'id_siswa' => $siswa->id_siswa,
            'tanggal_daftar' => now(),
            'status' => 'diterima',
        ]);

        // 4. Act as the user and try to access the edit route
        $response = $this->actingAs($user)->get(route('student.edit'));

        // 5. Assert redirection with error
        $response->assertRedirect(route('student.dashboard'));
        $response->assertSessionHas('error', 'Status pendaftaran Anda sudah diterima. Anda tidak dapat mengedit data diri lagi.');

        // 6. Try to update
        $updateResponse = $this->actingAs($user)->post(route('student.update'), [
            'nama' => 'Test Student Updated',
            'nisn' => '1234567890',
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
            'jurusan' => 'Animasi',
            'nama_ayah' => 'Ayah',
            'nama_ibu' => 'Ibu',
        ]);

        // 7. Assert update is rejected and redirected
        $updateResponse->assertRedirect(route('student.dashboard'));
        $updateResponse->assertSessionHas('error', 'Status pendaftaran Anda sudah diterima. Anda tidak dapat mengedit data diri lagi.');
        
        // Assert db is not updated
        $this->assertEquals('TEST STUDENT', $siswa->fresh()->nama);
    }

    public function test_student_can_edit_data_diri_if_status_is_menunggu(): void
    {
        $user = User::factory()->create([
            'role' => 'siswa',
        ]);

        $siswa = CalonSiswa::create([
            'user_id' => $user->id,
            'nisn' => '1234567890',
            'nama' => 'Test Student',
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

        $response = $this->actingAs($user)->get(route('student.edit'));
        $response->assertStatus(200);

        $updateResponse = $this->actingAs($user)->post(route('student.update'), [
            'nama' => 'Test Student Updated',
            'nisn' => '1234567890',
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
            'jurusan' => 'Animasi',
            'nama_ayah' => 'Ayah',
            'nama_ibu' => 'Ibu',
        ]);

        $updateResponse->assertRedirect(route('student.dashboard'));
        $updateResponse->assertSessionHas('success', 'Data diri Anda berhasil diperbarui.');
        $this->assertEquals('TEST STUDENT UPDATED', $siswa->fresh()->nama);
    }
}
