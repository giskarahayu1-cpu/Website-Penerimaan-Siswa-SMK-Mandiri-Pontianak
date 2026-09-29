<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\KepalaSekolahController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/visi-misi', [HomeController::class, 'visiMisi'])->name('visi-misi');
Route::get('/jurusan', [HomeController::class, 'jurusan'])->name('jurusan');
Route::get('/fasilitas', [HomeController::class, 'fasilitas'])->name('fasilitas');

// Guest Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Student Area
    Route::prefix('student')->name('student.')->group(function () {
        Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('dashboard');
        Route::get('/register', [StudentController::class, 'showRegisterForm'])->name('register');
        Route::post('/register', [StudentController::class, 'storeRegisterForm'])->name('register.store');
        Route::get('/edit', [StudentController::class, 'edit'])->name('edit');
        Route::post('/update', [StudentController::class, 'update'])->name('update');
        Route::post('/upload-berkas', [StudentController::class, 'uploadBerkas'])->name('upload-berkas');
        Route::post('/upload-pembayaran', [StudentController::class, 'uploadPembayaran'])->name('upload-pembayaran');
        Route::get('/print', [StudentController::class, 'printProof'])->name('print');
        Route::post('/notifications/read', [StudentController::class, 'markNotificationsAsRead'])->name('notifications.read');
    });

    // Admin Area
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/students', [AdminController::class, 'students'])->name('students.index');
        Route::get('/students/create', [AdminController::class, 'createStudent'])->name('students.create');
        Route::post('/students/store', [AdminController::class, 'storeStudent'])->name('students.store');
        Route::get('/students/{id_siswa}', [AdminController::class, 'showStudent'])->name('students.show');
        Route::get('/students/{id_siswa}/edit', [AdminController::class, 'editStudent'])->name('students.edit');
        Route::post('/students/{id_siswa}/update', [AdminController::class, 'updateStudent'])->name('students.update');
        Route::post('/students/{id_siswa}/delete', [AdminController::class, 'deleteStudent'])->name('students.delete');
        Route::post('/users/{id}/delete', [AdminController::class, 'deleteUser'])->name('users.delete');
        Route::post('/students/{id_siswa}/verify', [AdminController::class, 'verifyStudent'])->name('students.verify');
        Route::post('/students/{id_siswa}/payments/save', [AdminController::class, 'savePaymentSlot'])->name('students.payments.save');
        
        Route::get('/payments', [AdminController::class, 'payments'])->name('payments.index');
        Route::get('/payments/{id_pembayaran}', [AdminController::class, 'showPayment'])->name('payments.show');
        Route::post('/payments/{id_pembayaran}/verify', [AdminController::class, 'verifyPayment'])->name('payments.verify');
        Route::get('/export', [AdminController::class, 'exportData'])->name('export');

        // Rute untuk penetapan kelas & kontrol status periode pendaftaran
        Route::post('/classes/toggle-period', [AdminController::class, 'togglePeriod'])->name('classes.toggle-period');
        Route::post('/settings/update-bank', [AdminController::class, 'updateBankSettings'])->name('settings.update-bank');
    });

    // Area Kepala Sekolah
    Route::prefix('kepsek')->name('kepsek.')->group(function () {
        Route::get('/dashboard', [KepalaSekolahController::class, 'dashboard'])->name('dashboard');
        Route::get('/students', [KepalaSekolahController::class, 'students'])->name('students.index');
        Route::get('/students/{id_siswa}', [KepalaSekolahController::class, 'showStudent'])->name('students.show');
        Route::get('/payments', [KepalaSekolahController::class, 'payments'])->name('payments.index');
        Route::get('/export', [KepalaSekolahController::class, 'exportData'])->name('export');
    });
});
