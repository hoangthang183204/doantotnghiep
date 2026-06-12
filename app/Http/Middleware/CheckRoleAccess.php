<?php

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
        
        // ========== THÊM CÁC ROUTE PHÂN QUYỀN VÀO DANH SÁCH BỎ QUA ==========
        $ignoreRoutes = [
            'login', 'login.submit', 'logout',
            'admin.phan-quyen.index', 'admin.phan-quyen.edit', 'admin.phan-quyen.update'
        ];
        
        // Bỏ qua nếu route chứa 'phan-quyen'
        if (in_array($currentRoute, $ignoreRoutes) || str_contains($currentRoute, 'phan-quyen')) {
            return $next($request);
        }
        // ====================================================================
        
        // Lấy token từ cookie
        $token = Cookie::get('access_token');
        
        // Nếu không có token, redirect về login
        if (!$token) {
            return redirect()->route('login');
        }
        
        try {
            // Set token cho JWT
            JWTAuth::setToken($token);
            
            // Kiểm tra token hợp lệ
            if (!$user = JWTAuth::authenticate()) {
                Cookie::queue(Cookie::forget('access_token'));
                return redirect()->route('login');
            }
            
            // Lấy vai trò của user
            $userRoles = $user->vaiTros ?? collect();
            $userRoleNames = $userRoles->pluck('name')->toArray();
            $userRoleDisplayNames = $userRoles->pluck('ten_hien_thi')->toArray();
            
            // Nếu không có yêu cầu role cụ thể -> cho qua
            if (empty($roles)) {
                return $next($request);
            }
            
            // Kiểm tra role
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