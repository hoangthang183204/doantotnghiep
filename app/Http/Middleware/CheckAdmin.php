<?php
// app/Http/Middleware/CheckAdmin.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckAdmin
{
    public function handle(Request $request, Closure $next)
    {
        // ===== TẠM THỜI BỎ QUA KIỂM TRA =====
        return $next($request);
        
        // CODE CŨ COMMENT LẠI
        /*
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        $isAdmin = $user->vaiTros()->whereIn('name', ['admin', 'Super Admin', 'Admin'])->exists();

        if (!$isAdmin) {
            abort(403, 'Bạn không có quyền truy cập trang quản trị.');
        }

        return $next($request);
        */
    }
}