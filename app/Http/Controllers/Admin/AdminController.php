<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\Quiz;
use App\Models\QuizOption;
use App\Models\Glossary;
use App\Models\User;
use App\Models\Consultation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    /**
     * Menampilkan dashboard admin.
     */
    public function index(Request $request)
    {
        return view('admin.dashboard', [
            'user' => $request->user(),
        ]);
    }

    // Menampilkan Dashboard Utama dengan Data Dinamis
    public function dashboard()
    {
        $totalModules = Module::count();
        $totalUsers = User::where('role', 'user')->count();
        $pendingConsultations = Consultation::where('status', 'pending')->count();

        // Tarik 3 pesan terbaru untuk ditampilkan di dashboard sebagai pengganti teks kaku
        $recentConsultations = Consultation::where('status', 'pending')->latest()->take(3)->get();

        // Logika persentase progres (sementara diset 0 karena belum ada fitur kuis berjalan)
        $percentSelesai = 0;
        $percentProses = 0;
        $percentBelum = 100; // 100% user belum mulai (Abu-abu)

        return view('admin.dashboard', compact(
            'totalModules',
            'totalUsers',
            'pendingConsultations',
            'recentConsultations',
            'percentSelesai',
            'percentProses',
            'percentBelum'
        ));
    }

    public function modulesIndex()
    {
        // Ambil semua data modul dari database (diurutkan dari yang terbaru)
        $modules = Module::latest()->get();

        return view('admin.modules', compact('modules'));
    }

    public function modulesStore(Request $request)
    {
        // Validasi input
        $request->validate([
            'title' => 'required|string|max:255',
            'order' => 'required|integer|min:1',
        ]);

        // Simpan ke database
        Module::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title), // Otomatis ubah "Judul Modul" jadi "judul-modul"
            'description' => $request->description,
            'section' => 'mulai-belajar', // Default otomatis karena kolom ini tidak ada di form
            'order' => $request->order,
            'is_active' => $request->has('is_active') ? 1 : 0,
        ]);

        return back()->with('success', 'Modul baru berhasil ditambahkan!');
    }

    /**
     * Simpan perubahan dari Modal Edit.
     * Route: PUT /admin/modules/{module}
     */
    public function update(Request $request, Module $module)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'section' => ['required', 'in:mulai-belajar,jaga-diri,lainnya'],
            'order' => ['required', 'integer', 'min:1'],
        ]);

        // Checkbox yang tidak dicentang tidak ikut terkirim sama sekali oleh
        // browser, jadi harus dibaca manual pakai boolean(), bukan divalidasi.
        $validated['is_active'] = $request->boolean('is_active');

        $module->update($validated);

        return redirect()
            ->route('admin.modules')
            ->with('success', "Modul \"{$module->title}\" berhasil diperbarui.");
    }

    /**
     * Hapus modul. Karena foreign key module_content/quizzes/glossary/faq
     * semuanya sudah diset onDelete('cascade') di migration, menghapus 1
     * baris Module di sini OTOMATIS ikut menghapus seluruh konten, kuis,
     * dan glosarium yang menempel padanya di level database — makanya
     * modal konfirmasinya sengaja mewanti-wanti soal ini.
     * Route: DELETE /admin/modules/{module}
     */
    public function destroy(Module $module)
    {
        $title = $module->title;
        $module->delete();

        return redirect()
            ->route('admin.modules')
            ->with('success', "Modul \"{$title}\" beserta seluruh isinya berhasil dihapus.");
    }

    // Method untuk menampilkan halaman Kelola Kuis & Glosarium
    public function kuisGlosariumIndex()
    {
        // Ambil data modul beserta hitungan jumlah soal kuisnya
        $modules = Module::withCount('quizzes')->orderBy('order', 'asc')->get();

        // Ambil data modul untuk tab Manajemen Kuis (urutkan berdasarkan urutan atau terbaru)
        // $modules = Module::orderBy('order', 'asc')->get();

        // Ambil data glosarium untuk tab Kamus Glosarium (urutkan abjad A-Z)
        $glossaries = Glossary::orderBy('term', 'asc')->get();

        // Pastikan nama view ini sesuai dengan struktur folder yang kamu buat (misal: admin/kuis-glosarium/index.blade.php)
        return view('admin.kuis-glosarium', compact('modules', 'glossaries'));
    }

    // --- METHOD UNTUK GLOSARIUM ---

    public function glosariumStore(Request $request)
    {
        $request->validate([
            'module_id' => 'required',
            'term' => 'required|string|max:255',
            'definition' => 'required|string',
        ]);

        // Cari urutan (order) terakhir di modul yang dipilih
        $lastOrder = Glossary::where('module_id', $request->module_id)->max('order') ?? 0;

        Glossary::create([
            'module_id' => $request->module_id,
            'term' => $request->term,
            'definition' => $request->definition,
            'order' => $lastOrder + 1, // Otomatis lanjut ke angka berikutnya
        ]);

        return back()->with('success', 'Istilah baru berhasil ditambahkan ke glosarium!');
    }

    public function glosariumUpdate(Request $request, $id)
    {
        $request->validate([
            'module_id' => 'required',
            'term' => 'required|string|max:255',
            'definition' => 'required|string',
        ]);

        $glossary = Glossary::findOrFail($id);

        // Cek jika admin mengubah modul, maka order harus disesuaikan ulang
        if ($glossary->module_id != $request->module_id) {
            $lastOrder = Glossary::where('module_id', $request->module_id)->max('order') ?? 0;
            $newOrder = $lastOrder + 1;
        } else {
            $newOrder = $glossary->order; // Tetap gunakan order yang lama
        }

        $glossary->update([
            'module_id' => $request->module_id,
            'term' => $request->term,
            'definition' => $request->definition,
            'order' => $newOrder,
        ]);

        return back()->with('success', 'Istilah glosarium berhasil diperbarui!');
    }

    public function glosariumDestroy($id)
    {
        $glossary = Glossary::findOrFail($id);

        // Simpan module_id sebelum dihapus (jika nanti ingin merapikan ulang urutan/order)
        $moduleId = $glossary->module_id;

        $glossary->delete();

        return back()->with('success', 'Istilah glosarium berhasil dihapus!');
    }

    public function kelolaSoal($module_id)
    {
        // Cari modul berdasarkan ID, jika tidak ada akan memunculkan 404
        $module = Module::findOrFail($module_id);

        // Ambil semua soal kuis yang terkait dengan modul tersebut
        $quizzes = Quiz::with('options')->where('module_id', $module_id)->orderBy('order', 'asc')->get();

        return view('admin.kelola-soal', compact('module', 'quizzes'));
    }

    public function storeSoal(Request $request, $module_id)
    {
        // Cari nomor urut terakhir di modul ini, lalu tambah 1
        $lastOrder = Quiz::where('module_id', $module_id)->max('order') ?? 0;

        // 1. Simpan Pertanyaannya
        $quiz = Quiz::create([
            'module_id' => $module_id,
            'question' => $request->question,
            'type' => 'pilihan_ganda', // Tipe default
            'order' => $lastOrder + 1,
        ]);

        // 2. Simpan Opsi Jawabannya (A, B, C, D)
        $labels = ['A', 'B', 'C', 'D'];

        foreach ($request->options as $index => $text) {
            // Hanya simpan opsi jika kolom teksnya diisi
            if (!empty($text)) {
                QuizOption::create([
                    'quiz_id' => $quiz->id,
                    'label' => $labels[$index],
                    'text' => $text,
                    'is_correct' => $request->correct_option == $index ? 1 : 0,
                    'order' => $index + 1
                ]);
            }
        }

        return back()->with('success', 'Pertanyaan baru berhasil ditambahkan!');
    }

    public function updateSoal(Request $request, $id)
    {
        $quiz = Quiz::findOrFail($id);
        $quiz->update(['question' => $request->question]);

        // Looping untuk update teks jawaban dan mengatur kunci jawaban (is_correct)
        foreach ($request->options as $optionId => $data) {
            QuizOption::where('id', $optionId)->update([
                'text' => $data['text'],
                'is_correct' => $request->correct_option == $optionId ? 1 : 0
            ]);
        }

        return back()->with('success', 'Soal dan jawaban berhasil diperbarui!');
    }

    public function destroySoal($id)
    {
        $quiz = Quiz::findOrFail($id);
        $quiz->options()->delete(); // Hapus opsi jawabannya dulu
        $quiz->delete(); // Baru hapus soalnya

        return back()->with('success', 'Soal berhasil dihapus!');
    }

    // Menampilkan Daftar Pesan Tanya Ahli untuk Dijawab Admin
    public function consultationsIndex()
    {
        $consultations = Consultation::latest()->paginate(10);
        return view('admin.consultations.index', compact('consultations'));
    }

    // Menyimpan Jawaban Admin ke Database
    public function consultationsAnswer(Request $request, $id)
    {
        $request->validate([
            'answer' => 'required|string',
        ]);

        $consultation = Consultation::findOrFail($id);
        $consultation->update([
            'answer' => $request->answer,
            'status' => 'answered',
        ]);

        return redirect()->route('admin.consultations')->with('success', 'Jawaban berhasil dikirim dan dipublikasikan!');
    }
}
