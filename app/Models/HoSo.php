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
        'so_dien_thoai',
        'ngay_sinh',
        'gioi_tinh',
        'dia_chi_hien_tai',
        'anh_dai_dien',
        'trang_thai',
    ];
    protected $casts = [
        'trang_thai' => 'integer',
    ];

    public function scopeDangLam($query)
    {
        return $query->where('trang_thai', 1);
    }

    public function scopeDaNghi($query)
    {
        return $query->where('trang_thai', 0);
    }
}
