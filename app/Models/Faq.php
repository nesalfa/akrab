<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


/**
 * Model FAQ
 * Menyimpan pertanyaan umum + jawaban
 * Bisa per modul atau global (module_id nullable)
 * Lokasi: app/Models/Faq.php
 */
class Faq extends Model
{
    protected $table = 'faq';
    protected $fillable = ['module_id', 'question', 'answer', 'order'];

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    /**
     * Scope: Ambil FAQ global (tidak ada module_id)
     */
    public function scopeGlobal($query)
    {
        return $query->whereNull('module_id');
    }

    /**
     * Scope: Ambil FAQ per modul
     */
    public function scopeByModule($query, $moduleId)
    {
        return $query->where('module_id', $moduleId);
    }
}
