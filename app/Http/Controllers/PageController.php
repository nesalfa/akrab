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
        $myConsultations = collect();
        if (auth()->check()) {
            $myConsultations = Consultation::where('user_id', auth()->id())
                ->latest()
                ->get();
        }

        return view('tanya-ahli', compact('myConsultations'));
    }

    public function tanyaAhliStore(Request $request)
    {
        // Validasi input dari form
        $rules = [
            'question' => 'required|string|min:5',
        ];

        if (!auth()->check()) {
            $rules['name'] = 'required|string|max:255';
            $rules['email'] = 'nullable|email|max:255';
        }

        $validated = $request->validate($rules);

        // Simpan data ke tabel consultations
        Consultation::create([
            'user_id' => auth()->id() ?? null,
            'name' => auth()->check() ? auth()->user()->name : $validated['name'],
            'email' => auth()->check() ? auth()->user()->email : ($request->email ?? null),
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
