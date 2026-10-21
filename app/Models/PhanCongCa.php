<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhanCongCa extends Model
{
    protected $table = 'phan_cong_ca';

    protected $fillable = ['nguoi_dung_id', 'ca_lam_viec_id', 'ngay', 'ghi_chu'];

    protected $casts = ['ngay' => 'date'];

    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_dung_id');
    }

    public function ca()
    {
        return $this->belongsTo(CaLamViec::class, 'ca_lam_viec_id');
    }
}
