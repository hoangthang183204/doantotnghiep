<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChamCong extends Model
{
    use HasFactory;
    
    protected $table = 'cham_cong';
    
    protected $fillable = [
        'nguoi_dung_id',
        'ngay_cham_cong',
        'gio_vao',
        'gio_ra',
        'so_gio_lam',
        'so_cong',
        'gio_tang_ca',
        'phut_di_muon',
        'phut_ve_som',
        'trang_thai',
        'dia_chi_ip',
        'ten_wifi',
        'dia_chi_mac',
        'ten_thiet_bi',
        'phuong_thuc_cham_cong',
        'ghi_chu',
        'nguoi_phe_duyet_id',
        'trang_thai_duyet',
        'ghi_chu_duyet',
        'thoi_gian_phe_duyet'
    ];
    
    protected $casts = [
        'ngay_cham_cong' => 'date',
        'gio_vao' => 'datetime',
        'gio_ra' => 'datetime',
        'thoi_gian_phe_duyet' => 'datetime',
        'trang_thai_duyet' => 'integer',
    ];
    
    public function nguoi_dung()
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_dung_id');
    }
    
    public function nguoi_phe_duyet()
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_phe_duyet_id');
    }
}