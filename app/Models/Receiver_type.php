<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receiver_type extends Model
{
    use HasFactory;
    public function receivers()
    {
        return $this->hasMany(Receiver::class, 'receiver_type_id');
    }
}
