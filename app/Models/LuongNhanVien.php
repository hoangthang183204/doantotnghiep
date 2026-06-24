<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LuongNhanVien extends Model
{
    use HasFactory;

    protected $table = 'luong_nhan_vien';

    protected $fillable = [
        'bang_luong_id',
        'luong_thang',
        'luong_nam',
        'nguoi_dung_id',
        'luong_co_ban',
        'luong_theo_cong',
        'tong_phu_cap',
        'tien_tang_ca',
        'tong_khau_tru',
        'tong_luong',
        'luong_thuc_nhan',
        'so_ngay_cong',
        'so_ngay_cong_chuan',
        'gio_tang_ca',
        'cong_tang_ca',
        'ngay_nghi_phep',
        'ngay_nghi_khong_phep',
        'ngay_le',
        'thue_thu_nhap_ca_nhan',
        'ghi_chu',
    ];

    protected $casts = [
        'luong_thang'           => 'integer',
        'luong_nam'             => 'integer',
        'luong_co_ban'          => 'decimal:2',
        'luong_theo_cong'       => 'decimal:2',
        'tong_phu_cap'          => 'decimal:2',
        'tien_tang_ca'          => 'decimal:2',
        'tong_khau_tru'         => 'decimal:2',
        'tong_luong'            => 'decimal:2',
        'luong_thuc_nhan'       => 'decimal:2',
        'so_ngay_cong'          => 'decimal:2',
        'so_ngay_cong_chuan'    => 'decimal:2',
        'gio_tang_ca'           => 'decimal:2',
        'cong_tang_ca'          => 'decimal:2',
        'ngay_nghi_phep'        => 'decimal:2',
        'ngay_nghi_khong_phep'  => 'decimal:2',
        'ngay_le'               => 'decimal:2',
        'thue_thu_nhap_ca_nhan' => 'decimal:2',
    ];

    // =====================================================================
    // Relationships
    // =====================================================================

    public function bangLuong()
    {
        return $this->belongsTo(BangLuong::class, 'bang_luong_id');
    }

    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_dung_id');
    }

    public function khauTrus()
    {
        return $this->hasMany(KhauTruLuong::class, 'luong_nhan_vien_id');
    }

    /** Chi tiết phụ cấp đã áp dụng cho dòng lương này */
    public function phuCapLuongs()
    {
        return $this->hasMany(PhuCapLuong::class, 'luong_nhan_vien_id');
    }

    /** Chi tiết khấu trừ (BHXH, BHYT, BHTN, thuế...) */
    public function khauTruLuongs()
    {
        return $this->hasMany(KhauTruLuong::class, 'luong_nhan_vien_id');
    }

    public function hoSo()
    {
        return $this->hasOne(HoSo::class, 'nguoi_dung_id', 'nguoi_dung_id');
    }

    // =====================================================================
    // Accessors hỗ trợ hiển thị công thức
    // =====================================================================

    /** Đơn giá 1 ngày công = lương cơ bản / số ngày công chuẩn */
    public function getLuongMotNgayAttribute(): float
    {
        $chuan = (float) $this->so_ngay_cong_chuan ?: 26;
        return $chuan > 0 ? round((float) $this->luong_co_ban / $chuan, 2) : 0;
    }

    /** Đơn giá 1 giờ công = lương 1 ngày / 8 */
    public function getLuongMotGioAttribute(): float
    {
        return round($this->luong_mot_ngay / 8, 2);
    }
}
