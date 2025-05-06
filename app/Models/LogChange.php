<?php 

// app/Models/LogChange.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogChange extends Model
{
    protected $casts = [
        'changes' => 'array',
    ];

    protected $fillable = [
        'user_id',
        'model_type',
        'model_id',
        'changes',
    ];

    // Relationship to user who made the change
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

