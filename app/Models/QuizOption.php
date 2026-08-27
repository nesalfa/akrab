<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizOption extends Model
{
    protected $table = 'quiz_options';
    protected $fillable = ['quiz_id', 'label', 'text', 'is_correct', 'order'];
}

