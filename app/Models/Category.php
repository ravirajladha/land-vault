<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'status'];

     public function subcategories()
    {
        return $this->hasMany(Subcategory::class);
    }
    // In Item.php (Model)
public function category() {
    return $this->belongsTo(Category::class, 'category_id');
}
}