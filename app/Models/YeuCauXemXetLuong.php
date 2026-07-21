<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class YeuCauXemXetLuong extends Model
{
    protected $table = 'yeu_cau_xem_xet_luongs';

    protected $fillable = [
        'luong_nhan_vien_id',
        'nguoi_dung_id',
        'ly_do',
        'trang_thai',
        'phan_hoi',
        'nguoi_duyet_id',
        'thoi_gian_duyet',
    ];

    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_dung_id');
    }

    public function luongNhanVien()
    {
        return $this->belongsTo(LuongNhanVien::class, 'luong_nhan_vien_id');
    }

    public function nguoiDuyet()
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_duyet_id');
    }
}