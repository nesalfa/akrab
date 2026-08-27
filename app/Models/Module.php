<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model Module
 * 
 * Representasi tabel 'modules' dalam aplikasi
 * Setiap module adalah 1 modul pembelajaran (Mengenal Tubuhku, Pubertas, Menstruasi, dll)
 * 
 * Lokasi file: app/Models/Module.php
 */
class Module extends Model
{
    protected $table = 'modules';
    protected $fillable = ['slug', 'title', 'description', 'section', 'order', 'is_active'];

    /**
     * Relasi: Satu module memiliki banyak module_content
     * Contoh: Module "Pubertas" memiliki video, teks, infografis, glosarium, dll
     */
    public function moduleContent()
    {
        return $this->hasMany(ModuleContent::class);
    }

    /**
     * Relasi: Satu module memiliki banyak quizzes
     * Contoh: Module "Pubertas" memiliki 5 pertanyaan kuis
     */
    public function quizzes()
    {
        return $this->hasMany(Quiz::class, 'module_id', 'id');
    }

    /**
     * Relasi: Satu module memiliki banyak glossary items
     * Contoh: Module "Pubertas" memiliki istilah seperti "Hormon", "Testis", dll
     */
    public function glossary()
    {
        return $this->hasMany(Glossary::class);
    }

    /**
     * Relasi: Satu module memiliki banyak FAQ
     */
    public function faq()
    {
        return $this->hasMany(Faq::class);
    }

    /**
     * Relasi: Satu module memiliki banyak questions (Tanya Ahli)
     */
    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    /**
     * Helper method: Ambil semua konten berdasarkan type
     * Contoh: $module->getContentByType('video_isyarat')
     */
    public function getContentByType($type)
    {
        return $this->moduleContent()->where('type', $type)->orderBy('order')->get();
    }

    /**
     * Helper method: Ambil semua konten dalam urutan tampilan
     */
    public function getOrderedContent()
    {
        return $this->moduleContent()->orderBy('order')->get();
    }

    /**
     * Helper method: Ambil semua kuis beserta optionnya
     */
    public function getQuizzesWithOptions()
    {
        return $this->quizzes()->with('options')->orderBy('order')->get();
    }
}
