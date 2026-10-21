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

        $roleOk = $user->vaiTros()->whereIn('name', ['admin', 'Super Admin', 'Admin', 'hr', 'ke_toan'])->exists();
        $permOk = $user->hasPermission('salary.index')
            || $user->hasPermission('hoso.index')
            || $user->hasPermission('setting.general');

        if (!$roleOk && !$permOk) {
            abort(403, 'Bạn không có quyền dùng chức năng này.');
        }

        return $next($request);
    }
}
