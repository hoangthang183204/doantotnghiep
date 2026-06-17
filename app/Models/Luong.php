<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Luong extends Model
{
    use HasFactory;

    protected $table = 'luong';

    protected $fillable = [
        'nguoi_dung_id',
        'hop_dong_lao_dong_id',
        'luong_co_ban',
        'phu_cap',
        'tien_thuong',
        'tien_phat',
    ];

    public function nguoiDung()
{
    return $this->belongsTo(NguoiDung::class, 'nguoi_dung_id');
}

public function hopDongLaoDong()
{
    return $this->belongsTo(HopDongLaoDong::class, 'hop_dong_lao_dong_id');
}
public function hopDong()
{
    return $this->belongsTo(HopDongLaoDong::class, 'hop_dong_lao_dong_id');
}
}