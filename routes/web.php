<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LandingController;
use Illuminate\Support\Facades\Route;

// ─────────────────────────────────────────────────────────────────────────────
// AUTH
// ─────────────────────────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login',     [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',    [AuthController::class, 'login']);
    Route::get('/register',  [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// ─────────────────────────────────────────────────────────────────────────────
// USER (harus login)
// ─────────────────────────────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {

    // Buku
    Route::get('/buku',                    [BukuController::class, 'index'])->name('buku.index');
    Route::get('/buku/cari',               [BukuController::class, 'search'])->name('buku.search');
    Route::get('/buku/{googleBooksId}',    [BukuController::class, 'detail'])->name('buku.detail');
    Route::post('/buku',                   [BukuController::class, 'store'])->name('buku.store');
    Route::put('/buku/{googleBooksId}',    [BukuController::class, 'update'])->name('buku.update');
    Route::post('/buku/{googleBooksId}/favorite', [BukuController::class, 'toggleFavorite'])->name('buku.favorite');
    Route::delete('/buku/{googleBooksId}',         [BukuController::class, 'destroy'])->name('buku.destroy');

    // Profil sendiri
    Route::get('/profile',                [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile',                [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/avatar',         [ProfileController::class, 'updateAvatar'])->name('profile.avatar');
    Route::delete('/profile/avatar',      [ProfileController::class, 'deleteAvatar'])->name('profile.avatar.delete');
    Route::put('/profile/password',       [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::post('/profile/verify-password',[ProfileController::class, 'verifyPassword'])->name('profile.verify-password');
    Route::delete('/profile',             [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Pencarian & profil publik user lain
    Route::get('/users',          [ProfileController::class, 'search'])->name('user.search');
    Route::get('/user/{user}',    [ProfileController::class, 'showPublic'])->name('profile.public');
});

// ─────────────────────────────────────────────────────────────────────────────
// ADMIN
// ─────────────────────────────────────────────────────────────────────────────
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard',           [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users',               [AdminController::class, 'users'])->name('users');
    Route::delete('/users/{user}',     [AdminController::class, 'destroyUser'])->name('users.destroy');
    Route::patch('/users/{user}/role', [AdminController::class, 'updateRole'])->name('users.role');
    Route::get('/books',               [AdminController::class, 'books'])->name('books');
});

Route::get('/', [LandingController::class, 'index'])->name('home');