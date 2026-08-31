<?php

namespace App\Http\Controllers;

use App\Models\Glossary;
use App\Models\Module;
use App\Models\QuizAttempt;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\QuizOption;
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
        $modules = Module::where('is_active', true)->orderBy('order')->get();
        $modulesGrouped = $modules->groupBy('section');

        $completedModulesCount = 0;
        $latestModule = null;
        $totalModules = Module::where('is_active', true)->count(); // Hitung total modul aktif

        if (auth()->check()) {
            $user = auth()->user();

            // Hitung total modul selesai
            $completedModulesCount = method_exists($user, 'completedModulesCount') ? $user->completedModulesCount() : 0;

            // Cari aktivitas attempt kuis terakhir
            $latestAttempt = QuizAttempt::where('user_id', $user->id)
                ->latest('updated_at')
                ->first();

            if ($latestAttempt) {
                $latestModule = Module::find($latestAttempt->module_id);
            }
        }

        // Hitung persentase progress keseluruhan (misal: selesai 2 dari 15 = 13%)
        $progressPct = $totalModules > 0 ? (int) round(($completedModulesCount / $totalModules) * 100) : 0;

        return view('home', [
            'modules' => $modules,
            'modulesGrouped' => $modulesGrouped,
            'completedModulesCount' => $completedModulesCount,
            'latestModule' => $latestModule,
            'totalModules' => $totalModules,
            'progressPct' => $progressPct, // Kirim ke view home.blade.php
        ]);
    }

    /**
     * Tampilkan Halaman Daftar Pembelajaran (Linear Grid)
     * Route: GET /belajar
     * View: belajar.blade.php
     */
    public function index()
    {
        $modules = Module::where('is_active', true)
            ->orderBy('order', 'asc')
            ->get();

        // Ambil daftar ID modul yang sudah diselesaikan user untuk menandai card berwarna pink
        $completedModuleIds = [];
        if (auth()->check()) {
            $user = auth()->user();
            // Mengambil ID modul dari quiz_attempts dengan tipe 'post'
            $completedModuleIds = QuizAttempt::where('user_id', $user->id)
                ->where('type', 'post')
                ->pluck('module_id')
                ->unique()
                ->toArray();
        }

        return view('belajar', [
            'modules' => $modules,
            'completedModuleIds' => $completedModuleIds,
        ]);
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
        $module = Module::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $contents = $module->moduleContent()->orderBy('order')->get();
        $quizzes = $module->quizzes()->with('options')->orderBy('order')->get();
        $glossary = $module->glossary()->orderBy('order')->get();
        $faq = $module->faq()->orderBy('order')->get();

        $prevModule = Module::where('section', $module->section)
            ->where('order', '<', $module->order)
            ->where('is_active', true)
            ->orderBy('order', 'desc')
            ->first();

        $nextModule = Module::where('section', $module->section)
            ->where('order', '>', $module->order)
            ->where('is_active', true)
            ->orderBy('order')
            ->first();

        $user = auth()->user();
        $totalModules = Module::where('is_active', true)->count();
        $completedModulesCount = $user ? $user->completedModulesCount() : 0;
        $isThisModuleCompleted = $user ? $user->hasCompletedModule($module->slug) : false;

        // AMBIL RIWAYAT JAWABAN KUIS USER DI MODUL INI
        $preAttempts = collect();
        $postAttempts = collect();

        if ($user) {
            $userAttempts = QuizAttempt::where('user_id', $user->id)
                ->where('module_id', $module->id)
                ->get();

            $preAttempts = $userAttempts->where('type', 'pre')->keyBy('quiz_id');
            $postAttempts = $userAttempts->where('type', 'post')->keyBy('quiz_id');
        }

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
            'preAttempts' => $preAttempts, // Kirim ke blade
            'postAttempts' => $postAttempts, // Kirim ke blade
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
        $quiz = Quiz::find($validated['quiz_id']);
        $selectedOption = QuizOption::find($validated['selected_option_id']);

        // Ambil jawaban yang benar
        $correctOption = $quiz->options()->where('is_correct', true)->first();

        // Cek apakah jawaban user benar
        $isCorrect = $selectedOption->is_correct;

        // Rekam attempt untuk data riset Pre/Post-Test secara idempotent.
        // Satu user + module + type + quiz hanya memiliki 1 jawaban valid (diperbarui jika ada request baru)
        QuizAttempt::updateOrCreate(
            [
                'module_id' => $quiz->module_id,
                'user_id' => auth()->id(),
                'quiz_id' => $quiz->id,
                'type' => $validated['type'] ?? 'post',
            ],
            [
                'selected_option_id' => $selectedOption->id,
                'is_correct' => $isCorrect,
            ]
        );

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
        $results = Glossary::where('term', 'LIKE', '%' . $query . '%')
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
        Question::create([
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