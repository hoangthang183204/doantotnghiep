<?php
// app/Models/ThucHienTangCa.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThucHienTangCa extends Model
{
    use HasFactory;

    protected $table = 'thuc_hien_tang_ca';

    protected $fillable = [
        'dang_ky_tang_ca_id',
        'gio_bat_dau_thuc_te',
        'gio_ket_thuc_thuc_te',
        'so_gio_tang_ca_thuc_te',
        'so_cong_tang_ca',
        'cong_viec_da_thuc_hien',
        'ghi_chu',
        'trang_thai',
        'vi_tri_check_in',
        'vi_tri_check_out',
    ];

    protected $casts = [
        'gio_bat_dau_thuc_te' => 'datetime',
        'gio_ket_thuc_thuc_te' => 'datetime',
        'so_gio_tang_ca_thuc_te' => 'float',
        'so_cong_tang_ca' => 'float',
    ];

    // ⭐ Cập nhật danh sách trạng thái - TIẾNG VIỆT
    public static array $trangThaiThucHienLabels = [
        'chua_lam' => '⏳ Chưa làm',
        'dang_lam' => '🔄 Đang làm',
        'hoan_thanh' => '✅ Hoàn thành',
        'khong_hoan_thanh' => '❌ Không hoàn thành',
        'nhan_vien_xac_nhan' => '👤 Nhân viên đã xác nhận',
        'quan_ly_xac_nhan' => '✅ Quản lý đã xác nhận hoàn thành',
    ];

    // ⭐ Danh sách màu sắc cho trạng thái
    public static array $trangThaiThucHienColors = [
        'chua_lam' => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300',
        'dang_lam' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
        'hoan_thanh' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
        'khong_hoan_thanh' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
        'nhan_vien_xac_nhan' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400',
        'quan_ly_xac_nhan' => 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400',
    ];

    // Relationships
    public function dang_ky_tang_ca()
    {
        return $this->belongsTo(DangKyTangCa::class, 'dang_ky_tang_ca_id');
    }

    // Scopes
    public function scopeNhanVienXacNhan($query)
    {
        return $query->where('trang_thai', 'nhan_vien_xac_nhan');
    }

    public function scopeQuanLyXacNhan($query)
    {
        return $query->where('trang_thai', 'quan_ly_xac_nhan');
    }

    public function scopeChuaLam($query)
    {
        return $query->where('trang_thai', 'chua_lam');
    }

    public function scopeDangLam($query)
    {
        return $query->where('trang_thai', 'dang_lam');
    }

    public function scopeHoanThanh($query)
    {
        return $query->where('trang_thai', 'hoan_thanh');
    }

    /**
     * Lấy label trạng thái
     */
    public function getTrangThaiLabelAttribute()
    {
        return self::$trangThaiThucHienLabels[$this->trang_thai] ?? $this->trang_thai;
    }

    /**
     * Lấy màu trạng thái
     */
    public function getTrangThaiColorAttribute()
    {
        return self::$trangThaiThucHienColors[$this->trang_thai] ?? 'bg-gray-100 text-gray-700';
    }
}