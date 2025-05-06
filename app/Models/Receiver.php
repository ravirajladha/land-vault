<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class Receiver extends Model
{
    // If you want to specify the table associated with this model
    protected $table = 'receivers';

    // If you want to specify which attributes can be mass assignable
    protected $fillable = ['name', 'email', 'phone', 'city', 'receiver_type_id', 'created_by', 'status'];

    // Relationship with ReceiverType
    public function receiverType()
    {
        return $this->belongsTo(Receiver_type::class, 'receiver_type_id');
    }
    public function documentAssignments()
    {
        return $this->hasMany(Document_assignment::class, 'receiver_id');
    }
    
    // Add any additional relationships or functionality here...
}
