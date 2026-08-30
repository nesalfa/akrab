<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * PENTING soal hashing password:
     * Model ini SENGAJA TIDAK memakai cast bawaan Laravel 11
     * `'password' => 'hashed'`. Password di-hash secara EKSPLISIT lewat
     * `Hash::make()` di RegisteredUserController & UserSeeder.
     *
     * Kalau User.php kamu yang SEKARANG masih punya baris
     * `'password' => 'hashed'` di method casts(), HAPUS baris itu sebelum
     * pakai file ini — kalau tidak, password akan ke-hash DUA KALI dan
     * tidak ada satupun akun yang bisa login.
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'role',
        'completed_modules',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'completed_modules' => 'array',
            // 'password' => 'hashed',  <-- SENGAJA tidak diaktifkan, lihat catatan di atas.
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    public function quizAttempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }

    /**
     * Ambil koleksi quiz_attempts yang sudah dideduplikasi.
     * Mengambil 1 percobaan terbaru per kombinasi module_id + quiz_id + type.
     */
    public function validQuizAttempts()
    {
        return $this->quizAttempts
            ->sortByDesc('id')
            ->unique(function ($item) {
                return $item->module_id . '-' . $item->quiz_id . '-' . $item->type;
            })
            ->values();
    }

    /**
     * Hitung status modul pengerjaan anak: 'not_started', 'in_progress', atau 'completed'.
     * 'completed': Semua soal pre-test dan post-test modul telah dijawab lengkap (atau terdaftar di completed_modules).
     * 'in_progress': Sudah ada attempt kuis namun belum lengkap keduanya.
     * 'not_started': Belum ada attempt sama sekali.
     */
    public function getModuleProgressStatus($module, $validAttempts = null): string
    {
        if ($validAttempts === null) {
            $validAttempts = $this->validQuizAttempts();
        }

        $totalQuestions = $module->quizzes->count();
        if ($totalQuestions === 0) {
            return $this->hasCompletedModule($module->slug) ? 'completed' : 'not_started';
        }

        $modAttempts = $validAttempts->where('module_id', $module->id);
        $preCount = $modAttempts->where('type', 'pre')->count();
        $postCount = $modAttempts->where('type', 'post')->count();

        if ($this->hasCompletedModule($module->slug) || ($preCount >= $totalQuestions && $postCount >= $totalQuestions)) {
            return 'completed';
        }

        if ($preCount > 0 || $postCount > 0) {
            return 'in_progress';
        }

        return 'not_started';
    }

    /**
     * Cek apakah modul (berdasarkan slug atau id) sudah selesai.
     */
    public function hasCompletedModule(string $slug): bool
    {
        return in_array($slug, $this->completed_modules ?? [], true);
    }

    /**
     * Tandai satu modul selesai (idempotent — aman dipanggil berkali-kali
     * untuk slug yang sama, tidak akan duplikat).
     */
    public function markModuleCompleted(string $slug): void
    {
        $completed = $this->completed_modules ?? [];

        if (!in_array($slug, $completed, true)) {
            $completed[] = $slug;
            $this->update(['completed_modules' => $completed]);
        }
    }

    /**
     * Hitung jumlah modul aktif yang telah diselesaikan (completed) oleh anak.
     * Menggunakan getModuleProgressStatus() per modul aktif sebagai single source of truth.
     */
    public function completedModulesCount($activeModules = null, $validAttempts = null): int
    {
        if ($activeModules === null) {
            $activeModules = Module::with('quizzes')->where('is_active', true)->get();
        }

        if ($validAttempts === null) {
            $validAttempts = $this->validQuizAttempts();
        }

        $completedCount = 0;
        foreach ($activeModules as $mod) {
            if ($this->getModuleProgressStatus($mod, $validAttempts) === 'completed') {
                $completedCount++;
            }
        }

        return $completedCount;
    }
}