<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MucTieuDanhGia extends Model
{
    protected $table = 'muc_tieu_danh_gia';
    protected $fillable = [
        'ket_qua_danh_gia_id', 'ten', 'trong_so', 'muc_tieu', 'thuc_dat', 'diem',
    ];
}
