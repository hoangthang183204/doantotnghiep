<?php
// app/Providers/AppServiceProvider.php

namespace App\Providers;

use App\Models\CaLamViec;
use App\Models\ChamCong;
use App\Models\HoSoNguoiDung;
use App\Models\LuongNhanVien;
use App\Models\NgayLe;
use App\Models\HoSo;
use App\Models\PhongBan;
use App\Observers\GhiNhatKyThayDoi;
use App\Services\DanhMucCache;
use App\Services\NotificationService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // ⭐ Đăng ký NotificationService
        $this->app->singleton(NotificationService::class, function ($app) {
            return new NotificationService();
        });
    }

    public function boot(): void
    {
        $nhatKy = GhiNhatKyThayDoi::class;
        LuongNhanVien::observe($nhatKy);
        ChamCong::observe($nhatKy);
        HoSoNguoiDung::observe($nhatKy);
        HoSo::observe($nhatKy);

        $xoaCache = fn () => DanhMucCache::xoa();
        foreach ([CaLamViec::class, NgayLe::class, PhongBan::class] as $model) {
            $model::saved($xoaCache);
            $model::deleted($xoaCache);
        }
    }
}