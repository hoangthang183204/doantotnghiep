<?php
// app/Http/Middleware/CheckTruongPhong.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PhongBan;

class CheckTruongPhong
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // =============================================
        // ⭐ KIỂM TRA CHÍNH XÁC TRƯỞNG PHÒNG
        // =============================================
        
        $isTruongPhong = false;
        $phongBanId = null;
        $phongBan = null;

        // ✅ CÁCH 1: Kiểm tra từ bảng phong_ban (truong_phong_id)
        $phongBan = PhongBan::where('truong_phong_id', $user->id)->first();
        if ($phongBan) {
            $isTruongPhong = true;
            $phongBanId = $phongBan->id;
        }

        // ✅ CÁCH 2: Kiểm tra từ chức vụ (chỉ khi có từ khóa "Trưởng phòng")
        if (!$isTruongPhong && $user->chucVu) {
            $chucVuTen = $user->chucVu->ten;
            $keywords = ['Trưởng Phòng', 'Trưởng phòng', 'Quản lý', 'Manager'];
            
            foreach ($keywords as $keyword) {
                if (str_contains($chucVuTen, $keyword)) {
                    $isTruongPhong = true;
                    $phongBanId = $user->phong_ban_id;
                    break;
                }
            }
        }

        // ✅ CÁCH 3: Kiểm tra từ vai trò (QUAN TRỌNG NHẤT)
        if (!$isTruongPhong) {
            $isTruongPhong = $user->vaiTros()->whereIn('name', ['truong_phong', 'quan_ly'])->exists();
            if ($isTruongPhong) {
                $phongBanId = $user->phong_ban_id;
            }
        }

        // ⛔ NẾU KHÔNG PHẢI TRƯỞNG PHÒNG -> CHẶN TRUY CẬP
        if (!$isTruongPhong) {
            abort(403, 'Bạn không có quyền truy cập trang quản lý phòng ban.');
        }

        // Lấy thông tin phòng ban
        $phongBan = $phongBanId ? PhongBan::find($phongBanId) : null;

        // Lưu vào request
        $request->merge([
            'is_truong_phong' => true,
            'phong_ban_id' => $phongBanId,
            'phong_ban' => $phongBan,
            'user_role' => 'truong_phong',
        ]);

        return $next($request);
    }
}