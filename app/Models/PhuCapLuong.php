<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Chi tiết phụ cấp của 1 dòng lương nhân viên (itemized).
 */
class PhuCapLuong extends Model
{
    use HasFactory;

    protected $table = 'phu_cap_luong';

    public $timestamps = false;

    protected $fillable = [
        'luong_nhan_vien_id',
        'phu_cap_id',
        'so_tien',
        'ghi_chu',
    ];

    protected $casts = [
        'so_tien' => 'decimal:2',
    ];

    public function luongNhanVien()
    {
        return $this->belongsTo(LuongNhanVien::class, 'luong_nhan_vien_id');
    }

    public function phuCap()
    {
        return $this->belongsTo(PhuCap::class, 'phu_cap_id');
    }
}
