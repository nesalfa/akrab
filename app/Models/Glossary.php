<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model Glossary
 * Menyimpan istilah sulit + definisi per modul
 * Lokasi: app/Models/Glossary.php
 */
class Glossary extends Model
{
    protected $table = 'glossary';
    protected $fillable = ['module_id', 'term', 'definition', 'example', 'order'];

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}