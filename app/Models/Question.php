<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model Question (Tanya Ahli)
 * Menyimpan pertanyaan anonim dari remaja
 * 
 * PENTING: Tidak menyimpan data pribadi!
 * Status: pending, answered, archived
 * Lokasi: app/Models/Question.php
 */
class Question extends Model
{
    protected $table = 'questions';
    protected $fillable = ['module_id', 'question_text', 'answer_text', 'status', 'anonymous_id'];

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    /**
     * Scope: Filter pertanyaan yang belum dijawab
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope: Filter pertanyaan yang sudah dijawab
     */
    public function scopeAnswered($query)
    {
        return $query->where('status', 'answered');
    }

    /**
     * Method: Tandai pertanyaan sebagai sudah dijawab
     */
    public function markAsAnswered()
    {
        $this->status = 'answered';
        return $this->save();
    }

    /**
     * Method: Cek apakah pertanyaan sudah dijawab
     */
    public function hasAnswer()
    {
        return !is_null($this->answer_text) && $this->status === 'answered';
    }
}