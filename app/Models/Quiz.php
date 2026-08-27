<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model Quiz
 * 
 * Representasi tabel 'quizzes'
 * Menyimpan pertanyaan kuis per modul
 * 
 * Type: benar_salah, pilihan_ganda, skenario
 * 
 * Lokasi file: app/Models/Quiz.php
 */
class Quiz extends Model
{
    protected $table = 'quizzes';
    protected $fillable = ['module_id', 'question', 'type', 'order'];

    /**
     * Relasi: Banyak kuis dimiliki oleh satu module
     */
    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    /**
     * Relasi: Satu kuis memiliki banyak pilihan jawaban
     */
    public function options()
    {
        return $this->hasMany(QuizOption::class, 'quiz_id', 'id')->orderBy('order', 'asc');
    }

    /**
     * Scope: Filter kuis berdasarkan tipe
     * Contoh: Quiz::byType('benar_salah')->get();
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Method: Ambil jawaban yang benar
     */
    public function getCorrectAnswer()
    {
        return $this->options()->where('is_correct', true)->first();
    }

    /**
     * Method: Cek apakah kuis ini tipe benar-salah
     */
    public function isTrueFalse()
    {
        return $this->type === 'benar_salah';
    }

    /**
     * Method: Cek apakah kuis ini tipe pilihan ganda
     */
    public function isMultipleChoice()
    {
        return $this->type === 'pilihan_ganda';
    }
}

/**
 * Model QuizOption
 * 
 * Representasi tabel 'quiz_options'
 * Menyimpan pilihan jawaban untuk setiap kuis
 * 
 * Lokasi file: app/Models/QuizOption.php
 */
class QuizOption extends Model
{
    protected $table = 'quiz_options';
    protected $fillable = ['quiz_id', 'label', 'text', 'is_correct', 'order'];

    /**
     * Relasi: Banyak pilihan jawaban dimiliki oleh satu kuis
     */
    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    /**
     * Method: Cek apakah pilihan ini adalah jawaban yang benar
     */
    public function isCorrect()
    {
        return $this->is_correct === true;
    }
}
