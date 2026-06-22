<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BangLuong extends Model
{
    use HasFactory;

    protected $table = 'bang_luong';

    protected $fillable = [
        'ma_bang_luong',
        'loai_bang_luong',
        'nam',
        'thang',
        'trang_thai',
        'nguoi_xu_ly_id',
        'thoi_gian_xu_ly',
        'nguoi_phe_duyet_id',
        'thoi_gian_phe_duyet',
    ];

    protected $casts = [
        'nam'                 => 'integer',
        'thang'               => 'integer',
        'thoi_gian_xu_ly'     => 'datetime',
        'thoi_gian_phe_duyet' => 'datetime',
    ];

    // Nhãn các trạng thái (khớp enum trong DB)
    public static array $trangThaiLabels = [
        'dang_xu_ly' => 'Nháp / Đang xử lý',
        'cho_duyet'  => 'Chờ duyệt',
        'da_duyet'   => 'Đã duyệt',
        'da_chot'    => 'Đã chốt',
        'da_tra'     => 'Đã thanh toán',
    ];

    // =====================================================================
    // Relationships
    // =====================================================================

    public function nguoiXuLy()
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_xu_ly_id');
    }

    public function nguoiPheDuyet()
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_phe_duyet_id');
    }

    public function luongNhanViens()
    {
        return $this->hasMany(LuongNhanVien::class, 'bang_luong_id');
    }

    // =====================================================================
    // Helpers
    // =====================================================================

    public function getTrangThaiTextAttribute(): string
    {
        return self::$trangThaiLabels[$this->trang_thai] ?? $this->trang_thai;
    }

    public function getTrangThaiBadgeAttribute(): string
    {
        return match ($this->trang_thai) {
            'dang_xu_ly' => 'bg-yellow-100 text-yellow-800',
            'cho_duyet'  => 'bg-blue-100 text-blue-800',
            'da_duyet'   => 'bg-indigo-100 text-indigo-800',
            'da_chot'    => 'bg-green-100 text-green-800',
            'da_tra'     => 'bg-emerald-100 text-emerald-800',
            default      => 'bg-gray-100 text-gray-800',
        };
    }

    /** Bảng còn ở trạng thái nháp -> được phép sửa/xoá/tính lại */
    public function getLaNhapAttribute(): bool
    {
        return in_array($this->trang_thai, ['dang_xu_ly', 'cho_duyet'], true);
    }

    /** Đã chốt (khoá) */
    public function getDaChotAttribute(): bool
    {
        return in_array($this->trang_thai, ['da_chot', 'da_tra'], true);
    }
}
