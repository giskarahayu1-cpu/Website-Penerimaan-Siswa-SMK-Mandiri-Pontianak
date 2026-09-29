<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format alamat email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();
            if ($user->role === 'admin') {
                return redirect()->intended(route('admin.dashboard'))->with('success', 'Selamat datang Admin!');
            } elseif ($user->role === 'kepsek') {
                return redirect()->intended(route('kepsek.dashboard'))->with('success', 'Selamat datang Kepala Sekolah!');
            }

            return redirect()->intended(route('student.dashboard'))->with('success', 'Login berhasil!');
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        if (Setting::get('pendaftaran_status', 'dibuka') === 'ditutup') {
            return redirect()->route('login')->with('error', 'Periode pendaftaran online telah ditutup.');
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        if (Setting::get('pendaftaran_status', 'dibuka') === 'ditutup') {
            return redirect()->route('login')->with('error', 'Periode pendaftaran online telah ditutup.');
        }
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'nisn' => ['required', 'string', 'size:10', 'unique:users,nisn', 'unique:calon_siswa,nisn'],
            'no_hp' => ['required', 'string', 'max:15'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format alamat email tidak valid.',
            'email.unique' => 'Alamat email sudah terdaftar.',
            'nisn.required' => 'NISN wajib diisi.',
            'nisn.size' => 'NISN harus berukuran 10 karakter.',
            'nisn.unique' => 'NISN sudah terdaftar.',
            'no_hp.required' => 'Nomor handphone wajib diisi.',
            'password.required' => 'password wajib diisi.',
            'password.min' => 'Password harus minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'nisn' => $request->nisn,
            'no_hp' => $request->no_hp,
            'password' => Hash::make($request->password),
            'role' => 'siswa',
        ]);

        Auth::login($user);

        return redirect()->route('student.dashboard')->with('success', 'Registrasi berhasil! Silakan lengkapi pendaftaran.');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Anda telah berhasil logout.');
    }
}
