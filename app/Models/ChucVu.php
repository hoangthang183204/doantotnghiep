<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChucVu extends Model
{
    use HasFactory;

    protected $table = 'chuc_vu';

    protected $fillable = [
        'ten',
        'ma',
        'mo_ta',
        'luong_co_ban',
        'he_so_luong',
        'phong_ban_id',
        'trang_thai',
    ];

    // Quan hệ với phòng ban (tên chính)
    public function phongBan(): BelongsTo
    {
        return $this->belongsTo(PhongBan::class, 'phong_ban_id');
    }

    // ⭐ ALIAS để dùng tên 'phong_ban' trong Controller
    public function phong_ban(): BelongsTo
    {
        return $this->phongBan();
    }

    // Quan hệ với nhân viên (tên chính)
    public function nguoiDungs(): HasMany
    {
        return $this->hasMany(NguoiDung::class, 'chuc_vu_id');
    }

    // ⭐ ALIAS để dùng tên 'nguoi_dungs' trong Controller
    public function nguoi_dungs(): HasMany
    {
        return $this->nguoiDungs();
    }

    // Quan hệ với tin tuyển dụng
    public function tinTuyenDungs(): HasMany
    {
        return $this->hasMany(TinTuyenDung::class, 'chuc_vu_id');
    }

    // Accessor
    public function getTenChucVuAttribute()
    {
        return $this->ten;
    }
}