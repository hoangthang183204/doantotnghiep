<?php

namespace App\Services;

use App\Models\NgayLe;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LichLamViecService
{
    public static function cauHinh(): object
    {
        $row = DB::table('cau_hinh_lich_lam')->first();
        if ($row) {
            return $row;
        }

        return (object) [
            'gio_vao' => '08:00:00',
            'gio_ra' => '17:30:00',
            'gio_nghi_trua_bat_dau' => '12:00:00',
            'gio_nghi_trua_ket_thuc' => '13:30:00',
            'so_gio_chuan' => 8,
            'ngay_lam' => json_encode([1, 2, 3, 4, 5]),
            'ngay_cong_chuan' => 22,
        ];
    }

    public static function ngayLamTrongTuan(): array
    {
        $raw = self::cauHinh()->ngay_lam ?? '[1,2,3,4,5]';
        $days = is_string($raw) ? json_decode($raw, true) : (array) $raw;
        return array_map('intval', $days ?: [1, 2, 3, 4, 5]);
    }

    public static function laNgayLe(Carbon $ngay, bool $chiHuongLuong = false): bool
    {
        $les = NgayLe::query()->when($chiHuongLuong, fn ($q) => $q->where('co_huong_luong', true))->get();
        $md = $ngay->format('m-d');
        foreach ($les as $le) {
            if ($le->lap_lai_hang_nam && $le->ngay && $le->ngay->format('m-d') === $md) {
                return true;
            }
            if ($le->ngay && $le->ngay->toDateString() === $ngay->toDateString()) {
                return true;
            }
        }
        return false;
    }

    /** Số ngày lễ hưởng lương rơi vào ngày làm việc chuẩn trong tháng. */
    public static function soNgayLeHuongLuong(int $thang, int $nam): float
    {
        $start = Carbon::create($nam, $thang, 1)->startOfDay();
        $end = (clone $start)->endOfMonth();
        $ngayLam = self::ngayLamTrongTuan();
        $so = 0;
        for ($ngay = $start->copy(); $ngay->lte($end); $ngay->addDay()) {
            if (!in_array($ngay->dayOfWeekIso, $ngayLam, true)) {
                continue;
            }
            if (self::laNgayLe($ngay, true)) {
                $so++;
            }
        }
        return (float) $so;
    }
}
