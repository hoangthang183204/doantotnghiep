<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CapPhatTaiSan extends Model
{
    protected $table = 'cap_phat_tai_san';
    protected $fillable = [
        'tai_san_id', 'nguoi_dung_id', 'ngay_cap', 'ngay_thu',
        'nguoi_cap_id', 'ghi_chu', 'trang_thai',
    ];
    protected $casts = ['ngay_cap' => 'date', 'ngay_thu' => 'date'];

    public function taiSan()
    {
        return $this->belongsTo(TaiSan::class, 'tai_san_id');
    }

    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_dung_id');
    }
}
