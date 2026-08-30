<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SimpleXlsxExporter;
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

        // Hitung persentase progres nyata dari user yang menyelesaikan modul
        $totalActiveModules = Module::where('is_active', true)->count();
        $percentSelesai = 0;
        $percentProses = 0;
        $percentBelum = 100;

        if ($totalUsers > 0 && $totalActiveModules > 0) {
            $users = User::where('role', 'user')->with(['quizAttempts'])->get();
            $modules = Module::with('quizzes')->where('is_active', true)->get();
            $totalCompletedAll = 0;
            $totalInProgress = 0;
            $totalNotStarted = 0;

            foreach ($users as $u) {
                $validAttempts = $u->validQuizAttempts();
                $completedModCount = 0;

                foreach ($modules as $mod) {
                    if ($u->getModuleProgressStatus($mod, $validAttempts) === 'completed') {
                        $completedModCount++;
                    }
                }

                if ($completedModCount >= $totalActiveModules) {
                    $totalCompletedAll++;
                } elseif ($completedModCount > 0 || $validAttempts->isNotEmpty()) {
                    $totalInProgress++;
                } else {
                    $totalNotStarted++;
                }
            }

            $percentSelesai = (int) round(($totalCompletedAll / $totalUsers) * 100);
            $percentProses = (int) round(($totalInProgress / $totalUsers) * 100);
            $percentBelum = max(0, 100 - ($percentSelesai + $percentProses));
        }

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
        // Ambil semua data modul dari database (diurutkan berdasarkan nomor urutan pembelajaran ascending)
        $modules = Module::orderBy('order', 'asc')
            ->orderBy('id', 'asc')
            ->get();

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
            'section' => ['nullable', 'in:mulai-belajar,jaga-diri,lainnya'],
            'order' => ['required', 'integer', 'min:1'],
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        $validated['section'] = $validated['section'] ?? $module->section ?? 'mulai-belajar';
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
    public function consultationsIndex(Request $request)
    {
        $status = $request->query('status');
        $search = trim((string) $request->query('search', ''));

        $query = Consultation::with('responder');

        if ($status && in_array($status, ['pending', 'answered'])) {
            $query->where('status', $status);
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('question', 'like', "%{$search}%");
            });
        }

        // Urutkan prioritas: pending lebih dulu (terbaru), lalu answered (terbaru)
        $consultations = $query->orderByRaw("CASE WHEN status = 'pending' THEN 0 ELSE 1 END")
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $pendingCount = Consultation::where('status', 'pending')->count();
        $answeredCount = Consultation::where('status', 'answered')->count();

        return view('admin.consultations', compact('consultations', 'pendingCount', 'answeredCount', 'status', 'search'));
    }

    // Menyimpan Jawaban Admin ke Database
    public function consultationsAnswer(Request $request, $id)
    {
        $request->validate([
            'answer' => 'required|string|min:3',
        ]);

        $consultation = Consultation::findOrFail($id);

        \Illuminate\Support\Facades\DB::transaction(function () use ($consultation, $request) {
            $consultation->update([
                'answer' => $request->answer,
                'status' => 'answered',
                'answered_by' => auth()->id(),
                'answered_at' => now(),
            ]);
        });

        return redirect()->route('admin.consultations')->with('success', 'Jawaban berhasil dikirim ke pengguna!');
    }

    // ================= PROGRES BELAJAR ANAK =================

    public function progressIndex(Request $request)
    {
        $search = trim((string) $request->query('search', ''));
        $moduleId = $request->query('module_id');
        $statusFilter = $request->query('status'); // 'completed', 'in_progress', 'not_started'

        $allModules = Module::with('quizzes')->orderBy('order', 'asc')->get();
        $activeModules = $allModules->where('is_active', true);
        $totalActiveModules = $activeModules->count();

        $query = User::where('role', 'user')->with(['quizAttempts.module', 'quizAttempts.quiz']);

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%");
            });
        }

        // Ambil data users dengan pagination
        $usersPaginator = $query->latest()->paginate(10)->withQueryString();

        // Transform collection untuk metrik ringkasan
        $usersPaginator->getCollection()->transform(function ($user) use ($activeModules, $totalActiveModules) {
            $validAttempts = $user->validQuizAttempts();
            $completedCount = $user->completedModulesCount($activeModules, $validAttempts);
            $totalAttempts = $validAttempts->count();
            $correctAttempts = $validAttempts->where('is_correct', true)->count();
            $averageScore = $totalAttempts > 0 ? (int) round(($correctAttempts / $totalAttempts) * 100) : 0;
            $lastActivity = $validAttempts->sortByDesc('created_at')->first()?->created_at;

            $status = 'not_started';
            if ($completedCount >= $totalActiveModules && $totalActiveModules > 0) {
                $status = 'completed';
            } elseif ($completedCount > 0 || $totalAttempts > 0) {
                $status = 'in_progress';
            }

            $user->metrics = (object) [
                'completed_count' => $completedCount,
                'total_attempts' => $totalAttempts,
                'correct_attempts' => $correctAttempts,
                'average_score' => $averageScore,
                'last_activity' => $lastActivity,
                'status' => $status,
            ];

            return $user;
        });

        // Filter client/collection jika modul atau status pengerjaan dipilih
        $users = $usersPaginator;

        return view('admin.progress.index', compact('users', 'allModules', 'totalActiveModules', 'search', 'moduleId', 'statusFilter'));
    }

    public function progressShow($id)
    {
        $user = User::where('role', 'user')->findOrFail($id);
        $allModules = Module::with('quizzes.options')->orderBy('order', 'asc')->get();
        $totalActiveModules = Module::where('is_active', true)->count();

        // Ambil riwayat attempt user dengan relasi
        $rawAttempts = $user->quizAttempts()
            ->with(['module', 'quiz', 'selectedOption'])
            ->latest()
            ->get();

        // Deduplikasi attempt untuk validasi single attempt per (module, quiz, type)
        $attempts = $rawAttempts
            ->sortByDesc('id')
            ->unique(function ($item) {
                return $item->module_id . '-' . $item->quiz_id . '-' . $item->type;
            })
            ->values();

        // Metrik umum berdasarkan seluruh jawaban unik/valid
        $totalAttempts = $attempts->count();
        $correctAttempts = $attempts->where('is_correct', true)->count();
        $averageScore = $totalAttempts > 0 ? (int) round(($correctAttempts / $totalAttempts) * 100) : 0;

        // Kelompokkan attempt per modul & per tipe (pre/post)
        $historyByModule = [];
        $completedCount = 0;

        foreach ($allModules as $module) {
            $modAttempts = $attempts->where('module_id', $module->id);
            $totalQuestions = $module->quizzes->count();

            $preAttempts = $modAttempts->where('type', 'pre');
            $postAttempts = $modAttempts->where('type', 'post');

            $preCorrect = $preAttempts->where('is_correct', true)->count();
            $postCorrect = $postAttempts->where('is_correct', true)->count();

            $preScore = $totalQuestions > 0 ? (int) round(($preCorrect / $totalQuestions) * 100) : 0;
            $postScore = $totalQuestions > 0 ? (int) round(($postCorrect / $totalQuestions) * 100) : 0;

            $status = $user->getModuleProgressStatus($module, $attempts);
            $isCompleted = ($status === 'completed');

            if ($isCompleted && $module->is_active) {
                $completedCount++;
            }

            $historyByModule[] = (object) [
                'module' => $module,
                'status' => $status,
                'is_completed' => $isCompleted,
                'total_questions' => $totalQuestions,
                'pre_attempts_count' => $preAttempts->count(),
                'pre_correct' => $preCorrect,
                'pre_score' => $preScore,
                'pre_date' => $preAttempts->first()?->created_at,
                'post_attempts_count' => $postAttempts->count(),
                'post_correct' => $postCorrect,
                'post_score' => $postScore,
                'post_date' => $postAttempts->first()?->created_at,
                'attempts' => $modAttempts,
            ];
        }

        return view('admin.progress.show', compact('user', 'allModules', 'totalActiveModules', 'completedCount', 'totalAttempts', 'correctAttempts', 'averageScore', 'historyByModule', 'attempts'));
    }

    // ================= EKSPOR EXCEL (.xlsx) =================

    /**
     * Ekspor 1: Rekap nilai per modul untuk seluruh anak (1 baris per anak)
     */
    public function progressExportRekap(Request $request)
    {
        $moduleId = $request->query('module_id');

        if (!$moduleId) {
            return back()->with('error', 'Silakan pilih modul pembelajaran terlebih dahulu untuk mengekspor rekap nilai per modul.');
        }

        $module = Module::with('quizzes')->findOrFail($moduleId);
        $totalQuestions = $module->quizzes->count();

        $meta = [
            ['Judul:', 'Rekap Nilai Per Modul'],
            ['Nama Modul:', $module->title],
            ['Tanggal Ekspor:', date('d-m-Y H:i')]
        ];

        $headers = [
            'No.',
            'Nama Anak',
            'Username/Email',
            'Rata-Rata Pre-Test',
            'Rata-Rata Post-Test',
            'Peningkatan (%)',
            'Status'
        ];

        $search = $request->query('search');
        $query = User::where('role', 'user')->with(['quizAttempts' => function ($q) use ($moduleId) {
            $q->where('module_id', $moduleId);
        }]);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%");
            });
        }

        $users = $query->get();

        $computedData = [];

        foreach ($users as $u) {
            $attempts = $u->validQuizAttempts()->where('module_id', $moduleId);
            $preAttempts = $attempts->where('type', 'pre');
            $postAttempts = $attempts->where('type', 'post');

            $hasPre = $preAttempts->isNotEmpty();
            $hasPost = $postAttempts->isNotEmpty();

            $preScore = null;
            $postScore = null;
            $diffPercent = null;
            $status = 'Belum Mengerjakan';

            // Hitung rata-rata berdasarkan soal modul ($totalQuestions)
            // Rata-rata = (total jawaban benar / total pertanyaan kuis modul) * 100
            if ($hasPre) {
                $preCorrect = $preAttempts->where('is_correct', true)->count();
                $preScore = $totalQuestions > 0 ? round(($preCorrect / $totalQuestions) * 100, 2) : 0.0;
            }

            if ($hasPost) {
                $postCorrect = $postAttempts->where('is_correct', true)->count();
                $postScore = $totalQuestions > 0 ? round(($postCorrect / $totalQuestions) * 100, 2) : 0.0;
            }

            if ($hasPre && $hasPost) {
                $status = 'Selesai';
                // Peningkatan dalam poin (skala 0-100), disimpan sebagai fraksi untuk format persen (+0.00%;-0.00%;0.00%)
                $diff = $postScore - $preScore;
                $diffPercent = round($diff / 100, 4);
            } elseif ($hasPre && !$hasPost) {
                $status = 'Belum Menyelesaikan Post-Test';
            } elseif (!$hasPre && $hasPost) {
                $status = 'Data Pre-Test Tidak Tersedia';
            } else {
                $status = 'Belum Mengerjakan';
            }

            $computedData[] = [
                'user' => $u,
                'pre_score' => $preScore,
                'post_score' => $postScore,
                'diff_percent' => $diffPercent,
                'status' => $status,
            ];
        }

        // Urutkan berdasarkan Rata-Rata Post-Test dari terendah ke tertinggi.
        // Anak yang belum memiliki nilai post-test ditaruh di paling bawah.
        usort($computedData, function ($a, $b) {
            $aPost = $a['post_score'];
            $bPost = $b['post_score'];

            if ($aPost === null && $bPost === null) {
                return strcmp($a['user']->name, $b['user']->name);
            }
            if ($aPost === null) {
                return 1; // a ke bawah
            }
            if ($bPost === null) {
                return -1; // b ke bawah
            }

            if ($aPost == $bPost) {
                return strcmp($a['user']->name, $b['user']->name);
            }

            return ($aPost < $bPost) ? -1 : 1;
        });

        $rows = [];
        $rowNum = 1;

        foreach ($computedData as $item) {
            $rows[] = [
                $rowNum++,
                $item['user']->name,
                $item['user']->email ?? $item['user']->username ?? '—',
                $item['pre_score'] !== null ? ['type' => 'number', 'value' => $item['pre_score']] : '-',
                $item['post_score'] !== null ? ['type' => 'number', 'value' => $item['post_score']] : '-',
                $item['diff_percent'] !== null ? ['type' => 'percent', 'value' => $item['diff_percent']] : '-',
                ['type' => 'text', 'value' => $item['status']]
            ];
        }

        // Sanitasi nama modul untuk filename
        $safeModuleTitle = preg_replace('/[^A-Za-z0-9_\-]/', '_', trim($module->title));
        $safeModuleTitle = trim(preg_replace('/_+/', '_', $safeModuleTitle), '_');
        if (empty($safeModuleTitle)) {
            $safeModuleTitle = 'Modul_' . $module->id;
        }

        $filename = 'Rekap_Nilai_Modul_' . $safeModuleTitle . '_' . date('Y-m-d') . '.xlsx';
        $xlsxBinary = \App\Services\SimpleXlsxExporter::createXlsx(
    'Rekap Nilai Modul',
    $headers,
    $rows
);

        return response($xlsxBinary, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    /**
     * Ekspor 2: Detail nilai untuk satu anak
     */
    public function progressExportDetail($id)
    {
        $user = User::where('role', 'user')->findOrFail($id);

        $allModules = Module::with('quizzes')->orderBy('order', 'asc')->get();

        // Ambil riwayat pengerjaan kuis milik user yang sudah dideduplikasi
        $rawAttempts = $user->quizAttempts()
            ->with(['module', 'quiz', 'selectedOption'])
            ->latest()
            ->get();

        $attempts = $rawAttempts
            ->sortByDesc('id')
            ->unique(function ($item) {
                return $item->module_id . '-' . $item->quiz_id . '-' . $item->type;
            })
            ->values();

        $meta = [
            ['Nama Anak:', $user->name],
            ['Username / Email:', $user->email ?? $user->username ?? '—'],
            ['Tanggal Ekspor:', date('d-m-Y H:i')]
        ];

        $headers = [
            'No.',
            'Nama Modul',
            'Nama Kuis / Evaluasi',
            'Jenis Evaluasi',
            'Jawaban Benar',
            'Jumlah Soal',
            'Nilai / Persentase',
            'Tanggal Pengerjaan'
        ];

        $rows = [];
        $rowNum = 1;

        if ($attempts->isEmpty()) {
            $rows[] = [
                1,
                'Belum ada riwayat pengerjaan kuis',
                '—',
                '—',
                0,
                0,
                'Belum ada nilai',
                '—'
            ];
        } else {
            // Urutkan berdasarkan modul dan jenis tes konsisten dengan halaman detail progres
            foreach ($allModules as $module) {
                $modAttempts = $attempts->where('module_id', $module->id);
                if ($modAttempts->isEmpty()) {
                    continue;
                }

                $totalQuestions = $module->quizzes->count();

                $preAttempts = $modAttempts->where('type', 'pre');
                if ($preAttempts->isNotEmpty()) {
                    $preCorrect = $preAttempts->where('is_correct', true)->count();
                    $preScore = $totalQuestions > 0 ? round(($preCorrect / $totalQuestions) * 100, 2) : 0;
                    $preDate = $preAttempts->first()?->created_at?->format('d-m-Y H:i') ?? '—';

                    $rows[] = [
                        $rowNum++,
                        $module->title,
                        'Kuis Pre-Test Modul ' . $module->order,
                        'Pre-Test',
                        $preCorrect,
                        $totalQuestions,
                        number_format($preScore, 2, ',', '') . '%',
                        $preDate
                    ];
                }

                $postAttempts = $modAttempts->where('type', 'post');
                if ($postAttempts->isNotEmpty()) {
                    $postCorrect = $postAttempts->where('is_correct', true)->count();
                    $postScore = $totalQuestions > 0 ? round(($postCorrect / $totalQuestions) * 100, 2) : 0;
                    $postDate = $postAttempts->first()?->created_at?->format('d-m-Y H:i') ?? '—';

                    $rows[] = [
                        $rowNum++,
                        $module->title,
                        'Kuis Post-Test Modul ' . $module->order,
                        'Post-Test',
                        $postCorrect,
                        $totalQuestions,
                        number_format($postScore, 2, ',', '') . '%',
                        $postDate
                    ];
                }
            }
        }

        // Sanitasi nama user untuk filename
        $safeUserName = preg_replace('/[^A-Za-z0-9_\-]/', '_', trim($user->name));
        $safeUserName = trim(preg_replace('/_+/', '_', $safeUserName), '_');
        if (empty($safeUserName)) {
            $safeUserName = 'User_' . $user->id;
        }

        $filename = 'Detail_Nilai_' . $safeUserName . '_' . date('Y-m-d') . '.xlsx';
        $xlsxBinary = SimpleXlsxExporter::createXlsx('Detail Nilai Anak', $headers, $rows, $meta);

        return response($xlsxBinary, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    // ================= PROFIL ADMIN =================

    /**
     * Tampilkan formulir ubah profil admin yang sedang login.
     */
    public function profile()
    {
        $user = auth()->user();
        return view('admin.profile', compact('user'));
    }

    /**
     * Simpan pembaruan profil admin (nama, username, email).
     */
    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username,' . $user->id],
            'email' => ['nullable', 'email', 'max:255', 'unique:users,email,' . $user->id],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username ini sudah digunakan oleh akun lain.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah digunakan oleh akun lain.',
        ]);

        $user->update([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'] ?? null,
        ]);

        return redirect()->route('admin.profile')->with('success', 'Profil admin berhasil diperbarui!');
    }
}
