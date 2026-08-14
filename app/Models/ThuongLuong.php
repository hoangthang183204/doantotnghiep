<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Chi tiết 1 khoản thưởng đã áp dụng cho 1 dòng lương (snapshot khi chốt lương).
 */
class ThuongLuong extends Model
{
    use HasFactory;

    protected $table = 'thuong_luong';

    protected $fillable = [
        'luong_nhan_vien_id',
        'loai_thuong_id',
        'thuong_nhan_vien_id',
        'ten',
        'hinh_thuc',
        'so_tien',
        'chiu_thue',
        'ghi_chu',
    ];

    protected $casts = [
        'so_tien'   => 'decimal:2',
        'chiu_thue' => 'boolean',
    ];

    public function luongNhanVien()
    {
        return $this->belongsTo(LuongNhanVien::class, 'luong_nhan_vien_id');
    }

    public function loaiThuong()
    {
        return $this->belongsTo(LoaiThuong::class, 'loai_thuong_id');
    }

    public function getHinhThucTextAttribute(): string
    {
        return ThuongNhanVien::$hinhThucLabels[$this->hinh_thuc] ?? $this->hinh_thuc;
    }
}
