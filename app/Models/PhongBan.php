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
    
    // ✅ Quan hệ với ChucVu
    public function chucVus()
    {
        return $this->hasMany(ChucVu::class, 'phong_ban_id');
    }
    
    // ✅ Quan hệ với NguoiDung (trưởng phòng) - Tên là 'truongPhong'
    public function truongPhong()
    {
        return $this->belongsTo(NguoiDung::class, 'truong_phong_id');
    }
    
    // ✅ Quan hệ với NguoiDung (nhân viên trong phòng)
    public function nguoiDungs()
    {
        return $this->hasMany(NguoiDung::class, 'phong_ban_id');
    }
    
    // ✅ ALIAS: Cho phép gọi cả 2 cách 'truong_phong' hoặc 'truongPhong'
    public function truong_phong()
    {
        return $this->belongsTo(NguoiDung::class, 'truong_phong_id');
    }
    
    // ✅ Scope lọc hoạt động
    public function scopeActive($query)
    {
        return $query->where('trang_thai', 1);
    }
}