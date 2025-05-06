<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class Document_transaction extends Model
{
  
    protected $table = 'document_transactions';
    protected $fillable = [
        'doc_id',
        'created_by',
        'transaction_type',
        'notes',
    ];
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
