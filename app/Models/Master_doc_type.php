<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Master_doc_type extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function masterDocDatas()
    {
        return $this->hasMany('App\Models\Master_doc_data', 'document_type');

    }

}
