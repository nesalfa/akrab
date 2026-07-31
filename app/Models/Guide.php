<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model Guide
 * Menyimpan panduan untuk guru, orang tua, tenaga kesehatan
 * Role: guru, orang_tua, nakes
 * Lokasi: app/Models/Guide.php
 */
class Guide extends Model
{
    protected $table = 'guides';
    protected $fillable = ['role', 'title', 'content', 'order'];

    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    /**
     * Method: Cek apakah guide ini untuk guru
     */
    public function isForTeacher()
    {
        return $this->role === 'guru';
    }

    /**
     * Method: Cek apakah guide ini untuk orang tua
     */
    public function isForParent()
    {
        return $this->role === 'orang_tua';
    }

    /**
     * Method: Cek apakah guide ini untuk tenaga kesehatan
     */
    public function isForHealthcare()
    {
        return $this->role === 'nakes';
    }
}