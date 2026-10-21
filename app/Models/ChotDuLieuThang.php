<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChotDuLieuThang extends Model
{
    protected $table = 'chot_du_lieu_thang';

    protected $fillable = [
        'thang', 'nam', 'trang_thai', 'nguoi_chot_id', 'thoi_gian_chot', 'ghi_chu',
    ];

    protected $casts = [
        'thoi_gian_chot' => 'datetime',
    ];

    public function nguoiChot()
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_chot_id');
    }
}
