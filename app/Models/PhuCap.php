<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhuCap extends Model
{
    use HasFactory;
    
    protected $table = 'phu_cap';
    
    protected $fillable = [
        'ten',
        'ma',
        'mo_ta',
        'loai_phu_cap',
        'so_tien_mac_dinh',
        'cach_tinh',
        'chiu_thue',
        'dieu_kien_ap_dung',
        'trang_thai'
    ];
    
    protected $casts = [
        'so_tien_mac_dinh' => 'decimal:2',
        'dieu_kien_ap_dung' => 'array',
        'trang_thai' => 'integer',
    ];
    
    public function nhan_viens()
    {
        return $this->hasMany(PhuCapNhanVien::class, 'phu_cap_id');
    }
}