<?php
// app/Http/Middleware/CheckEmployee.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CheckEmployee
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // Kiểm tra đã đăng nhập chưa
        if (!$user) {
            return redirect()->route('login');
        }

        // Log để debug
        Log::info('CheckEmployee middleware', [
            'user_id' => $user->id,
            'email' => $user->email,
        ]);

        // Kiểm tra user có phải admin không (dùng vai trò)
        $isAdmin = $user->vaiTros()->whereIn('name', ['admin', 'Super Admin', 'Admin'])->exists();

        // Nếu là admin, chuyển sang trang admin
        if ($isAdmin) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Bạn là admin, vui lòng sử dụng trang quản trị.');
        }

        // Kiểm tra tài khoản có bị khóa không
        if ($user->trang_thai != 1) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            return redirect()->route('login')
                ->with('error', 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ quản trị viên.');
        }

        return $next($request);
    }
}