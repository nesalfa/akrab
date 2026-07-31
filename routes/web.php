<?php

/**
 * routes/web.php
 *
 * Definisi semua routes (URL paths) untuk aplikasi AKRAB
 *
 * UPDATE dari versi sebelumnya:
 * - /modul/{slug} sekarang WAJIB login (middleware 'auth') — tamu yang
 *   klik card modul otomatis dilempar ke /login, dan otomatis balik ke
 *   modul yang tadi dituju setelah berhasil login/daftar (bawaan Laravel,
 *   lewat redirect()->intended() yang sudah dipasang di
 *   AuthenticatedSessionController & RegisteredUserController).
 * - /login & /register sekarang pakai controller sungguhan (validasi,
 *   hash password, pembeda role user/admin), bukan lagi Route::view()
 *   placeholder.
 * - /api/quiz/submit & /api/modules/{slug}/complete ikut masuk grup
 *   'auth' karena sekarang butuh user yang login (data progress & quiz
 *   attempt diikat ke akun, bukan session ID lagi).
 * - Ada grup baru khusus admin (prefix /admin, middleware 'admin').
 *
 * Struktur routes:
 * - GET  /                          → Halaman beranda (list modul) — publik
 * - GET  /belajar                    → Halaman Belajar — publik
 * - GET  /glosarium, /bantuan, /tentang → placeholder — publik
 * - GET  /register, POST /register   → Registrasi (selalu bikin akun role user) — tamu
 * - GET  /login, POST /login         → Login tunggal untuk user & admin — tamu
 * - POST /logout                     → Logout — wajib login
 * - GET  /modul/{slug}               → Detail modul — WAJIB LOGIN
 * - POST /api/quiz/submit            → Submit jawaban kuis — WAJIB LOGIN
 * - POST /api/modules/{slug}/complete→ Tandai modul selesai — WAJIB LOGIN
 * - GET  /api/glossary/search        → Search glosarium — publik
 * - POST /api/questions/submit       → Submit pertanyaan Tanya Ahli — publik
 * - GET  /admin                      → Dashboard admin (kerangka) — WAJIB LOGIN + role admin
 */

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ModuleController;
use Illuminate\Support\Facades\Route;

// ================= PUBLIK (tidak perlu login) =================

// Halaman pertama / Root URL (Menampilkan Beranda Utama)
Route::get('/', [ModuleController::class, 'home'])->name('home');

// Halaman menu klaster pembelajaran / daftar modul (Menampilkan belajar.blade.php)
Route::get('/belajar', [ModuleController::class, 'index'])->name('belajar');
// Route::get('/bantuan', [ModuleController::class, 'index'])->name('bantuan');

// Placeholder — belum ada view aslinya, masih menampilkan home.blade.php
Route::get('/glosarium', fn() => view('home'))->name('glosarium');
// Route::get('/bantuan', fn() => view('home'))->name('bantuan');
Route::get('/tentang', fn() => view('home'))->name('tentang');

// API publik (tidak butuh identitas user untuk berfungsi)
Route::get('/api/glossary/search', [ModuleController::class, 'searchGlossary']);
Route::post('/api/questions/submit', [ModuleController::class, 'submitQuestion']);

// ================= TAMU (belum login) =================

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);

    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
});

// ================= WAJIB LOGIN =================
// Di sinilah gate utamanya: tamu yang coba akses salah satu rute di bawah
// ini otomatis dilempar ke /login (bawaan middleware 'auth' + exception
// handler Laravel), dan URL tujuannya "diingat" otomatis lewat mekanisme
// redirect()->intended() bawaan framework.

Route::middleware('auth')->group(function () {
    // Detail modul pembelajaran
    // Parameter {slug} adalah URL-friendly identifier modul
    // Contoh: /modul/pubertas, /modul/mengenal-tubuh-kita, /modul/menstruasi
    Route::get('/modul/{slug}', [ModuleController::class, 'show'])->name('module.show');

    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // API: Submit jawaban kuis (Pre-Test & Post-Test)
    // Request: POST /api/quiz/submit
    // Body: { quiz_id: int, selected_option_id: int, type?: 'pre'|'post' }
    Route::post('/api/quiz/submit', [ModuleController::class, 'submitQuiz'])->name('quiz.submit');

    // API: Tandai modul selesai (dipanggil setelah Post-Test disubmit)
    // Request: POST /api/modules/{slug}/complete
    Route::post('/api/modules/{slug}/complete', [ModuleController::class, 'completeModule'])->name('modules.complete');
});

// ================= KHUSUS ADMIN =================
// Middleware 'admin' perlu didaftarkan dulu di bootstrap/app.php (atau
// app/Http/Kernel.php kalau masih struktur lama) — lihat README bagian
// "Mendaftarkan middleware 'admin'".

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Masih kerangka minimal — lihat views/admin/dashboard.blade.php
    Route::get('/', fn() => view('admin.dashboard'))->name('dashboard');
});

/**
 * NOTES:
 *
 * 1. Naming Routes:
 *    - name('home') → route('home')
 *    - name('belajar'), name('glosarium'), name('bantuan'), name('tentang')
 *      → dipakai navbar di layouts/app.blade.php
 *    - name('module.show') → route('module.show', $slug)
 *    - name('login') / name('register') / name('logout')
 *    - name('admin.dashboard')
 *
 * 2. Parameter {slug}:
 *    - Auto-binding ke parameter $slug di ModuleController@show()
 *    - Misalnya: /modul/pubertas → $slug = 'pubertas'
 *
 * 3. Kenapa /modul/{slug} dipindah ke dalam grup 'auth':
 *    - Alur yang diminta: tamu klik card modul di Beranda/Belajar →
 *      diarahkan login/daftar dulu → begitu berhasil, otomatis diarahkan
 *      balik ke modul yang tadi mau dibuka (BUKAN cuma ke beranda).
 *    - Itu semua sudah beres cuma dengan (a) taruh route ini di dalam
 *      middleware 'auth', dan (b) controller login/register pakai
 *      redirect()->intended() — tidak ada logika "simpan URL tujuan"
 *      tambahan yang perlu ditulis manual, itu bawaan Laravel.
 *
 * 4. API Routes yang masih publik (glossary/search, questions/submit):
 *    - Sengaja TIDAK ikut dipindah ke grup 'auth' karena tidak
 *      menyimpan/butuh data yang terikat akun. Kalau nanti mau
 *      pertanyaan Tanya Ahli juga tercatat per-akun (bukan anonim lewat
 *      session ID seperti sekarang), baru perlu dipindah & disesuaikan.
 */