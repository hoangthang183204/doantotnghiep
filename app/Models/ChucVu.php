<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChucVu extends Model
{
    use HasFactory;

    protected $table = 'chuc_vu';

    protected $fillable = [
        'ten',           // Tên cột trong database là 'ten'
        'ma',
        'mo_ta',
        'luong_co_ban',
        'he_so_luong',
        'phong_ban_id',
        'trang_thai',
    ];

    // Quan hệ với phòng ban
    public function phongBan()
    {
        return $this->belongsTo(PhongBan::class, 'phong_ban_id');
    }

    // Quan hệ với nhân viên
    public function nguoiDungs()
    {
        return $this->hasMany(NguoiDung::class, 'chuc_vu_id');
    }

    // Quan hệ với tin tuyển dụng
    public function tinTuyenDungs()
    {
        return $this->hasMany(TinTuyenDung::class, 'chuc_vu_id');
    }

    // Accessor để tương thích với tên cũ
    public function getTenChucVuAttribute()
    {
        return $this->ten;
    }
}