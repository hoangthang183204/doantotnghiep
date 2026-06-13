<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaiLieu extends Model
{
    protected $table = 'tai_lieu';

    protected $fillable = [
        'nguoi_dung_id',
        'ung_vien_id',
        'loai_tai_lieu',
        'tieu_de',
        'mo_ta',
        'ten_file_goc',
        'duong_dan_file',
        'kich_thuoc_file',
        'loai_mime',
        'bao_mat',
        'ngay_het_han',
        'nguoi_tai_len_id',
        'thoi_gian_tai_len',
        'trang_thai',
    ];

    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_dung_id');
    }
}
