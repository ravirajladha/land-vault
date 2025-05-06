<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compliance extends Model
{
    use HasFactory;

    protected $fillable = ['document_type', 'doc_id', 'name','due_date', 'is_recurring','recurrence_interval','created_by'];

    protected $casts = [
        'due_date' => 'datetime',
    ];
    public function documentType()
    {
        return $this->belongsTo(Master_doc_type::class, 'document_type');
    }

    public function document()
    {
        return $this->belongsTo(Master_doc_data::class, 'doc_id');
    }
    
}