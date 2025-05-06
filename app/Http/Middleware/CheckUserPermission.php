<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckUserPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $permissionName  The name of the permission to check.
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect('login');
        }

        // Get the current route name
        $routeName = $request->route()->getName();

        // Check if the route name is present in the permissions table
        $routeExistsInPermissions = DB::table('permissions')
            ->where('name', $routeName)
            ->exists();

        // If the route does not exist in the permissions table, allow access
        if (!$routeExistsInPermissions) {
            return $next($request);
        }

        // If the route exists, check if the user has the permission
        $hasPermission = DB::table('user_has_permissions')
            ->join('permissions', 'permissions.display_name', '=', 'user_has_permissions.permission_display_name')
            ->where('user_has_permissions.user_id', $user->id)
            ->where('permissions.name', $routeName)
            ->exists();

        // Allow access if the user is an admin or has the required permission
        if ($user->type === 'admin' || $hasPermission) {
            return $next($request);
        }

        // Otherwise, the user does not have permission
        if ($user->type !== 'admin' && !$hasPermission) {
            return redirect()->route('error-403')->with('error', 'You do not have permission to access this page.');
        }
    // Route::view('/error-page-403', 'errors.custom')->name('error.403');

        abort(403, 'Unauthorized access');
    }
}
?>