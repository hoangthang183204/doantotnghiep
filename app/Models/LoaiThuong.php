<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Danh mục loại thưởng do admin/HR tự định nghĩa
 * (chuyên cần, KPI, tháng 13, Tết, thâm niên, sáng kiến...).
 */
class LoaiThuong extends Model
{
    use HasFactory;

    protected $table = 'loai_thuong';

    protected $fillable = [
        'ten',
        'ma',
        'mo_ta',
        'hinh_thuc_mac_dinh',
        'cach_tinh',
        'gia_tri_mac_dinh',
        'chiu_thue',
        'trang_thai',
    ];

    protected $casts = [
        'gia_tri_mac_dinh' => 'decimal:2',
        'chiu_thue'        => 'boolean',
        'trang_thai'       => 'boolean',
    ];

    /** Hình thức áp dụng */
    public static array $hinhThucLabels = [
        'dinh_ky' => 'Định kỳ hàng tháng',
        'mot_lan' => 'Áp dụng 1 lần',
    ];

    /** Cách quy đổi ra tiền */
    public static array $cachTinhLabels = [
        'so_tien_co_dinh'    => 'Số tiền cố định',
        'phan_tram_luong_cb' => '% lương cơ bản',
    ];

    public function thuongNhanViens()
    {
        return $this->hasMany(ThuongNhanVien::class, 'loai_thuong_id');
    }

    public function scopeHoatDong($query)
    {
        return $query->where('trang_thai', true);
    }

    public function getCachTinhTextAttribute(): string
    {
        return self::$cachTinhLabels[$this->cach_tinh] ?? $this->cach_tinh;
    }

    public function getHinhThucTextAttribute(): string
    {
        return self::$hinhThucLabels[$this->hinh_thuc_mac_dinh] ?? $this->hinh_thuc_mac_dinh;
    }

    /** Hiển thị gọn giá trị mặc định: "300.000 đ" hoặc "10%" */
    public function getGiaTriTextAttribute(): string
    {
        return $this->cach_tinh === 'phan_tram_luong_cb'
            ? rtrim(rtrim(number_format((float) $this->gia_tri_mac_dinh, 2), '0'), '.') . '% lương CB'
            : number_format((float) $this->gia_tri_mac_dinh) . ' đ';
    }
}
