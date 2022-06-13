<?php

namespace App\Http\Middleware;

use App\Exceptions\ReturnResponseException;
use App\Models\Permission\Permission;
use App\Models\User\Employee;
use App\Models\User\Student;
use Closure;
use Illuminate\Http\Request;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     * @throws ReturnResponseException
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        $routeName = $request->route()->getName();
        $permission = Permission::where('method_name', $routeName)->first();

        if (($user instanceof Employee || $user instanceof Student)
            && $user->is_admin
            && $permission !== null
            && ($permission->method_name === 'manage-users-visualization' || $user->userCard->hasPermission($permission->id))) {
            return $next($request);
        }

        throw new ReturnResponseException('Permission denied', 403);
    }
}
