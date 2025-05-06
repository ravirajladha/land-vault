<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;

use Illuminate\Support\Facades\Log;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Arr;

use Illuminate\Support\Facades\DB;
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'type',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // User model
    public function permissions()
    {
        return $this->belongsToMany(
            Permission::class,
            'user_has_permissions', // Pivot table
            'user_id', // Foreign key on the pivot table for the user
            'permission_display_name', // Foreign key on the pivot table for the permission
            'id', // Local key on the users table
            'display_name'

        );
    }

    public function hasPermission($permissionName)
    {
        if ($this->type === 'admin') {
            return true;
        }

        return $this->permissions()
            ->where('display_name', $permissionName)
            ->exists();
    }



    protected static function boot()
    {
        parent::boot();
    
        static::creating(function ($model) {
            // Log the creation of a new record
            DB::table('log_changes')->insert([
                'user_id' => auth()->id(),
                'model_id' => 1,
                'model_type' => get_class($model),
                'action' => 'create',
                'changes' => json_encode($model->getAttributes()),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });
    
        static::updating(function ($model) {
            $original = $model->getOriginal();
            $changes = Arr::except($model->getDirty(), ['updated_at']);
    
            // Log the updating of a record
            DB::table('log_changes')->insert([
                'user_id' => auth()->id(),
                'model_id' => $model->id,
                'model_type' => get_class($model),
                'action' => 'update',
                'changes' => json_encode($changes),
                
                'original_values' => json_encode($original),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });
    
        static::deleting(function ($model) {
            // Log the deletion of a record
            DB::table('log_changes')->insert([
                'user_id' => auth()->id(),
                'model_id' => $model->id,
                'model_type' => get_class($model),
                'action' => 'delete',
                'original_values' => json_encode($model->getAttributes()),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });
    }
}
