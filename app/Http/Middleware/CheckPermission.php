<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckPermission
{
    public function handle($request, Closure $next, $permission)
    {
        // TẠM THỜI BỎ QUA KIỂM TRA QUYỀN
        return $next($request);
        
        // Code cũ comment lại
        /*
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        $user = Auth::user();
        
        $userPermissions = $user->vaiTros->flatMap(function($role) {
            return $role->quyens->pluck('name');
        })->unique();
        
        if (!$userPermissions->contains($permission)) {
            abort(403, 'Bạn không có quyền truy cập chức năng này!');
        }
        
        return $next($request);
        */
    }
}