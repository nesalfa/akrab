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
     * Cek apakah modul (berdasarkan slug) sudah pernah diselesaikan
     * (Post-Test-nya sudah disubmit) oleh user ini.
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

    public function completedModulesCount(): int
    {
        return count($this->completed_modules ?? []);
    }
}