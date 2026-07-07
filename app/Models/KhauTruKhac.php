<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Khoản khấu trừ khác của nhân viên trong 1 tháng (tạm ứng, phạt, bồi thường...).
 */
class KhauTruKhac extends Model
{
    use HasFactory;

    protected $table = 'khau_tru_khac';

    protected $fillable = [
        'nguoi_dung_id',
        'thang',
        'nam',
        'loai',
        'so_tien',
        'ly_do',
        'trang_thai',
        'nguoi_tao_id',
    ];

    protected $casts = [
        'thang'   => 'integer',
        'nam'     => 'integer',
        'so_tien' => 'decimal:2',
    ];

    /** Nhãn hiển thị của từng loại khấu trừ */
    public static array $loaiLabels = [
        'tam_ung'    => 'Tạm ứng',
        'phat'       => 'Phạt vi phạm',
        'boi_thuong' => 'Bồi thường',
        'khac'       => 'Khấu trừ khác',
    ];

    public function getLoaiTextAttribute(): string
    {
        return self::$loaiLabels[$this->loai] ?? $this->loai;
    }

    public function getLoaiBadgeAttribute(): string
    {
        return match ($this->loai) {
            'tam_ung'    => 'bg-amber-100 text-amber-800',
            'phat'       => 'bg-red-100 text-red-800',
            'boi_thuong' => 'bg-orange-100 text-orange-800',
            default      => 'bg-gray-100 text-gray-800',
        };
    }

    // =====================================================================
    // Relationships
    // =====================================================================

    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_dung_id');
    }

    public function nguoiTao()
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_tao_id');
    }

    // =====================================================================
    // Scopes
    // =====================================================================

    public function scopeHieuLuc($query)
    {
        return $query->where('trang_thai', 'hieu_luc');
    }

    public function scopeThang($query, int $thang, int $nam)
    {
        return $query->where('thang', $thang)->where('nam', $nam);
    }
}
