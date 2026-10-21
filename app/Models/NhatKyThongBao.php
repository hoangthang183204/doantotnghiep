<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NhatKyThongBao extends Model
{
    protected $table = 'nhat_ky_thong_bao';
    protected $fillable = ['nguoi_dung_id', 'kenh', 'tieu_de', 'noi_dung', 'trang_thai', 'chi_tiet'];
}
