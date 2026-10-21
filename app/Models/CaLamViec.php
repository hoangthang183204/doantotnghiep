<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaLamViec extends Model
{
    protected $table = 'ca_lam_viec';

    protected $fillable = [
        'ten', 'ma', 'gio_bat_dau', 'gio_ket_thuc',
        'so_gio_lam_viec', 'gio_bat_dau_tang_ca',
        'so_phut_cho_phep_di_tre', 'so_phut_cho_phep_ve_som',
        'is_default', 'trang_thai', 'loai', 'mo_ta'
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'trang_thai' => 'boolean'
    ];

    public function chamCongs()
    {
        return $this->hasMany(ChamCong::class);
    }

    /**
     * Lấy ca mặc định
     */
    public static function getDefault()
    {
        return \App\Services\DanhMucCache::cacCa()->first(fn ($ca) => (bool) $ca->is_default);
    }

    /**
     * Lấy ca sáng
     */
    public static function getSang()
    {
        return \App\Services\DanhMucCache::cacCa()->firstWhere('ma', 'SANG');
    }

    /**
     * Lấy ca chiều
     */
    public static function getChieu()
    {
        return \App\Services\DanhMucCache::cacCa()->firstWhere('ma', 'CHIEU');
    }

    /**
     * Format giờ bắt đầu
     */
    public function getGioBatDauFormatAttribute()
    {
        return $this->gio_bat_dau ? \Carbon\Carbon::parse($this->gio_bat_dau)->format('H:i') : null;
    }

    /**
     * Format giờ kết thúc
     */
    public function getGioKetThucFormatAttribute()
    {
        return $this->gio_ket_thuc ? \Carbon\Carbon::parse($this->gio_ket_thuc)->format('H:i') : null;
    }
}