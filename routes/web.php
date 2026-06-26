<?php

// Kode ini diletakkan di routes/web.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MangaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\AdminController;
// Pastikan controller baru ini di-import di bagian atas
use App\Http\Controllers\AdminChapterController;
use App\Http\Controllers\SearchController;

// 1. BISA DIAKSES SEMUA ORANG (Guest & Auth)
Route::get('/', [MangaController::class, 'index'])->name('home');
// Halaman Detail Komik (Sekarang mencari berdasarkan slug)
Route::get('/komik/{slug}', [MangaController::class, 'show'])->name('manga.show');
// Halaman Baca Chapter (Mencari berdasarkan slug komik dan nomor chapter)
Route::get('/komik/{slug}/chapter/{chapter_number}', [MangaController::class, 'read'])->name('chapter.read');
// Rute untuk Pencarian Filter Biasa (Standard)
Route::get('/search', [\App\Http\Controllers\SearchController::class, 'index'])->name('search');
// Rute untuk Pencarian Cerdas AI (Skripsi)
Route::get('/search/ai', [\App\Http\Controllers\SearchController::class, 'aiSearch'])->name('search.ai');

// 2. HANYA UNTUK YANG BELUM LOGIN (Guest)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// 3. HANYA UNTUK YANG SUDAH LOGIN (Auth)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/profile/update-info', [ProfileController::class, 'updateInfo'])->name('profile.update.info');
    Route::post('/profile/update-password', [ProfileController::class, 'updatePassword'])->name('profile.update.password');
    Route::post('/profile/upload-avatar', [ProfileController::class, 'uploadAvatar'])->name('profile.upload.avatar');
    Route::post('/comment/{chapter_id}', [CommentController::class, 'store'])->name('comment.store');
    // --- RUTE LIBRARY & BOOKMARK ---
    Route::get('/library', [\App\Http\Controllers\LibraryController::class, 'index'])->name('library');
    Route::post('/library/bookmark/{manga_id}', [\App\Http\Controllers\LibraryController::class, 'toggleBookmark'])->name('bookmark.toggle');
    
    // --- RUTE BALASAN KOMENTAR ---
    Route::get('/komentar/{id}', [\App\Http\Controllers\CommentController::class, 'showThread'])->name('comment.thread');
    Route::post('/komentar/{id}/reply', [\App\Http\Controllers\CommentController::class, 'reply'])->name('comment.reply');

    // --- RUTE KOMENTAR KOMIK & PROFIL PUBLIK ---
Route::post('/komik/{id}/comment', [\App\Http\Controllers\CommentController::class, 'storeMangaComment'])->name('manga.comment.store');
Route::get('/user/{id}', [\App\Http\Controllers\ProfileController::class, 'showPublic'])->name('profile.public');

    // === GRUP KEAMANAN ADMIN ===
Route::middleware(['auth', 'admin'])->group(function () {
    
    // --- DASHBOARD ADMIN UTAMA ---
    Route::get('/admin/dashboard', [\App\Http\Controllers\AdminDashboardController::class, 'index'])->name('admin.dashboard');
    
    // --- KELOLA KOMIK ---
    Route::get('/admin/manga', [\App\Http\Controllers\AdminController::class, 'index'])->name('admin.manga.index');
    Route::get('/admin/manga/create', [\App\Http\Controllers\AdminController::class, 'create'])->name('admin.manga.create');
    Route::post('/admin/manga', [\App\Http\Controllers\AdminController::class, 'store'])->name('admin.manga.store');
    Route::get('/admin/manga/{id}/edit', [\App\Http\Controllers\AdminController::class, 'edit'])->name('admin.manga.edit');
    Route::put('/admin/manga/{id}', [\App\Http\Controllers\AdminController::class, 'update'])->name('admin.manga.update');
    Route::delete('/admin/manga/{id}', [\App\Http\Controllers\AdminController::class, 'destroy'])->name('admin.manga.destroy');

    // --- RUTE TAMBAH CHAPTER DARI HALAMAN KOMIK ---
    Route::get('/admin/manga/{manga_id}/chapter/create', [\App\Http\Controllers\AdminChapterController::class, 'create'])->name('admin.chapter.create');
    Route::post('/admin/manga/{manga_id}/chapter', [\App\Http\Controllers\AdminChapterController::class, 'store'])->name('admin.chapter.store');

    // --- KELOLA CHAPTER & HALAMAN SECARA UMUM ---
    Route::get('/admin/chapter', [\App\Http\Controllers\AdminChapterController::class, 'index'])->name('admin.chapter.index');
    Route::get('/admin/chapter/{id}/edit', [\App\Http\Controllers\AdminChapterController::class, 'edit'])->name('admin.chapter.edit');
    Route::put('/admin/chapter/{id}', [\App\Http\Controllers\AdminChapterController::class, 'update'])->name('admin.chapter.update');
    Route::delete('/admin/chapter/{id}', [\App\Http\Controllers\AdminChapterController::class, 'destroy'])->name('admin.chapter.destroy');
    Route::delete('/admin/page/{id}', [\App\Http\Controllers\AdminChapterController::class, 'destroyPage'])->name('admin.page.destroy');

    // --- KELOLA KOMENTAR ---
    Route::get('/admin/comment', [\App\Http\Controllers\AdminCommentController::class, 'index'])->name('admin.comment.index');
    Route::delete('/admin/comment/{id}', [\App\Http\Controllers\AdminCommentController::class, 'destroy'])->name('admin.comment.destroy');

    // --- KELOLA IKLAN ---
    Route::get('/admin/ad', [\App\Http\Controllers\AdminAdController::class, 'index'])->name('admin.ad.index');
    Route::get('/admin/ad/create', [\App\Http\Controllers\AdminAdController::class, 'create'])->name('admin.ad.create');
    Route::post('/admin/ad', [\App\Http\Controllers\AdminAdController::class, 'store'])->name('admin.ad.store');
    Route::get('/admin/ad/{id}/edit', [\App\Http\Controllers\AdminAdController::class, 'edit'])->name('admin.ad.edit');
    Route::put('/admin/ad/{id}', [\App\Http\Controllers\AdminAdController::class, 'update'])->name('admin.ad.update');
    Route::delete('/admin/ad/{id}', [\App\Http\Controllers\AdminAdController::class, 'destroy'])->name('admin.ad.destroy');

    // --- PENGATURAN WEB ---
    Route::get('/admin/setting', [\App\Http\Controllers\AdminSettingController::class, 'index'])->name('admin.setting.index');
    Route::put('/admin/setting/update', [\App\Http\Controllers\AdminSettingController::class, 'update'])->name('admin.setting.update');

    // --- KELOLA PENGGUNA ---
    Route::get('/admin/user', [\App\Http\Controllers\AdminUserController::class, 'index'])->name('admin.user.index');
    Route::put('/admin/user/{id}/role', [\App\Http\Controllers\AdminUserController::class, 'updateRole'])->name('admin.user.updateRole');
    Route::delete('/admin/user/{id}', [\App\Http\Controllers\AdminUserController::class, 'destroy'])->name('admin.user.destroy');

});
});