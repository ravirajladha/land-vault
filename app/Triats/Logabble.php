<?php 

// app/Traits/Loggable.php

namespace App\Traits;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

trait Loggable
{
    // public static function bootLoggable()
    // {
    //     static::updating(function ($model) {
    //         $original = $model->getOriginal();
    //         $changes = $model->getDirty();
    //         $changes = collect($changes)->reject(function ($value, $key) {
    //             return in_array($key, ['updated_at']); // Exclude timestamps or other fields
    //         });

    //         if ($changes->isNotEmpty()) {
    //             LogChange::create([
    //                 'user_id' => Auth::id(),
    //                 'model_type' => get_class($model),
    //                 'model_id' => $model->id,
    //                 'changes' => $changes->toJson(),
    //             ]);
    //         }
    //     });
    // }
}


