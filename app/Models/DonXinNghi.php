<?php
// app/Models/DonXinNghi.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonXinNghi extends Model
{
    use HasFactory;

    protected $table = 'don_xin_nghi';

    protected $fillable = [
        'ma_don_nghi',
        'nguoi_dung_id',
        'loai_nghi_id',
        'loai_nghi_phep_id',
        'ngay_bat_dau',
        'ngay_ket_thuc',
        'so_ngay_nghi',
        'ly_do',
        'ghi_chu',
        'trang_thai',
        'ban_giao_cho_id',
        'ghi_chu_ban_giao',
        'cap_duyet_hien_tai',
        'lien_he_khan_cap',
        'sdt_khan_cap',
        'tai_lieu_ho_tro',
    ];

    protected $casts = [
        'ngay_bat_dau' => 'date',
        'ngay_ket_thuc' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'tai_lieu_ho_tro' => 'array',
    ];

    /**
     * Quan hệ với bảng nguoi_dung (người tạo đơn)
     */
    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_dung_id');
    }

    /**
     * Quan hệ với bảng nguoi_dung (người bàn giao)
     */
    public function banGiaoCho()
    {
        return $this->belongsTo(NguoiDung::class, 'ban_giao_cho_id');
    }

    /**
     * Quan hệ với bảng loai_nghi_phep (loại nghỉ)
     */
    public function loaiNghiPhep()
    {
        return $this->belongsTo(LoaiNghiPhep::class, 'loai_nghi_phep_id');
    }

    /**
     * Quan hệ với bảng loai_nghi_phep (loại nghỉ - cũ)
     */
    public function loaiNghi()
    {
        return $this->belongsTo(LoaiNghiPhep::class, 'loai_nghi_id');
    }
}