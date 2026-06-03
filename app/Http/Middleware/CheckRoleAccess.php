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
                // Token không hợp lệ, xóa cookie và redirect
                Cookie::queue(Cookie::forget('access_token'));
                return redirect()->route('login');
            }
            
            // Kiểm tra role nếu có yêu cầu
            if (!empty($roles)) {
                $userRole = $user->vai_tro->ten_hien_thi ?? 'Nhân viên';
                if (!in_array($userRole, $roles)) {
                    abort(403, 'Bạn không có quyền truy cập');
                }
            }
            
            return $next($request);
            
        } catch (\Exception $e) {
            // Token lỗi, xóa cookie
            Cookie::queue(Cookie::forget('access_token'));
            return redirect()->route('login');
        }
    }
}