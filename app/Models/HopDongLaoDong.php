<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HopDongLaoDong extends Model
{
    use HasFactory;

    protected $table = 'hop_dong_lao_dong';

    protected $fillable = [
        'nguoi_dung_id',
        'chuc_vu_id',
        'chuc_vu',
        'so_hop_dong',
        'loai_hop_dong',
        'ngay_bat_dau',
        'ngay_ket_thuc',
        'luong_co_ban',
        'hinh_thuc_lam_viec',
        'dia_diem_lam_viec',
        'duong_dan_file',
        'file_dinh_kem',
        'file_hop_dong_da_ky',
        'dieu_khoan',
        'trang_thai_hop_dong',
        'trang_thai_ky',
        'nguoi_ky_id',
        'thoi_gian_ky',
        'ghi_chu',
        'ly_do_huy',
        'nguoi_huy_id',
        'thoi_gian_huy',
        'trang_thai_tai_ky',
        'created_by',
    ];

    protected $casts = [
        'ngay_bat_dau' => 'date',
        'ngay_ket_thuc' => 'date',
        'thoi_gian_ky' => 'datetime',
        'thoi_gian_huy' => 'datetime',
        'luong_co_ban' => 'decimal:2',
        'phu_cap' => 'decimal:2',
    ];

    // ========== CÁC QUAN HỆ ==========

    /**
     * Quan hệ với NguoiDung (nhân viên)
     */
    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_dung_id');
    }

    /**
     * Quan hệ với HoSo (thông qua nguoi_dung_id)
     */
    public function hoSo()
    {
        return $this->belongsTo(HoSo::class, 'nguoi_dung_id', 'nguoi_dung_id');
    }

    /**
     * Quan hệ với NguoiDung (người ký hợp đồng)
     */
    public function nguoiKy()
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_ky_id');
    }

    /**
     * Quan hệ với NguoiDung (người hủy hợp đồng)
     */
    public function nguoiHuy()
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_huy_id');
    }

    /**
     * Quan hệ với ChucVu (chức vụ)
     */
    public function chucVu()
    {
        return $this->belongsTo(ChucVu::class, 'chuc_vu_id');
    }

    /**
     * Quan hệ với NguoiDung (người tạo hợp đồng)
     */
    public function nguoiGuiHopDong()
    {
        return $this->belongsTo(NguoiDung::class, 'created_by');
    }

    /**
     * Quan hệ với Luong (bảng lương)
     */
    public function luong()
    {
        return $this->hasOne(Luong::class, 'hop_dong_lao_dong_id');
    }

    // ========== ACCESSOR ==========

    /**
     * Lấy tên hiển thị trạng thái
     */
    public function getTenTrangThaiAttribute(): string
    {
        return match ($this->trang_thai_hop_dong) {
            'tao_moi' => 'Tạo mới',
            'chua_hieu_luc' => 'Chưa hiệu lực',
            'hieu_luc' => '✅ Hiệu lực',
            'het_han' => '⏳ Hết hạn',
            'huy_bo' => '⛔ Hủy bỏ',
            default => '---',
        };
    }

    /**
     * Lấy màu sắc cho trạng thái
     */
    public function getMauTrangThaiAttribute(): string
    {
        return match ($this->trang_thai_hop_dong) {
            'hieu_luc' => 'bg-green-100 text-green-700',
            'chua_hieu_luc' => 'bg-yellow-100 text-yellow-700',
            'het_han' => 'bg-gray-100 text-gray-500',
            'huy_bo' => 'bg-red-100 text-red-700',
            default => 'bg-gray-100 text-gray-500',
        };
    }

    /**
     * Lấy ngày bắt đầu định dạng d/m/Y
     */
    public function getNgayBatDauFormatAttribute(): string
    {
        return $this->ngay_bat_dau ? $this->ngay_bat_dau->format('d/m/Y') : '---';
    }

    /**
     * Lấy ngày kết thúc định dạng d/m/Y
     */
    public function getNgayKetThucFormatAttribute(): string
    {
        return $this->ngay_ket_thuc ? $this->ngay_ket_thuc->format('d/m/Y') : 'Không áp dụng';
    }

    public function getTenLoaiHopDongAttribute()
    {
        $map = [
            'thu_viec' => 'Hợp đồng thử việc',
            'xac_dinh_thoi_han' => 'Hợp đồng xác định thời hạn',
            'khong_xac_dinh_thoi_han' => 'Hợp đồng không xác định thời hạn',
            'mua_vu' => 'Hợp đồng mùa vụ',
        ];

        return $map[$this->loai_hop_dong] ?? $this->loai_hop_dong ?? '---';
    }

    public function hoSoNguoiDung()
    {
        return $this->belongsTo(HoSo::class, 'nguoi_dung_id', 'nguoi_dung_id');
    }

    public function phuCap()
    {
        return $this->belongsTo(PhuCap::class, 'phu_cap_id');
    }

    public function getPhuCapSoTienAttribute()
    {
        if ($this->phuCap) {
            return $this->phuCap->so_tien_mac_dinh;
        }
        return $this->phu_cap ?? 0;
    }

    public function getPhuCapTenAttribute()
    {
        if ($this->phuCap) {
            return $this->phuCap->ten;
        }
        return 'Phụ cấp khác';
    }
}
