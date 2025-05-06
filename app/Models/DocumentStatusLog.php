<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentStatusLog extends Model
{
    protected $table = 'document_status_logs';

    // Define the fillable columns
    protected $fillable = [
        'document_id',
        'status',
        'message',
        'created_by',
        'temp_id'
        // Add any other fillable columns here if necessary
    ];
}
