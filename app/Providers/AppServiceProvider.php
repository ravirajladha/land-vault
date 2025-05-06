<?php

namespace App\Providers;

use Blade;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Services\DocumentTableService;
use Illuminate\Support\ServiceProvider;
use App\Models\User;
use App\Models\Set;
use App\Observers\GlobalModelObserver;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(DocumentTableService::class, function ($app) {
            // return new instance of service
            return new DocumentTableService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {

        // Inside AppServiceProvider's boot method
        //Get all the post, update and delete through globalobservice from all the models
        $namespace = 'App\\Models'; // Define the namespace where your models are located
        $path = app_path('Models'); // Define the path to your models

        $files = \File::allFiles($path);
        foreach ($files as $file) {
            $class = $namespace . '\\' . $file->getBasename('.php');
            if (is_subclass_of($class, 'Illuminate\Database\Eloquent\Model')) {
                $class::observe(GlobalModelObserver::class);
            }
        }


        View::composer('*', function ($view) {
            $user = Auth::user();
            // Pass the user to the view
            $view->with('user', $user);

            if ($user) {
                // Assuming your User model has a permissions() relationship defined
                $permissions = $user->permissions()->pluck('display_name'); // Use 'name' if you want to use the permission names
                $view->with('permissions', $permissions);
            } else {
                // Make sure to always pass permissions, even if it's an empty collection
                $view->with('permissions', collect());
            }
        });
        // Define a Blade directive for generating permission checkboxes

    }
}
