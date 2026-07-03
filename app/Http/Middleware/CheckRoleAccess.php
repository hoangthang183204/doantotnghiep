<?php
// app/Http/Middleware/CheckRoleAccess.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Tymon\JWTAuth\Facades\JWTAuth;

class CheckRoleAccess
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $currentRoute = $request->route()->getName();

        // Các route bỏ qua kiểm tra
        $ignoreRoutes = [
            'login',
            'login.submit',
            'logout',
            'admin.phan-quyen.index',
            'admin.phan-quyen.edit',
            'admin.phan-quyen.update'
        ];

        if (in_array($currentRoute, $ignoreRoutes) || str_contains($currentRoute, 'phan-quyen')) {
            return $next($request);
        }

        // Lấy token từ cookie
        $token = Cookie::get('access_token');

        if (!$token) {
            return redirect()->route('login');
        }

        try {
            JWTAuth::setToken($token);

            if (!$user = JWTAuth::authenticate()) {
                Cookie::queue(Cookie::forget('access_token'));
                return redirect()->route('login');
            }

            // ⭐ KHÔNG CÒN AUTO ADMIN NỮA
            // TẤT CẢ ĐỀU PHẢI CÓ PERMISSION

            // Nếu không có yêu cầu role cụ thể -> cho qua
            if (empty($roles)) {
                return $next($request);
            }

            // Kiểm tra role
            $userRoles = $user->vaiTros ?? collect();
            $userRoleNames = $userRoles->pluck('name')->toArray();
            $userRoleDisplayNames = $userRoles->pluck('ten_hien_thi')->toArray();

            $hasRole = false;
            foreach ($roles as $role) {
                if (in_array($role, $userRoleNames) || in_array($role, $userRoleDisplayNames)) {
                    $hasRole = true;
                    break;
                }
            }

            if (!$hasRole) {
                abort(403, 'Bạn không có quyền truy cập khu vực này!');
            }

            return $next($request);
        } catch (\Exception $e) {
            Cookie::queue(Cookie::forget('access_token'));
            return redirect()->route('login');
        }
    }
}
