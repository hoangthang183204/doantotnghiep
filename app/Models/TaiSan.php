<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaiSan extends Model
{
    protected $table = 'tai_san';
    protected $fillable = ['ma', 'ten', 'loai', 'serial', 'tinh_trang', 'ghi_chu'];

    public function capPhats()
    {
        return $this->hasMany(CapPhatTaiSan::class, 'tai_san_id');
    }

    public function capPhatHienTai()
    {
        return $this->hasOne(CapPhatTaiSan::class, 'tai_san_id')->where('trang_thai', 'dang_cap');
    }
}
