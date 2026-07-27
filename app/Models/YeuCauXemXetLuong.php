<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class YeuCauXemXetLuong extends Model
{
    protected $table = 'yeu_cau_xem_xet_luongs';

    protected $fillable = [
        'luong_nhan_vien_id',
        'nguoi_dung_id',
        'loai_sai_sot',
        'ly_do',
        'trang_thai',
        'phan_hoi',
        'nguoi_duyet_id',
        'thoi_gian_duyet',
    ];

    protected $casts = [
        'loai_sai_sot' => 'string',
        'thoi_gian_duyet' => 'datetime',
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
    public function lichSuXuLy()
{
    return $this->hasMany(
        LichSuXuLyYeuCauLuong::class,
        'yeu_cau_xem_xet_luong_id'
    )->orderByDesc('thoi_gian');
}
}