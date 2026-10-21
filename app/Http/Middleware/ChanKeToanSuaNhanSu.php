<?php

namespace App\Http\Middleware;

use App\Support\VaiTroNghiepVu;
use Closure;
use Illuminate\Http\Request;

class ChanKeToanSuaNhanSu
{
    public function handle(Request $request, Closure $next)
    {
        if (VaiTroNghiepVu::laKeToanThuan($request->user())) {
            abort(403, 'Kế toán chỉ được xem dữ liệu này, không được sửa giờ công, duyệt đơn hay tạo hồ sơ.');
        }

        return $next($request);
    }
}
