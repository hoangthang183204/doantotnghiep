<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TinTuyenDung extends Model
{
    protected $table = 'tin_tuyen_dung';

    public function phongBan()
    {
        return $this->belongsTo(PhongBan::class, 'phong_ban_id');
    }

    public function chucVu()
    {
        return $this->belongsTo(ChucVu::class, 'chuc_vu_id');
    }
}
   
