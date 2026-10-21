<?php

namespace App\Services;

use App\Models\CaLamViec;
use App\Models\NgayLe;
use App\Models\PhongBan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DanhMucCache
{
    private static ?bool $coRedis = null;

    public static function kho()
    {
        if (self::$coRedis === null) {
            self::$coRedis = false;
            try {
                Cache::store('redis')->put('dm.ping', 1, 30);
                self::$coRedis = true;
            } catch (\Throwable $e) {
                self::$coRedis = false;
            }
        }

        return self::$coRedis
            ? Cache::store('redis')
            : Cache::store(config('cache.default', 'database'));
    }

    public static function cacCa()
    {
        return self::kho()->remember('dm.ca', 3600, function () {
            return CaLamViec::query()->orderBy('gio_bat_dau')->get();
        });
    }

    public static function ngayLe()
    {
        return self::kho()->remember('dm.ngay_le', 3600, function () {
            return NgayLe::query()->orderBy('ngay')->get();
        });
    }

    public static function phongBan()
    {
        return self::kho()->remember('dm.phong_ban', 3600, function () {
            return PhongBan::query()->orderBy('ten_phong_ban')->get(['id', 'ten_phong_ban', 'truong_phong_id', 'trang_thai']);
        });
    }

    public static function cauHinhLich(): object
    {
        return self::kho()->remember('dm.lich', 3600, function () {
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
        });
    }

    public static function xoa(): void
    {
        foreach (['dm.ca', 'dm.ngay_le', 'dm.phong_ban', 'dm.lich'] as $khoa) {
            try {
                self::kho()->forget($khoa);
            } catch (\Throwable $e) {
                // Cache hỏng không được chặn nghiệp vụ.
            }
        }
        self::$coRedis = null;
    }
}
