<?php
// app/Helpers/SalaryHelper.php

namespace App\Helpers;

use App\Models\NguoiDung;

class SalaryHelper
{
    /**
     * Lấy lương cơ bản của nhân viên
     */
    public static function getBaseSalary($userId, $default = 0)
    {
        $user = NguoiDung::find($userId);
        if (!$user) {
            return $default;
        }

        return $user->getLuongCoBanHienTai();
    }

    /**
     * Lấy lương theo giờ của nhân viên
     */
    public static function getHourlyRate($userId, $default = 0)
    {
        $user = NguoiDung::find($userId);
        if (!$user) {
            return $default;
        }

        // ⭐ SỬ DỤNG ACCESSOR
        return $user->luong_theo_gio;
    }

    /**
     * Tính lương tăng ca
     */
    public static function calculateOvertimeSalary($userId, $hours, $type = 'ngay_thuong')
    {
        // Tính từ lương cơ bản trực tiếp
        $baseSalary = self::getBaseSalary($userId);

        if ($baseSalary <= 0) {
            return 0;
        }

        // Tính lương theo giờ: lương cơ bản / (26 ngày * 8 giờ)
        $hourlyRate = round($baseSalary / (26 * 8), 0);

        $heSo = match ($type) {
            'ngay_thuong' => 1.5,
            'ngay_nghi' => 2.0,
            'le_tet' => 3.0,
            default => 1.5,
        };

        return round($hours * $hourlyRate * $heSo, 0);
    }

    /**
     * Đồng bộ lương từ các bảng khác vào nguoi_dung
     */
    public static function syncSalary($userId)
    {
        $user = NguoiDung::find($userId);
        if (!$user) {
            return false;
        }

        $luongCoBan = $user->getLuongCoBanHienTai();
        if ($luongCoBan > 0) {
            $user->luong_co_ban = $luongCoBan;
            $user->luong_theo_gio = $user->luong_theo_gio; // Tính tự động
            $user->save();
            return true;
        }

        return false;
    }
}
