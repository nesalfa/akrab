<?php

namespace App\Http\Controllers;

use App\Models\Module;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    /**
     * Tampilkan Halaman Beranda Utama
     * Route: GET /
     * View: home.blade.php
     */
    public function home()
    {
        // Ambil semua modul aktif dari DB untuk kebutuhan section di Beranda
        $modules = Module::where('is_active', true)->orderBy('order')->get();
        $modulesGrouped = $modules->groupBy('section');

        return view('home', [
            'modules' => $modules,
            'modulesGrouped' => $modulesGrouped,
        ]);
    }

    /**
     * Tampilkan Halaman Daftar Pembelajaran (Linear Grid)
     * Route: GET /belajar
     * View: belajar.blade.php
     */
    public function index()
    {
        // Ambil semua modul aktif langsung dari database secara urut tanpa pembagian klaster
        $modules = Module::where('is_active', true)
            ->orderBy('order', 'asc')
            ->get();

        return view('belajar', compact('modules'));
    }

    /**
     * Tampilkan halaman detail modul dengan konten lengkap
     * 
     * Route: GET /modul/{slug}
     * View: modules/show.blade.php
     * Parameter: $slug (string) - URL slug dari modul (misal: "pubertas", "mengenal-tubuhku")
     *
     * UPDATE: rute ini sekarang WAJIB login (pasang middleware 'auth' di
     * routes/web.php — lihat routes-snippet.php). Karena itu, $totalModules
     * dan $completedModulesCount di bawah ini aman dihitung langsung dari
     * auth()->user(), tidak perlu jaga-jaga guest lagi.
     */
    public function show($slug)
    {
        // Cari modul berdasarkan slug
        $module = Module::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail(); // Jika tidak ketemu, throw 404 error

        // Ambil semua konten modul, urutkan berdasarkan 'order'
        $contents = $module->moduleContent()
            ->orderBy('order')
            ->get();

        // Ambil semua kuis beserta pilihan jawaban, urutkan berdasarkan 'order'
        $quizzes = $module->quizzes()
            ->with('options')
            ->orderBy('order')
            ->get();

        // Ambil semua glosarium
        $glossary = $module->glossary()
            ->orderBy('order')
            ->get();

        // Ambil FAQ untuk modul ini
        $faq = $module->faq()
            ->orderBy('order')
            ->get();

        // Cari modul sebelumnya (prev)
        $prevModule = Module::where('section', $module->section)
            ->where('order', '<', $module->order)
            ->where('is_active', true)
            ->orderBy('order', 'desc')
            ->first();

        // Cari modul selanjutnya (next)
        $nextModule = Module::where('section', $module->section)
            ->where('order', '>', $module->order)
            ->where('is_active', true)
            ->orderBy('order')
            ->first();

        // Data progress untuk sidebar — dihitung dari kolom users.completed_modules,
        // BUKAN localStorage lagi.
        $user = auth()->user();
        $totalModules = Module::where('is_active', true)->count();
        $completedModulesCount = $user ? $user->completedModulesCount() : 0;
        $isThisModuleCompleted = $user ? $user->hasCompletedModule($module->slug) : false;

        // Pass semua data ke view
        return view('modules.show', [
            'module' => $module,
            'contents' => $contents,
            'quizzes' => $quizzes,
            'glossary' => $glossary,
            'faq' => $faq,
            'prevModule' => $prevModule,
            'nextModule' => $nextModule,
            'totalModules' => $totalModules,
            'completedModulesCount' => $completedModulesCount,
            'isThisModuleCompleted' => $isThisModuleCompleted,
        ]);
    }

    /**
     * API endpoint untuk submit kuis jawaban
     * 
     * Route: POST /api/quiz/submit
     * Request body: { quiz_id: int, selected_option_id: int, type?: 'pre'|'post' }
     * Response: { is_correct: bool, correct_option: {...}, feedback: string }
     * 
     * PENTING: Endpoint ini untuk AJAX call dari frontend JavaScript
     * Bukan untuk form submission tradisional
     *
     * UPDATE (data riset Pre/Post-Test): setiap jawaban yang masuk lewat
     * endpoint ini direkam ke tabel quiz_attempts dengan `user_id` dari
     * auth()->id() — endpoint ini sekarang mengasumsikan pemanggilnya
     * SUDAH login (halaman modul di belakang middleware 'auth'). Kalau
     * suatu saat endpoint ini dipanggil dari halaman yang tidak
     * mewajibkan login, tambahkan pengecekan auth()->check() di sini.
     */
    public function submitQuiz(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'quiz_id' => 'required|exists:quizzes,id',
            'selected_option_id' => 'required|exists:quiz_options,id',
            'type' => 'nullable|in:pre,post',
        ]);

        // Ambil quiz & pilihan yang dipilih user
        $quiz = \App\Models\Quiz::find($validated['quiz_id']);
        $selectedOption = \App\Models\QuizOption::find($validated['selected_option_id']);

        // Ambil jawaban yang benar
        $correctOption = $quiz->options()->where('is_correct', true)->first();

        // Cek apakah jawaban user benar
        $isCorrect = $selectedOption->is_correct;

        // Rekam attempt untuk data riset Pre/Post-Test.
        // module_id diambil dari relasi quiz (quizzes.module_id), tidak perlu
        // dikirim terpisah dari frontend.
        \App\Models\QuizAttempt::create([
            'module_id' => $quiz->module_id,
            'user_id' => auth()->id(),
            'quiz_id' => $quiz->id,
            'selected_option_id' => $selectedOption->id,
            'is_correct' => $isCorrect,
            'type' => $validated['type'] ?? 'post',
        ]);

        // Buat feedback message
        if ($isCorrect) {
            $feedback = "✓ Jawaban Anda benar! Selamat!";
        } else {
            $feedback = "✗ Jawaban kurang tepat. Jawaban yang benar adalah: " . $correctOption->text;
        }

        // Return response JSON
        return response()->json([
            'is_correct' => $isCorrect,
            'correct_option' => $correctOption,
            'feedback' => $feedback,
            'selected_option_id' => $validated['selected_option_id'],
        ]);
    }

    /**
     * API endpoint: tandai satu modul selesai (dipanggil setelah semua
     * jawaban Post-Test berhasil disubmit — lihat show.blade.php).
     *
     * Route: POST /api/modules/{slug}/complete
     * Response: { completed_modules_count: int, total_modules: int, percentage: int }
     *
     * SENGAJA TIDAK ADA syarat skor minimal — menyelesaikan Post-Test
     * (menjawab semua pertanyaan) sudah cukup untuk menandai modul
     * selesai, konsisten dengan keputusan sebelumnya: tidak ada
     * nilai/keterangan lulus-tidak lulus yang ditampilkan ke user.
     */
    public function completeModule(Request $request, string $slug)
    {
        $module = Module::where('slug', $slug)->where('is_active', true)->firstOrFail();

        /** @var \App\Models\User $user */
        $user = $request->user();
        $user->markModuleCompleted($module->slug);

        $total = Module::where('is_active', true)->count();
        $completedCount = $user->fresh()->completedModulesCount();

        return response()->json([
            'completed_modules_count' => $completedCount,
            'total_modules' => $total,
            'percentage' => $total > 0 ? (int) round(($completedCount / $total) * 100) : 0,
        ]);
    }

    /**
     * Search glosarium berdasarkan term
     * 
     * Route: GET /api/glossary/search?q=pubertas
     * Response: Array of glossary items yang cocok
     */
    public function searchGlossary(Request $request)
    {
        $query = $request->input('q');

        if (!$query || strlen($query) < 2) {
            return response()->json([]);
        }

        // Cari glossary items yang cocok dengan term
        $results = \App\Models\Glossary::where('term', 'LIKE', '%' . $query . '%')
            ->orWhere('definition', 'LIKE', '%' . $query . '%')
            ->limit(10)
            ->get();

        return response()->json($results);
    }

    /**
     * Simpan pertanyaan untuk Tanya Ahli
     * 
     * Route: POST /api/questions/submit
     * Request body: { module_id: int, question_text: string }
     * Response: { success: bool, message: string }
     * 
     * PENTING: Tidak menyimpan data pribadi user!
     */
    public function submitQuestion(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'module_id' => 'nullable|exists:modules,id',
            'question_text' => 'required|string|min:10|max:1000',
        ]);

        // Generate anonymous ID berdasarkan session
        // (bukan email atau personal data!)
        $anonymousId = session()->getId();

        // Simpan pertanyaan
        \App\Models\Question::create([
            'module_id' => $validated['module_id'],
            'question_text' => $validated['question_text'],
            'status' => 'pending',
            'anonymous_id' => $anonymousId,
        ]);

        // Return response
        return response()->json([
            'success' => true,
            'message' => 'Pertanyaan Anda sudah kami terima. Tim ahli kami akan menjawab dalam waktu singkat. Terima kasih telah bertanya! 💚',
        ]);
    }
}