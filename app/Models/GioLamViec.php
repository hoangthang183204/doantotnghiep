<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class GioLamViec extends Model
{
    protected $table = 'gio_lam_viec';

    protected $fillable = [
        'gio_bat_dau',
        'gio_ket_thuc',
        'gio_nghi_trua',
        'so_phut_cho_phep_di_tre',
        'so_phut_cho_phep_ve_som',
        'gio_bat_dau_tang_ca',
    ];

    protected $casts = [
        'gio_bat_dau' => 'datetime:H:i',
        'gio_ket_thuc' => 'datetime:H:i',
        'gio_nghi_trua' => 'float',
        'so_phut_cho_phep_di_tre' => 'integer',
        'so_phut_cho_phep_ve_som' => 'integer',
        'gio_bat_dau_tang_ca' => 'datetime:H:i',
    ];

    /**
     * Lấy cấu hình giờ làm việc hiện tại
     */
    public static function current(): ?self
    {
        return self::first();
    }

    /**
     * Kiểm tra giờ hiện tại có phải giờ làm việc không
     */
    public function isWorkingTime(): bool
    {
        $now = Carbon::now();
        $start = Carbon::parse($this->gio_bat_dau);
        $end = Carbon::parse($this->gio_ket_thuc);

        return $now->between($start, $end);
    }

    /**
     * Kiểm tra giờ hiện tại có phải giờ tăng ca không
     */
    public function isOvertimeTime(): bool
    {
        if (!$this->gio_bat_dau_tang_ca) {
            return false;
        }

        $now = Carbon::now();
        $start = Carbon::parse($this->gio_bat_dau_tang_ca);

        return $now->gt($start);
    }

    /**
     * Lấy số giờ làm việc trong ngày (tính cả nghỉ trưa)
     */
    public function getSoGioLamViecTrongNgay(): float
    {
        $start = Carbon::parse($this->gio_bat_dau);
        $end = Carbon::parse($this->gio_ket_thuc);

        return round($start->diffInHours($end) - ($this->gio_nghi_trua ?? 0), 2);
    }

    public function getGioBatDauFormatAttribute(): string
    {
        return $this->gio_bat_dau ? Carbon::parse($this->gio_bat_dau)->format('H:i') : '--:--';
    }

    public function getGioKetThucFormatAttribute(): string
    {
        return $this->gio_ket_thuc ? Carbon::parse($this->gio_ket_thuc)->format('H:i') : '--:--';
    }

    public function getGioBatDauTangCaFormatAttribute(): string
    {
        return $this->gio_bat_dau_tang_ca ? Carbon::parse($this->gio_bat_dau_tang_ca)->format('H:i') : '--:--';
    }
}