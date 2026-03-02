<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Symfony\Component\HttpFoundation\Response;

class CheckPermissionMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Super Admin bypass
        if ($user->hasRole('super admin')) {
            return $next($request);
        }

        $routeName = $request->route()?->getName();

        if (!$routeName) {
            return $next($request);
        }

        $map = config('permission_map');

        if (!isset($map[$routeName])) {
            return $next($request); // map এ না থাকলে allow
        }

        $permissionConfig = $map[$routeName];

        /*
        |--------------------------------------------------------------------------
        | Dynamic Order Status Permission Check
        |--------------------------------------------------------------------------
        */

        if (is_array($permissionConfig) && ($permissionConfig['type'] ?? null) === 'order_status') {

            $status = $request->query('status');

            // default permission
            $permission = $status
                ? 'order_' . $status
                : 'order_all';

            if ($user->can($permission)) {
                return $next($request);
            }

            return redirect()->back()->with('error', 'No permission for this order status.');
        }

        /*
        |--------------------------------------------------------------------------
        | Static Permission Check
        |--------------------------------------------------------------------------
        */

        if ($user->can($permissionConfig)) {
            return $next($request);
        }

        return to_route('admin.dashboard.index')->with('error', 'You do not have permission.');
    }
}
