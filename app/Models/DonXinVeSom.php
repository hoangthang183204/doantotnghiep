<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonXinVeSom extends Model
{
    use HasFactory;

    protected $table = 'don_xin_ve_som';

    protected $fillable = [
        'nguoi_dung_id',
        'cham_cong_id',
        'ngay',
        'gio_ra_du_kien',
        'so_phut_ve_som',
        'ly_do',
        'trang_thai',
        'ly_do_tu_choi',
        'nguoi_duyet_id',
        'thoi_gian_duyet',
    ];

    protected $casts = [
        'ngay' => 'date',
        'thoi_gian_duyet' => 'datetime',
    ];

    // ⭐ SỬA: Dùng NguoiDung thay vì User
    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_dung_id');
    }

    public function chamCong()
    {
        return $this->belongsTo(ChamCong::class, 'cham_cong_id');
    }

    // ⭐ SỬA: Dùng NguoiDung thay vì User
    public function nguoiDuyet()
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_duyet_id');
    }

    // Scopes
    public function scopeChoDuyet($query)
    {
        return $query->where('trang_thai', 'cho_duyet');
    }

    public function scopeDaDuyet($query)
    {
        return $query->where('trang_thai', 'da_duyet');
    }

    public function scopeTuChoi($query)
    {
        return $query->where('trang_thai', 'tu_choi');
    }

    // Accessors
    public function getTrangThaiTextAttribute()
    {
        return [
            'cho_duyet' => '⏳ Chờ duyệt',
            'da_duyet' => '✅ Đã duyệt',
            'tu_choi' => '❌ Từ chối',
        ][$this->trang_thai] ?? $this->trang_thai;
    }

    public function getTrangThaiColorAttribute()
    {
        return [
            'cho_duyet' => 'yellow',
            'da_duyet' => 'green',
            'tu_choi' => 'red',
        ][$this->trang_thai] ?? 'gray';
    }
}