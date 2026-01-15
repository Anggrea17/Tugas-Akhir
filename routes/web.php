<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BayiController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


//route login google
use App\Http\Controllers\Auth\GoogleController;

Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('google.login');
Route::get('/auth/google/callback', [GoogleController::class, 'callback'])->name('google.callback');

//route login user
use App\Http\Controllers\Auth\LoginController;
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout'); 
//route untuk landingpg user
use App\Http\Controllers\LandingpgController;
Route::get('/landingpg', [LandingpgController::class, 'index'])->name('landingpg');
Route::get('/', function () {
    return redirect()->route('landingpg');
});

//route register user
use App\Http\Controllers\RegisterController;
Route::get('/register', [RegisterController::class, 'show'])->name('register');
Route::post('/register', [RegisterController::class, 'store']);

use App\Http\Controllers\ProfileController;
// halaman edit profile
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');

    // Update user
    Route::put('/profile/update-user', [ProfileController::class, 'updateUser'])->name('profile.updateUser');

    // Update bayi
    Route::put('/profile/update-bayi', [ProfileController::class, 'updateBayi'])->name('profile.updateBayi');
    Route::delete('/profile/bayi/{id}', [ProfileController::class, 'destroyBayi'])->name('destroyBayi');
});

//route bayi user
Route::get('/bayi/data', [BayiController::class, 'getMyBabies'])->middleware('auth');
Route::get('/my-babies/data', [BayiController::class, 'getUserBabies'])->middleware('auth');
Route::get('/my-babies/data', [BayiController::class, 'getMyBabies'])->name('my-babies.data');

// Route form Rekomendasi MPASI user
use App\Http\Controllers\RekomendasiController;
Route::middleware(['auth'])->group(function () {
Route::get('/rekomendasi', [RekomendasiController::class, 'hasil'])->middleware(['auth', 'no_admin_access'])->name('rekomendasi');
});

// Route Artikel MPASI user
use App\Http\Controllers\ArtikelController;
Route::get('/artikel', [ArtikelController::class, 'artikel'])->name('artikel');
Route::get('/artikel/{slug}', [ArtikelController::class, 'show'])->name('artikel.show');
// route perkembangan bayi user
use App\Http\Controllers\PerkembanganBayiController;
Route::middleware(['auth'])->group(function () {
    Route::get('/perkembangan', [PerkembanganBayiController::class, 'index'])->middleware(['auth', 'no_admin_access'])->name('perkembangan.index');
    Route::delete('/perkembangan/{id}', [PerkembanganBayiController::class, 'destroy'])->name('perkembangan.destroy');
});

//route lupa password user
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
Route::middleware('auth')->group(function () {
Route::put('/profile/password', [ProfileController::class, 'updatePassword'])
    ->name('profile.password.update');
});
Route::middleware('guest')->group(function () {
    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
     Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.update');
});

// Route Admin
use App\Http\Controllers\Auth\AdminController;
use App\Http\Controllers\KategoriArtikelController;
use App\Http\Controllers\KategoriMpasiController;

Route::post('/bayi/update-or-create', [BayiController::class, 'updateOrCreate'])->name('bayi.updateOrCreate');
Route::post('/bayi/update/{id}', [BayiController::class, 'updateFromRekomendasi'])->name('bayi.updateFromRekomendasi');
Route::middleware(['auth', 'is_admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('/admin/logout', [LoginController::class, 'logoutAdmin'])->name('admin.logout');
    Route::post('/admin/users', [AdminController::class, 'storeUser'])->name('admin.users.store');

    // kelola User
    Route::get('/users', [AdminController::class, 'kelolaUser'])->name('admin.users');
    Route::delete('/users/{id}', [AdminController::class, 'hapusUser'])->name('admin.users.delete');
    Route::get('/users/{id}/edit', [AdminController::class, 'editUser'])->name('admin.users.edit');
    Route::put('/users/{id}', [AdminController::class, 'updateUser'])->name('admin.users.update');
    Route::post('/users', [AdminController::class, 'storeUser'])->name('admin.users.store');  
    Route::put('/users/{id}/update-password', [AdminController::class, 'updatePassword'])->name('admin.users.updatePassword');
    Route::get('/admin/users/{id}', [AdminController::class, 'showmusers'])->name('admin.users.show');

    // kelola Resep MPASI
    Route::get('/admin/mpasi', [AdminController::class, 'kelolaMpasi'])->name('admin.mpasi');
    Route::get('/admin/mpasi/tambah', [AdminController::class, 'tambahMpasi'])->name('admin.mpasi.create');
    Route::post('/admin/mpasi', [AdminController::class, 'simpanMpasi'])->name('admin.mpasi.store');
    Route::get('/admin/mpasi/{id}/edit', [AdminController::class, 'editMpasi'])->name('admin.mpasi.edit');
    Route::put('/admin/mpasi/{id}', [AdminController::class, 'updateMpasi'])->name('admin.mpasi.update');
    Route::delete('/admin/mpasi/{id}', [AdminController::class, 'hapusMpasi'])->name('admin.mpasi.delete');
    Route::get('/admin/mpasi/{id}', [AdminController::class, 'showmpasi'])->name('admin.mpasi.show');
    // kelola Artikel
    Route::get('/admin/kelolaartikel', [AdminController::class, 'kelolaArtikel'])->name('admin.kelolaartikel');
    Route::get('/admin/kelolaartikel/tambah', [AdminController::class, 'tambahArtikel'])->name('admin.kelolaartikel.create');
    Route::post('/admin/kelolaartikel', [AdminController::class, 'simpanArtikel'])->name('admin.kelolaartikel.store');
    Route::get('/admin/kelolaartikel/{id}/edit', [AdminController::class, 'editArtikel'])->name('admin.kelolaartikel.edit');
    Route::put('/admin/kelolaartikel/{id}', [AdminController::class, 'updateArtikel'])->name('admin.kelolaartikel.update');
    Route::delete('/admin/kelolaartikel/{id}', [AdminController::class, 'hapusArtikel'])->name('admin.kelolaartikel.delete');
    Route::get('/admin/kelolaartikel/{id}', [adminController::class, 'show'])->name('admin.kelolaartikel.show');
    Route::post('/admin/kelolaartikel/upload', [adminController::class, 'upload'])->name('admin.upload');

    // Kategori Artikel
    Route::get('/kategori', [KategoriArtikelController::class, 'kelolakategoriartikel'])->name('admin.kategoriartikel');
    Route::get('/kategori/create', [KategoriArtikelController::class, 'create'])->name('admin.kategori.create');
    Route::post('/kategori', [KategoriArtikelController::class, 'store'])->name('admin.kategori.store');
    Route::get('/kategori/{kategori}/edit', [KategoriArtikelController::class, 'edit'])->name('admin.kategori.edit');
    Route::put('/kategori/{kategori}', [KategoriArtikelController::class, 'update'])->name('admin.kategori.update');
    Route::delete('/kategori/{kategori}', [KategoriArtikelController::class, 'destroy'])->name('admin.kategori.destroy');

    // Kategori Mpasi
    Route::get('/kategori-mpasi', [KategoriMpasiController::class, 'kelolakategorimpasi'])->name('admin.kategorimpasi');
    Route::get('/kategori-mpasi/create', [KategoriMpasiController::class, 'create'])->name('admin.kategorimpasi.create');
    Route::post('/kategori-mpasi', [KategoriMpasiController::class, 'store'])->name('admin.kategorimpasi.store');
    Route::put('/kategori-mpasi/{kategori}', [KategoriMpasiController::class, 'update'])->name('admin.kategorimpasi.update');
    Route::delete('/kategori-mpasi/{kategori}', [KategoriMpasiController::class, 'destroy'])->name('admin.kategorimpasi.destroy');

    //kelola bayi per user
    Route::get('/kelola-bayi/{user}', [BayiController::class, 'index'])->name('admin.kelolabayi');
    Route::post('/kelola-bayi/{user}/store', [BayiController::class, 'store'])->name('admin.bayi.store');
    Route::get('/kelola-bayi/{user}/edit/{bayi}', [BayiController::class, 'edit'])->name('admin.bayi.edit');
    Route::put('/kelola-bayi/{user}/update/{bayi}', [BayiController::class, 'update'])->name('admin.bayi.update');
    Route::delete('/kelola-bayi/{user}/delete/{bayi}', [BayiController::class, 'destroy'])->name('admin.bayi.destroy');
    Route::get('/kelola-bayi/{user}/data', [BayiController::class, 'getData'])->name('admin.bayi.data');
    //lihat perkembangan bayi per user
    Route::get('/kelola-bayi/{user}/perkembangan/{bayi}', [BayiController::class, 'show'])->name('admin.bayi.perkembangan');
});