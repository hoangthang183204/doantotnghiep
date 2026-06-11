<?php

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
    ];

    protected $casts = [
        'ngay_tang_ca'    => 'date',
        'thoi_gian_duyet' => 'datetime',
        'so_gio_tang_ca'  => 'float',
    ];

    // Nhãn trạng thái
    public static array $trangThaiLabels = [
        'cho_duyet' => 'Chờ duyệt',
        'da_duyet'  => 'Đã duyệt',
        'tu_choi'   => 'Từ chối',
        'huy'       => 'Đã huỷ',
    ];

    // Nhãn loại tăng ca
    public static array $loaiLabels = [
        'ngay_thuong' => 'Ngày thường',
        'ngay_nghi'   => 'Ngày nghỉ',
        'le_tet'      => 'Lễ / Tết',
    ];

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    public function nguoi_dung()
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_dung_id');
    }

    public function nguoi_duyet()
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_duyet_id');
    }

    public function thuc_hien()
    {
        return $this->hasOne(
            ThucHienTangCa::class,
            'dang_ky_tang_ca_id'
        );
    }

    // -------------------------------------------------------------------------
    // Scopes
    // -------------------------------------------------------------------------

    public function scopeChoDuyet($query)
    {
        return $query->where('trang_thai', 'cho_duyet');
    }

    public function scopeDaDuyet($query)
    {
        return $query->where('trang_thai', 'da_duyet');
    }
}
