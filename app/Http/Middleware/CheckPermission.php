<?php
// app/Http/Middleware/CheckPermission.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckPermission
{
    public function handle(Request $request, Closure $next, $permission)
    {
        if (!Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated'], 401);
            }
            return redirect()->route('login');
        }

        $user = Auth::user();

        // ⭐ THÊM: Bỏ qua kiểm tra cho route Phân quyền
        $currentRoute = $request->route()->getName();
        $ignoreRoutes = [
            'admin.phan-quyen.index',
            'admin.phan-quyen.edit',
            'admin.phan-quyen.update',
            'admin.phan-quyen.store'
        ];

        if (in_array($currentRoute, $ignoreRoutes)) {
            return $next($request);
        }

        // ============================================================
        // 2. KIỂM TRA QUYỀN CỤ THỂ
        // ============================================================
        if (!$user->hasPermission($permission)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Bạn không có quyền truy cập chức năng này!',
                    'permission' => $permission
                ], 403);
            }

            abort(403, 'Bạn không có quyền truy cập chức năng này!');
        }

        return $next($request);
    }
}
