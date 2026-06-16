<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhuCapNhanVien extends Model
{
    use HasFactory;

    protected $table = 'phu_cap_nhan_vien';

    protected $fillable = [
        'nguoi_dung_id',
        'phu_cap_id',
        'so_tien',
        'ngay_hieu_luc',
        'ngay_ket_thuc',
        'trang_thai',
        'ghi_chu'
    ];

    protected $casts = [
        'so_tien' => 'decimal:2',
        'ngay_hieu_luc' => 'date',
        'ngay_ket_thuc' => 'date',
    ];

    public function nguoi_dung()
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_dung_id');
    }

    public function phuCap()
    {
        return $this->belongsTo(PhuCap::class, 'phu_cap_id');
    }
}
