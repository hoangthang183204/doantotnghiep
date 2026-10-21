<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckHrModule
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $path = $request->path();
        $laKeToan = \App\Support\VaiTroNghiepVu::laKeToan($user);
        $laHr = \App\Support\VaiTroNghiepVu::laHr($user);
        $laQuanTri = \App\Support\VaiTroNghiepVu::laQuanTri($user);

        $laThue = str_contains($path, 'bhxh-thue') || str_contains($path, 'quyet-toan');
        $laChot = str_contains($path, 'chot-du-lieu');
        if ($laThue) {
            abort_unless($laKeToan || \App\Support\VaiTroNghiepVu::laSuperAdmin($user) || ($laQuanTri && $user->hasPermission('salary.index')), 403, 'Chỉ kế toán cấu hình thuế và BHXH.');
            return $next($request);
        }
        if ($laChot) {
            abort_unless($laHr || $laQuanTri, 403, 'Chỉ HR chốt dữ liệu tháng.');
            return $next($request);
        }
        if (str_contains($path, 'nhat-ky')) {
            abort_unless($laQuanTri || $laHr, 403);
            return $next($request);
        }

        abort_unless($laHr || $laQuanTri, 403, 'Chức năng này thuộc HR.');

        return $next($request);
    }
}
