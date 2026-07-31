<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ModuleController;

Route::get('/', function () {
    return view('home'); // Halaman beranda
});

// Rute untuk modul pembelajaran
Route::get('/modul/{slug}', [ModuleController::class, 'show'])->name('module.show');

// Rute API untuk AJAX (opsional)
Route::get('/api/modul/{id}', [ModuleController::class, 'getData']);

// Route::get('/', function () {
//     return view('welcome');
// });
