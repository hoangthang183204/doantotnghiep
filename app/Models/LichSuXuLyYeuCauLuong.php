<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LichSuXuLyYeuCauLuong extends Model
{
    protected $table = 'lich_su_xu_ly_yeu_cau_luong';

    protected $fillable = [
        'yeu_cau_xem_xet_luong_id',
        'nguoi_thuc_hien_id',
        'hanh_dong',
        'du_lieu_cu',
        'du_lieu_moi',
        'ghi_chu',
        'thoi_gian',
    ];

    protected $casts = [
        'du_lieu_cu' => 'array',
        'du_lieu_moi' => 'array',
        'thoi_gian' => 'datetime',
    ];

    /**
     * Yêu cầu xem xét lương
     */
    public function yeuCau()
    {
        return $this->belongsTo(
            YeuCauXemXetLuong::class,
            'yeu_cau_xem_xet_luong_id'
        );
    }

    /**
     * Người thực hiện hành động
     */
    public function nguoiThucHien()
    {
        return $this->belongsTo(
            NguoiDung::class,
            'nguoi_thuc_hien_id'
        );
    }
}