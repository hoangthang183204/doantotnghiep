<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LichSuLuong extends Model
{
    protected $table = 'lich_su_luong';

    protected $fillable = [
        'nguoi_dung_id',
        'hop_dong_id',
        'luong_cu',
        'luong_moi',
        'phu_cap_cu',
        'phu_cap_moi',
        'ngay_ap_dung',
        'loai',
        'ly_do',
        'trang_thai',
        'nguoi_tao_id',
        'nguoi_duyet_id',
        'thoi_gian_duyet',
        'ghi_chu'
    ];

    protected $casts = [
        'ngay_ap_dung' => 'date',
        'thoi_gian_duyet' => 'datetime',
        'luong_cu' => 'decimal:2',
        'luong_moi' => 'decimal:2',
        'phu_cap_cu' => 'decimal:2',
        'phu_cap_moi' => 'decimal:2',
    ];

    protected $attributes = [
        'trang_thai' => 'cho_duyet',
        'loai' => 'tang_luong',
    ];

    // ============================================================
    // RELATIONSHIPS
    // ============================================================

    public function nguoiDung(): BelongsTo
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_dung_id');
    }

    public function hopDong(): BelongsTo
    {
        return $this->belongsTo(HopDongLaoDong::class, 'hop_dong_id');
    }

    public function nguoiTao(): BelongsTo
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_tao_id');
    }

    public function nguoiDuyet(): BelongsTo
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_duyet_id');
    }

    // ============================================================
    // SCOPES
    // ============================================================

    public function scopeChoDuyet($query)
    {
        return $query->where('trang_thai', 'cho_duyet');
    }

    public function scopeDaDuyet($query)
    {
        return $query->where('trang_thai', 'da_duyet');
    }

    public function scopeTangLuong($query)
    {
        return $query->where('loai', 'tang_luong');
    }

    public function scopeGiamLuong($query)
    {
        return $query->where('loai', 'giam_luong');
    }

    // ============================================================
    // ACCESSORS
    // ============================================================

    public function getLoaiTextAttribute(): string
    {
        return match ($this->loai) {
            'tang_luong' => '✅ Tăng lương',
            'giam_luong' => '❌ Giảm lương',
            'dieu_chinh' => '🔄 Điều chỉnh',
            default => $this->loai,
        };
    }

    public function getTrangThaiTextAttribute(): string
    {
        return match ($this->trang_thai) {
            'cho_duyet' => '⏳ Chờ duyệt',
            'da_duyet' => '✅ Đã duyệt',
            'tu_choi' => '❌ Từ chối',
            default => $this->trang_thai,
        };
    }

    public function getChenhLechAttribute(): float
    {
        return $this->luong_moi - $this->luong_cu;
    }

    public function getPhanTramTangAttribute(): float
    {
        if ($this->luong_cu == 0) {
            return 0;
        }
        return round(($this->chenhLech / $this->luong_cu) * 100, 2);
    }
}