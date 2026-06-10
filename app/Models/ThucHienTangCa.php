<?php

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
        'cong_viec_da_thuc_hien',
        'so_cong_tang_ca',
        'trang_thai',
        'vi_tri_check_in',
        'vi_tri_check_out',
        'ghi_chu',
    ];

    protected $casts = [
        'so_gio_tang_ca_thuc_te' => 'float',
        'so_cong_tang_ca'        => 'float',
    ];

    public static array $trangThaiLabels = [
        'chua_lam'         => 'Chưa làm',
        'dang_lam'         => 'Đang làm',
        'hoan_thanh'       => 'Hoàn thành',
        'khong_hoan_thanh' => 'Không hoàn thành',
    ];

    public function dang_ky()
    {
        return $this->belongsTo(DangKyTangCa::class, 'dang_ky_tang_ca_id');
    }
}
