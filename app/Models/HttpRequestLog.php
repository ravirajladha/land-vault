<?php 

// app/Models/LogChange.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HttpRequestLog extends Model
{
   
    protected $fillable = [
        'user_id',
        'ip_address',
        'user_agent',
        'method',
        'url',
    ];

    // Relationship to user who made the change
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

