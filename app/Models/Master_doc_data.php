<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Master_doc_data extends Model
{
    use HasFactory;

    // Use guarded instead of fillable
    protected $guarded = [];
    public function documentType()
    {
        return $this->belongsTo('App\Models\Master_doc_type', 'document_type');
    // return $this->belongsTo(Master_doc_data::class, 'document_type');

    }
}

