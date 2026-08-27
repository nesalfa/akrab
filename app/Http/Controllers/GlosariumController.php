<?php
namespace App\Http\Controllers;


use App\Models\Glossary;
use Illuminate\Http\Request;

class GlosariumController extends Controller
{
    public function glosarium(Request $request)
    {
        $query = $request->input('q');

        // Mengambil data dari tabel menggunakan model Glossary
        // Menggunakan when() agar pencarian hanya jalan kalau ada inputan di search bar
        $glosariums = Glossary::when($query, function ($q) use ($query) {
            // Catatan: Sesuaikan 'term' dan 'definition' dengan nama kolom di database kamu
            return $q->where('term', 'like', "%{$query}%")
                ->orWhere('definition', 'like', "%{$query}%");
        })->get();

        // Kalau datanya nanti sangat banyak, ganti ->get() menjadi ->paginate(10)

        // Mengirimkan variabel $glosariums ke view glosarium.blade.php
        return view('glosarium', compact('glosariums'));
    }
}