<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NguoiPhuThuoc extends Model
{
    protected $table = 'nguoi_phu_thuoc';

    protected $fillable = [
        'ho_so_id',
        'ho_ten',
        'ngay_sinh',
        'quan_he',
        'ma_so_thue',
        'ngay_bat_dau',
        'ngay_ket_thuc',
        'ghi_chu',
    ];

    protected $casts = [
        'ngay_sinh' => 'date',
        'ngay_bat_dau' => 'date',
        'ngay_ket_thuc' => 'date',
    ];

    public function hoSo(): BelongsTo
    {
        return $this->belongsTo(HoSo::class, 'ho_so_id');
    }

    public function getQuanHeTextAttribute(): string
    {
        $map = [
            'con' => 'Con',
            'vo' => 'Vợ',
            'chong' => 'Chồng',
            'cha' => 'Cha',
            'me' => 'Mẹ',
            'khac' => 'Khác',
        ];
        return $map[$this->quan_he] ?? $this->quan_he;
    }

    public function getConHanAttribute(): bool
    {
        if (!$this->ngay_ket_thuc) return true;
        return $this->ngay_ket_thuc->isFuture();
    }
}