<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserStatus
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            // Kiểm tra trạng thái tài khoản
            if ($user->trang_thai != 1) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                
                // Xóa cookie token nếu có
                if ($request->hasCookie('access_token')) {
                    \Cookie::forget('access_token');
                }
                
                return redirect()->route('login')
                    ->withErrors([
                        'email' => 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ quản trị viên.'
                    ]);
            }
        }

        return $next($request);
    }
}