<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KyDanhGia extends Model
{
    protected $table = 'ky_danh_gia';
    protected $fillable = ['ten', 'loai', 'tu_ngay', 'den_ngay', 'trang_thai'];
    protected $casts = ['tu_ngay' => 'date', 'den_ngay' => 'date'];

    public function ketQuas()
    {
        return $this->hasMany(KetQuaDanhGia::class, 'ky_danh_gia_id');
    }
}
