<?php
// app/Http/Controllers/Employee/ProfileController.php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\HoSoNguoiDung;
use App\Models\HopDongLaoDong;
use App\Models\LuongNhanVien;
use App\Models\PhuCap;
use App\Models\PhuCapNhanVien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Hiển thị trang xem hồ sơ cá nhân
     */
    public function show()
    {
        $user = Auth::user();
        $hoSo = $user->hoSo;

        // Lấy hợp đồng hiệu lực
        $hopDongHieuLuc = $hoSo?->hop_dong
            ?->where('trang_thai_hop_dong', 'hieu_luc')
            ?->first();

        // Lấy bảng lương gần nhất
        $luongGanNhat = LuongNhanVien::where('nguoi_dung_id', $user->id)
            ->orderBy('luong_nam', 'desc')
            ->orderBy('luong_thang', 'desc')
            ->first();

        // Tính toán lương
        $luongCoBanHienTai = $hopDongHieuLuc?->luong_co_ban ?? 0;

        // Tính phụ cấp
        $tongPhuCap = 0;
        if ($hopDongHieuLuc) {
            if (!empty($hopDongHieuLuc->phu_cap)) {
                $phuCapIds = is_string($hopDongHieuLuc->phu_cap)
                    ? json_decode($hopDongHieuLuc->phu_cap, true)
                    : $hopDongHieuLuc->phu_cap;

                if (is_array($phuCapIds) && count($phuCapIds) > 0) {
                    $tongPhuCap = PhuCap::whereIn('id', $phuCapIds)->sum('so_tien_mac_dinh');
                }
            }

            if ($tongPhuCap == 0) {
                $phuCapNhanVien = PhuCapNhanVien::where('nguoi_dung_id', $user->id)
                    ->where('trang_thai', 'hieu_luc')
                    ->where('ngay_hieu_luc', '<=', now())
                    ->where(function ($q) {
                        $q->whereNull('ngay_ket_thuc')->orWhere('ngay_ket_thuc', '>=', now());
                    })
                    ->sum('so_tien');
                $tongPhuCap = $phuCapNhanVien > 0 ? $phuCapNhanVien : 0;
            }
        }

        // Tăng ca
        $tienTangCa = $luongGanNhat?->tien_tang_ca ?? 0;
        $coTangCa = $tienTangCa > 0;

        // Tổng thu nhập
        $tongThuNhap = $luongCoBanHienTai + $tongPhuCap + $tienTangCa;

        // Bảo hiểm (10.5%)
        $luongDongBhxh = $hopDongHieuLuc?->luong_co_ban ?? 0;
        $bhxh = round($luongDongBhxh * 0.08, 0);
        $bhyt = round($luongDongBhxh * 0.015, 0);
        $bhtn = round($luongDongBhxh * 0.01, 0);
        $tongBaoHiem = $bhxh + $bhyt + $bhtn;

        // Giảm trừ gia cảnh
        $soNguoiPhuThuoc = $hoSo?->nguoiPhuThuoc?->count() ?? 0;
        $giamTruBanThan = 15500000;
        $giamTruGiaCanh = $giamTruBanThan + 6200000 * $soNguoiPhuThuoc;

        // Thuế TNCN
        $thuNhapChiuThue = max(0, $tongThuNhap - $tongBaoHiem);
        $thuNhapTinhThue = max(0, $thuNhapChiuThue - $giamTruGiaCanh);

        $thueTncn = 0;
        $remaining = $thuNhapTinhThue;
        $bac = [
            ['tu' => 0, 'den' => 10000000, 'thue_suat' => 0.05],
            ['tu' => 10000000, 'den' => 30000000, 'thue_suat' => 0.1],
            ['tu' => 30000000, 'den' => 60000000, 'thue_suat' => 0.2],
            ['tu' => 60000000, 'den' => 100000000, 'thue_suat' => 0.3],
            ['tu' => 100000000, 'den' => PHP_INT_MAX, 'thue_suat' => 0.35],
        ];
        foreach ($bac as $b) {
            if ($remaining <= 0) break;
            $khoang = min($remaining, $b['den'] - $b['tu']);
            $thueTncn += $khoang * $b['thue_suat'];
            $remaining -= $khoang;
        }
        $thueTncn = round($thueTncn, 0);

        $thucNhan = $tongThuNhap - $tongBaoHiem - $thueTncn;

        // Lấy chi tiết phụ cấp
        $phuCapChiTiets = collect();
        if ($hopDongHieuLuc && !empty($hopDongHieuLuc->phu_cap)) {
            $phuCapIds = is_string($hopDongHieuLuc->phu_cap)
                ? json_decode($hopDongHieuLuc->phu_cap, true)
                : $hopDongHieuLuc->phu_cap;
            if (is_array($phuCapIds) && count($phuCapIds) > 0) {
                $phuCapChiTiets = PhuCap::whereIn('id', $phuCapIds)->get();
            }
        }

        return view('employee.profile.show', compact(
            'user',
            'hoSo',
            'hopDongHieuLuc',
            'luongGanNhat',
            'luongCoBanHienTai',
            'tongPhuCap',
            'tienTangCa',
            'coTangCa',
            'tongThuNhap',
            'luongDongBhxh',
            'bhxh',
            'bhyt',
            'bhtn',
            'tongBaoHiem',
            'soNguoiPhuThuoc',
            'thuNhapChiuThue',
            'thueTncn',
            'thucNhan',
            'phuCapChiTiets'
        ));
    }

    /**
     * Trang chỉnh sửa hồ sơ (chuyển sang index hiện tại)
     */
    public function edit()
    {
        return redirect()->route('employee.ho-so.index');
    }
}