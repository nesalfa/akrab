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
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\GlosariumController;
use App\Http\Controllers\Admin\AdminController;
use Illuminate\Support\Facades\Route;

// ================= PUBLIK (tidak perlu login) =================

// Halaman pertama / Root URL (Menampilkan Beranda Utama)
Route::get('/', [ModuleController::class, 'home'])->name('home');

// Halaman menu klaster pembelajaran / daftar modul (Menampilkan belajar.blade.php)
Route::get('/belajar', [ModuleController::class, 'index'])->name('belajar');

// Halaman informasional — ditangani PageController (dipisah dari
// ModuleController, lihat diskusi soal pemisahan tanggung jawab controller)
// Route::get('/glosarium', [PageController::class, 'glosarium'])->name('glosarium');

Route::get('/glosarium', [GlosariumController::class, 'glosarium'])->name('glosarium');

// Halaman Utama / Hub Bantuan
Route::get('/bantuan', [PageController::class, 'bantuan'])->name('bantuan');

// Sub-halaman Bantuan
Route::get('rujukan', [PageController::class, 'bantuanRujukan'])->name('rujukan');
Route::get('tanya-ahli', [PageController::class, 'tanyaAhliCreate'])->name('tanya-ahli');
Route::post('tanya-ahli', [PageController::class, 'tanyaAhliStore'])->name('tanya-ahli.store');
Route::get('pendamping', [PageController::class, 'panduanPendamping'])->name('pendamping');

Route::get('/tentang', [PageController::class, 'tentang'])->name('tentang');

// API publik (tidak butuh identitas user untuk berfungsi)
Route::get('/api/glossary/search', [ModuleController::class, 'searchGlossary']);
Route::post('/api/questions/submit', [ModuleController::class, 'submitQuestion']);

// ================= TAMU (belum login) =================

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);

    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);

    // Lupa Kata Sandi — alur OTP 6 digit lewat email (bukan link reset
    // bawaan Laravel). Cuma berlaku untuk akun role 'user' (email-based);
    // admin login pakai kode staf, tidak relevan di sini.
    Route::get('/forgot-password', [PasswordResetController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'store'])->name('password.email');

    Route::get('/reset-password', [PasswordResetController::class, 'showResetForm'])->name('password.reset.form');
    Route::post('/reset-password', [PasswordResetController::class, 'update'])->name('password.update');
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
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    // Kelola Modul
    Route::get('/modules', [AdminController::class, 'modulesIndex'])->name('modules');

    /**
     * PENTING: {module} di URL harus PERSIS namanya "module" (bukan
     * "modul" atau "id") supaya cocok dengan parameter
     * `App\Models\Module $module` di method update()/destroy() — Laravel
     * mencocokkan lewat NAMA parameter, bukan urutan.
     */
    Route::post('/modules', [AdminController::class, 'modulesStore'])->name('modules.store');
    Route::put('/modules/{module}', [AdminController::class, 'update'])->name('modules.update');
    Route::delete('/modules/{module}', [AdminController::class, 'destroy'])->name('modules.destroy');
    // Kelola Kuis & Glosarium
    Route::get('/kuis-glosarium', [AdminController::class, 'kuisGlosariumIndex'])->name('kuis-glosarium');
    // Rute Kelola Glosarium
    Route::post('/glosarium', [AdminController::class, 'glosariumStore'])->name('glosarium.store');
    Route::put('/glosarium/{id}', [AdminController::class, 'glosariumUpdate'])->name('glosarium.update');
    Route::delete('/glosarium/{id}', [AdminController::class, 'glosariumDestroy'])->name('glosarium.destroy');
    // Masuk ke halaman detail soal per modul
    Route::get('/kuis-glosarium/{module_id}/soal', [AdminController::class, 'kelolaSoal'])->name('kuis-kelola');
    Route::post('/kuis-glosarium/soal/{module_id}', [AdminController::class, 'storeSoal'])->name('kuis.store');
    Route::put('/kuis-glosarium/soal/{id}', [AdminController::class, 'updateSoal'])->name('kuis.update');
    Route::delete('/kuis-glosarium/soal/{id}', [AdminController::class, 'destroySoal'])->name('kuis.destroy');

    // Pesan Tanya Ahli
    Route::get('/consultations', [AdminController::class, 'consultationsIndex'])->name('consultations');
    Route::post('/consultations/{id}/answer', [AdminController::class, 'consultationsAnswer'])->name('consultations.answer');

    // Progres Belajar Anak
    Route::get('/progress', [AdminController::class, 'progressIndex'])->name('progress');
    Route::get('/progress/export/rekap', [AdminController::class, 'progressExportRekap'])->name('progress.export.rekap');
    Route::get('/progress/{id}/export', [AdminController::class, 'progressExportDetail'])->name('progress.export.detail');
    Route::get('/progress/{id}', [AdminController::class, 'progressShow'])->name('progress.show');

    // Profil Admin
    Route::get('/profile', [AdminController::class, 'profile'])->name('profile');
    Route::put('/profile', [AdminController::class, 'updateProfile'])->name('profile.update');
});



// Rute Tanya Ahli (yang sebelumnya)
// Route::get('/consultations', ...

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