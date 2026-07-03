<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KhenThuongKyLuatNhanVien extends Model
{
    protected $table = 'khen_thuong_ky_luat_nhan_vien';


    protected $fillable = [
        'ho_so_id',
        'loai',
        'ten',
        'ngay',
        'noi_dung',
        'hinh_thuc',
        'so_tien',
        'quyet_dinh_so',
        'nguoi_ky_id',
        'muc_do',
        'bang_chung',
        'quyet_dinh_file',
    ];

    protected $casts = [
        'ngay' => 'date',
        'so_tien' => 'decimal:2',
    ];

    public function hoSo(): BelongsTo
    {
        return $this->belongsTo(HoSo::class, 'ho_so_id');
    }

    public function nguoiKy(): BelongsTo
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_ky_id');
    }

    public function getLoaiTextAttribute(): string
    {
        return $this->loai == 'khen_thuong' ? '🏆 Khen thưởng' : '⚠️ Kỷ luật';
    }

    public function getMauLoaiAttribute(): string
    {
        return $this->loai == 'khen_thuong'
            ? 'bg-green-50 dark:bg-green-900/20 border-l-4 border-green-500'
            : 'bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500';
    }

    public function getMucDoTextAttribute(): ?string
    {
        if ($this->loai === 'khen_thuong') {
            return match ($this->muc_do) {
                'kha' => '⭐ Khá',
                'gioi' => '🥈 Giỏi',
                'xuat_sac' => '🏆 Xuất sắc',
                default => null,
            };
        }

        return match ($this->muc_do) {
            'khien_trach' => '🟡 Khiển trách',
            'canh_cao' => '🟠 Cảnh cáo',
            'sa_thai' => '🔴 Sa thải',
            default => null,
        };
    }
}
