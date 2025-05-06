<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class Advocate extends Model
{
    // If you want to specify the table associated with this model
    protected $table = 'advocates';

    public function documentAssignments()
    {
        return $this->hasMany(Advocate_documents::class, 'advocate_id');
    }
    
    
}
