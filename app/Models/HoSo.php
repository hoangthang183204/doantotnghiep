<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HoSo extends Model
{
    protected $table = 'ho_so_nguoi_dung';

    protected $fillable = [
        'ho',
        'ten',
        'email_cong_ty',
        'ma_nhan_vien',
        'ngay_sinh',
        'gioi_tinh',
        'dia_chi_hien_tai',
        'trang_thai',
    ];

    // CAST để tránh lỗi null / string
    protected $casts = [
        'trang_thai' => 'integer',
    ];

    // scope đang làm
    public function scopeDangLam($query)
    {
        return $query->where('trang_thai', 1);
    }

    // scope nghỉ việc
    public function scopeDaNghi($query)
    {
        return $query->where('trang_thai', 0);
    }
}