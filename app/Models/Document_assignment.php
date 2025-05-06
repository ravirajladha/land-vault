<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document_assignment extends Model
{
    use HasFactory;
    protected $fillable = ['document_type', 'doc_id','otp', 'receiver_id','receiver_type', 'access_token', 'expires_at','created_by'];

    public function receiver()
    {
        return $this->belongsTo(Receiver::class, 'receiver_id');
    }

    public function receiverType()
    {
        return $this->belongsTo(Receiver_type::class, 'receiver_type');
    }

    public function documentType()
    {
        return $this->belongsTo(Master_doc_type::class, 'document_type');
    }

    public function document()
    {
        return $this->belongsTo(Master_doc_data::class, 'doc_id');
    }
    
}
