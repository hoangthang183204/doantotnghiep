<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhongBan extends Model
{
    use HasFactory;

    protected $table = 'phong_ban';

    protected $fillable = [
        'ten_phong_ban',
        'ma_phong_ban',
        'mo_ta',
        'truong_phong_id',
        'ngan_sach',
        'trang_thai'
    ];

    protected $casts = [
        'ngan_sach' => 'decimal:2',
        'trang_thai' => 'integer',
    ];

    public function truong_phong()
    {
        return $this->belongsTo(NguoiDung::class, 'truong_phong_id');
    }

    public function nguoi_dungs()
    {
        return $this->hasMany(NguoiDung::class, 'phong_ban_id');
    }

    public function chuc_vus()
    {
        return $this->hasMany(ChucVu::class, 'phong_ban_id');
    }

    // Trong app/Models/PhongBan.php, thêm quan hệ:
    public function nguoi_dung()
    {
        return $this->hasMany(NguoiDung::class, 'phong_ban_id');
    }
}
