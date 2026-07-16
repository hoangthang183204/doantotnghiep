<?php
// app/Helpers/TinhLuongHelper.php

namespace App\Helpers;

class TinhLuongHelper
{
    /**
     * Giảm trừ gia cảnh (NQ 110/2025/UBTVQH15, áp dụng từ kỳ tính thuế 2026).
     * Bản thân: 15,5tr/tháng — Mỗi người phụ thuộc: 6,2tr/tháng.
     */
    public const GIAM_TRU_BAN_THAN = 15_500_000;
    public const GIAM_TRU_NGUOI_PHU_THUOC = 6_200_000;

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
     * Tính thuế TNCN theo luật 2026 (biểu 5 bậc, có giảm trừ người phụ thuộc).
     * @param float $tongLuong        - Tổng thu nhập chịu thuế
     * @param float $luongCoBan       - Lương cơ bản (để tính BH)
     * @param int   $soNguoiPhuThuoc  - Số người phụ thuộc (giảm trừ 6,2tr/người)
     * @return float
     */
    public static function tinhThueTncn($tongLuong, $luongCoBan, $soNguoiPhuThuoc = 0)
    {
        // 1. Tính bảo hiểm từ lương cơ bản
        $baoHiem = self::tinhBaoHiem($luongCoBan);
        $tongBaoHiem = $baoHiem['tong'];

        // 2. Giảm trừ gia cảnh = bản thân 15,5tr + 6,2tr × số người phụ thuộc
        $giamTruGiaCanh = self::GIAM_TRU_BAN_THAN + self::GIAM_TRU_NGUOI_PHU_THUOC * max(0, (int) $soNguoiPhuThuoc);

        // 3. Thu nhập tính thuế = Tổng thu nhập − Bảo hiểm − Giảm trừ gia cảnh
        $thuNhapTinhThue = max(0, $tongLuong - $tongBaoHiem - $giamTruGiaCanh);

        // 4. Tính thuế theo biểu lũy tiến 5 bậc (Luật Thuế TNCN 2025)
        $thue = 0;
        $bac = [
            ['tu' => 0, 'den' => 10000000, 'thue_suat' => 0.05],
            ['tu' => 10000000, 'den' => 30000000, 'thue_suat' => 0.10],
            ['tu' => 30000000, 'den' => 60000000, 'thue_suat' => 0.20],
            ['tu' => 60000000, 'den' => 100000000, 'thue_suat' => 0.30],
            ['tu' => 100000000, 'den' => PHP_INT_MAX, 'thue_suat' => 0.35],
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
        $khauTruKhac = 0,
        $soNguoiPhuThuoc = 0
    ) {
        // 1. Tính bảo hiểm từ lương cơ bản
        $baoHiem = self::tinhBaoHiem($luongCoBan);
        $tongBaoHiem = $baoHiem['tong'];

        // 2. Giảm trừ gia cảnh (bản thân + người phụ thuộc)
        $giamTruGiaCanh = self::GIAM_TRU_BAN_THAN + self::GIAM_TRU_NGUOI_PHU_THUOC * max(0, (int) $soNguoiPhuThuoc);

        // 3. Tính thuế từ tổng thu nhập
        $thueTncn = self::tinhThueTncn($tongLuong, $luongCoBan, $soNguoiPhuThuoc);

        // 4. Tổng khấu trừ
        $tongKhauTru = $tongBaoHiem + $thueTncn + $tienPhat + $khauTruKhac;

        // 5. Thực nhận
        $thucNhan = $tongLuong - $tongKhauTru;

        return [
            'bao_hiem' => $baoHiem,
            'tong_bao_hiem' => $tongBaoHiem,
            'thu_nhap_chiu_thue' => max(0, $tongLuong - $tongBaoHiem),
            'so_nguoi_phu_thuoc' => (int) $soNguoiPhuThuoc,
            'giam_tru_ban_than' => self::GIAM_TRU_BAN_THAN,
            'giam_tru_nguoi_phu_thuoc' => self::GIAM_TRU_NGUOI_PHU_THUOC * max(0, (int) $soNguoiPhuThuoc),
            'giam_tru_gia_canh' => $giamTruGiaCanh,
            'thu_nhap_tinh_thue' => max(0, $tongLuong - $tongBaoHiem - $giamTruGiaCanh),
            'thue_tncn' => $thueTncn,
            'tong_khau_tru' => $tongKhauTru,
            'thuc_nhan' => $thucNhan,
        ];
    }
}