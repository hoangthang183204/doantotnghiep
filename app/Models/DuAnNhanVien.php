<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DuAnNhanVien extends Model
{
    protected $table = 'du_an_nhan_vien';

    protected $fillable = [
        'ho_so_id',
        'ten_du_an',
        'vai_tro',
        'ngay_bat_dau',
        'ngay_ket_thuc',
        'mo_ta',
        'trang_thai',
    ];

    protected $casts = [
        'ngay_bat_dau' => 'date',
        'ngay_ket_thuc' => 'date',
    ];

    public function hoSo(): BelongsTo
    {
        return $this->belongsTo(HoSo::class, 'ho_so_id');
    }

    // Lấy màu sắc cho trạng thái
    public function getMauTrangThaiAttribute(): string
    {
        return match ($this->trang_thai) {
            'Đang thực hiện' => 'bg-yellow-100 text-yellow-700',
            'Hoàn thành' => 'bg-green-100 text-green-700',
            'Tạm dừng' => 'bg-red-100 text-red-700',
            default => 'bg-gray-100 text-gray-700',
        };
    }

    // Lấy icon cho trạng thái
    public function getIconTrangThaiAttribute(): string
    {
        return match ($this->trang_thai) {
            'Đang thực hiện' => '🔄',
            'Hoàn thành' => '✅',
            'Tạm dừng' => '⏸️',
            default => '📌',
        };
    }

    // Lấy màu border cho dự án
    public function getMauBorderAttribute(): string
    {
        return match ($this->trang_thai) {
            'Đang thực hiện' => 'border-yellow-500',
            'Hoàn thành' => 'border-green-500',
            'Tạm dừng' => 'border-red-500',
            default => 'border-gray-500',
        };
    }
}