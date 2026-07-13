<?php
// app/Models/DangKyTangCa.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DangKyTangCa extends Model
{
    use HasFactory;

    protected $table = 'dang_ky_tang_ca';

    protected $fillable = [
        'nguoi_dung_id',
        'ngay_tang_ca',
        'gio_bat_dau',
        'gio_ket_thuc',
        'so_gio_tang_ca',
        'loai_tang_ca',
        'ly_do_tang_ca',
        'trang_thai',
        'nguoi_duyet_id',
        'thoi_gian_duyet',
        'ly_do_tu_choi',
        'da_hoan_thanh',
        'thoi_gian_hoan_thanh',
        'luong_tang_ca',
    ];

    protected $casts = [
        'ngay_tang_ca' => 'date',
        'thoi_gian_duyet' => 'datetime',
        'thoi_gian_hoan_thanh' => 'datetime',
        'so_gio_tang_ca' => 'float',
        'luong_tang_ca' => 'float',
        'da_hoan_thanh' => 'boolean',
    ];

    // Nhãn trạng thái
    public static array $trangThaiLabels = [
        'cho_duyet' => 'Chờ duyệt',
        'da_duyet' => 'Đã duyệt',
        'tu_choi' => 'Từ chối',
        'huy' => 'Đã huỷ',
    ];

    // Nhãn loại tăng ca
    public static array $loaiLabels = [
        'ngay_thuong' => 'Ngày thường',
        'ngay_nghi' => 'Ngày nghỉ',
        'le_tet' => 'Lễ / Tết',
    ];

    // Hệ số lương tăng ca
    public static array $heSoLuong = [
        'ngay_thuong' => 1.5,
        'ngay_nghi' => 2.0,
        'le_tet' => 3.0,
    ];

    // ============================================================
    // ⭐ RELATIONSHIPS - SỬA TÊN CHO ĐÚNG
    // ============================================================

    /**
     * Quan hệ với bảng nguoi_dung (người tạo đơn)
     * Tên: nguoi_dung (không phải nguoiDung)
     */
    public function nguoi_dung()
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_dung_id');
    }

    /**
     * Quan hệ với bảng nguoi_dung (người duyệt)
     */
    public function nguoi_duyet()
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_duyet_id');
    }

    /**
     * Quan hệ với bảng thuc_hien_tang_ca
     */
    public function thuc_hien()
    {
        return $this->hasOne(ThucHienTangCa::class, 'dang_ky_tang_ca_id');
    }

    // ============================================================
    // ⭐ SCOPES
    // ============================================================

    public function scopeChoDuyet($query)
    {
        return $query->where('trang_thai', 'cho_duyet');
    }

    public function scopeDaDuyet($query)
    {
        return $query->where('trang_thai', 'da_duyet');
    }

    public function scopeChuaHoanThanh($query)
    {
        return $query->where('trang_thai', 'da_duyet')
            ->where('da_hoan_thanh', false);
    }

    public function scopeDaHoanThanh($query)
    {
        return $query->where('trang_thai', 'da_duyet')
            ->where('da_hoan_thanh', true);
    }

    // ============================================================
    // ⭐ METHODS
    // ============================================================

    /**
     * Tính lương tăng ca
     */
    public function tinhLuongTangCa($luongCoBan)
    {
        $heSo = self::$heSoLuong[$this->loai_tang_ca] ?? 1.5;
        $this->luong_tang_ca = $this->so_gio_tang_ca * $luongCoBan * $heSo;
        return $this->luong_tang_ca;
    }

    /**
     * Đánh dấu hoàn thành
     */
    public function hoanThanh()
    {
        $this->da_hoan_thanh = true;
        $this->thoi_gian_hoan_thanh = now();

        if (!$this->luong_tang_ca) {
            $luongCoBan = $this->nguoi_dung->luong_co_ban ?? 0;
            $this->tinhLuongTangCa($luongCoBan);
        }

        $this->save();
        return $this;
    }
}