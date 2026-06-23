<?php
// app/Models/LichSuTaiKy.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LichSuTaiKy extends Model
{
    use HasFactory;

    protected $table = 'lich_su_tai_ky_hop_dong';

    protected $fillable = [
        'hop_dong_cu_id',
        'hop_dong_moi_id',
        'nguoi_thuc_hien_id',
        'ly_do_tai_ky',
    ];

    public function hopDongCu()
    {
        return $this->belongsTo(HopDongLaoDong::class, 'hop_dong_cu_id');
    }

    public function hopDongMoi()
    {
        return $this->belongsTo(HopDongLaoDong::class, 'hop_dong_moi_id');
    }

    public function nguoiThucHien()
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_thuc_hien_id');
    }
}