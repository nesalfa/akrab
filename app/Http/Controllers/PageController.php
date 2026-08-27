<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Consultation;

/**
 * Controller khusus untuk halaman informasional/statis di navbar yang
 * BUKAN seputar Module (beda dari ModuleController). Sengaja dipisah
 * sesuai diskusi sebelumnya — supaya ModuleController tetap fokus ke
 * urusan modul pembelajaran, bukan jadi tempat sampah untuk semua route.
 *
 * Masih halaman placeholder (isinya minimal) — silakan kembangkan tiap
 * method di bawah begitu kontennya sudah jelas mau seperti apa.
 */
class PageController extends Controller
{
    public function glosarium()
    {
        return view('glosarium');
    }

    public function bantuan()
    {
        return view('bantuan');
    }

    // public function bantuanIndex()
    // {
    //     return view('bantuan.index');
    // }

    public function bantuanRujukan()
    {
        return view('rujukan');
    }

    public function tanyaAhliCreate()
    {
        return view('tanya-ahli');
    }

    public function tanyaAhliStore(Request $request)
    {
        // Validasi input dari form
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'question' => 'required|string|min:5',
        ]);

        // Simpan data ke tabel consultations
        Consultation::create([
            'name' => $validated['name'],
            'email' => $request->email ?? null, // Ambil email jika ada/diisi
            'question' => $validated['question'],
            'status' => 'pending', // Status otomatis pending agar masuk antrean admin
        ]);

        // Redirect kembali dengan pesan sukses
        return redirect()->route('tanya-ahli')->with('success', 'Pertanyaan kamu berhasil dikirim! Tim ahli akan segera merespons.');
    }

    public function panduanPendamping()
    {
        return view('pendamping');
    }

    public function tentang()
    {
        return view('tentang');
    }
}
