<?php
// app/Helpers/TinhLuongHelper.php

namespace App\Helpers;

class TinhLuongHelper
{
    /**
     * Tính các khoản khấu trừ bảo hiểm
     * @param float $luongCoBan - Lương cơ bản đóng BHXH
     * @return array
     */
    public static function tinhBaoHiem($luongCoBan)
    {
        return [
            'bhxh' => round($luongCoBan * 0.08, 0),   // 8%
            'bhyt' => round($luongCoBan * 0.015, 0),  // 1.5%
            'bhtn' => round($luongCoBan * 0.01, 0),   // 1%
            'tong' => round($luongCoBan * 0.105, 0),  // 10.5%
        ];
    }

    /**
     * Tính thuế TNCN (KHÔNG giảm trừ người phụ thuộc)
     * @param float $tongLuong - Tổng thu nhập
     * @param float $luongCoBan - Lương cơ bản (để tính BH)
     * @return float
     */
    public static function tinhThueTncn($tongLuong, $luongCoBan)
    {
        // 1. Tính bảo hiểm từ lương cơ bản
        $baoHiem = self::tinhBaoHiem($luongCoBan);
        $tongBaoHiem = $baoHiem['tong'];
        
        // 2. Thu nhập chịu thuế = Tổng thu nhập - Bảo hiểm
        $thuNhapChiuThue = max(0, $tongLuong - $tongBaoHiem);
        
        // 3. Giảm trừ bản thân 11,000,000
        $giamTruBanThan = 11000000;
        $thuNhapTinhThue = max(0, $thuNhapChiuThue - $giamTruBanThan);
        
        // 4. Tính thuế theo biểu lũy tiến
        $thue = 0;
        $bac = [
            ['tu' => 0, 'den' => 5000000, 'thue_suat' => 0.05],
            ['tu' => 5000000, 'den' => 10000000, 'thue_suat' => 0.10],
            ['tu' => 10000000, 'den' => 18000000, 'thue_suat' => 0.15],
            ['tu' => 18000000, 'den' => 32000000, 'thue_suat' => 0.20],
            ['tu' => 32000000, 'den' => 52000000, 'thue_suat' => 0.25],
            ['tu' => 52000000, 'den' => 80000000, 'thue_suat' => 0.30],
            ['tu' => 80000000, 'den' => PHP_INT_MAX, 'thue_suat' => 0.35],
        ];

        $remaining = $thuNhapTinhThue;
        foreach ($bac as $b) {
            if ($remaining <= 0) break;
            $khoang = min($remaining, $b['den'] - $b['tu']);
            $thue += $khoang * $b['thue_suat'];
            $remaining -= $khoang;
        }

        return round($thue, 0);
    }

    /**
     * Tính lương thực nhận đầy đủ
     * @param float $luongCoBan - Lương cơ bản (tính BH)
     * @param float $tongLuong - Tổng thu nhập (tính thuế)
     * @param float $tienPhat - Tiền phạt
     * @param float $khauTruKhac - Các khoản khấu trừ khác
     * @return array
     */
    public static function tinhLuongThucNhan(
        $luongCoBan,
        $tongLuong,
        $tienPhat = 0,
        $khauTruKhac = 0
    ) {
        // 1. Tính bảo hiểm từ lương cơ bản
        $baoHiem = self::tinhBaoHiem($luongCoBan);
        $tongBaoHiem = $baoHiem['tong'];
        
        // 2. Tính thuế từ tổng thu nhập
        $thueTncn = self::tinhThueTncn($tongLuong, $luongCoBan);
        
        // 3. Tổng khấu trừ
        $tongKhauTru = $tongBaoHiem + $thueTncn + $tienPhat + $khauTruKhac;
        
        // 4. Thực nhận
        $thucNhan = $tongLuong - $tongKhauTru;
        
        return [
            'bao_hiem' => $baoHiem,
            'tong_bao_hiem' => $tongBaoHiem,
            'thu_nhap_chiu_thue' => max(0, $tongLuong - $tongBaoHiem),
            'giam_tru_ban_than' => 11000000,
            'thu_nhap_tinh_thue' => max(0, $tongLuong - $tongBaoHiem - 11000000),
            'thue_tncn' => $thueTncn,
            'tong_khau_tru' => $tongKhauTru,
            'thuc_nhan' => $thucNhan,
        ];
    }
}