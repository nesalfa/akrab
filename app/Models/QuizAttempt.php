<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model QuizAttempt
 *
 * Satu baris = satu jawaban kuis (Pre-Test atau Post-Test) dari satu
 * akun. Sebelumnya dilacak lewat `respondent_token` (session ID anonim);
 * sekarang lewat `user_id` sungguhan karena halaman modul mewajibkan
 * login. Lihat migration `2024_08_replace_respondent_token_with_user_id_in_quiz_attempts.php`.
 */
class QuizAttempt extends Model
{
    protected $table = 'quiz_attempts';

    protected $fillable = [
        'module_id',
        'user_id',
        'quiz_id',
        'selected_option_id',
        'is_correct',
        'type',
    ];

    protected $casts = [
        'is_correct' => 'boolean',
    ];

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function selectedOption()
    {
        return $this->belongsTo(QuizOption::class, 'selected_option_id');
    }

    public function scopePreTest($query)
    {
        return $query->where('type', 'pre');
    }

    public function scopePostTest($query)
    {
        return $query->where('type', 'post');
    }
}