<?php

namespace App\Observers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GlobalModelObserver
{
    public function created(Model $model)
    {
        // Check if the operation is not a seeder or migration and get the user ID
        $userId = Auth::id() ?? 1; // Default to 1 if no user is authenticated (for seeder or migration)
        
        if ($userId) {
            $attributes = $model->getAttributes();
            $changes = !empty($attributes) ? json_encode($attributes) : null;

            DB::table('log_changes')->insert([
                'user_id' => $userId,
                'model_id' => $model->id,
                'model_type' => get_class($model),
                'action' => 'create',
                'changes' => $changes,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function updated(Model $model)
    {
        if (app()->runningInConsole()) {
            // Do not log if running in console (e.g., migrations, seedings)
            return;
        }
        
        $userId = Auth::id();
        if ($userId) {
            // Get only the changed attributes
            $changes = $model->getChanges();
            $changesJson = !empty($changes) ? json_encode($changes) : null;
            
            // Get the original attributes before the changes
            $original = $model->getOriginal();
            $originalJson = !empty($original) ? json_encode($original) : null;
            
            DB::table('log_changes')->insert([
                'user_id' => $userId,
                'model_id' => $model->id,
                'model_type' => get_class($model),
                'action' => 'update',
                'changes' => $changesJson,
                'original_values' => $originalJson,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function deleted(Model $model)
    {
        $userId = Auth::id();
        if ($userId) {
            $original = $model->getAttributes();
            $originalJson = !empty($original) ? json_encode($original) : null;

            DB::table('log_changes')->insert([
                'user_id' => $userId,
                'model_id' => $model->id,
                'model_type' => get_class($model),
                'action' => 'delete',
                'changes' => null, // No changes to record for delete actions
                'original_values' => $originalJson, // Store the original values before deletion
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
