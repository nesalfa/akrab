<?php

namespace App\Http\Controllers;

use App\Models\Module;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    /**
     * Menampilkan halaman beranda dengan daftar semua modul.
     */
    public function index()
    {
        // Query semua modul dari database
        $modules = Module::all();

        // Return ke view beranda dengan membawa data modul
        return view('beranda', compact('modules'));
    }

    /**
     * Menampilkan detail halaman modul berdasarkan ID.
     */
    public function show($id)
    {
        // Query modul berdasarkan ID beserta konten/materi di dalamnya
        $module = Module::with('contents')->findOrFail($id);

        // Return ke view detail modul
        return view('modul.show', compact('module'));
    }
}