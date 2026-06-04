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
        'tong_phu_cap',
        'tong_khau_tru',
        'tong_luong',
        'luong_thuc_nhan',
        'so_ngay_cong',
        'gio_tang_ca',
        'cong_tang_ca',
        'ngay_nghi_phep',
        'ngay_nghi_khong_phep',
        'ngay_le',
        'thue_thu_nhap_ca_nhan',
        'ghi_chu'
    ];
    
    protected $casts = [
        'luong_thang' => 'integer',
        'luong_nam' => 'integer',
        'luong_co_ban' => 'decimal:2',
        'tong_phu_cap' => 'decimal:2',
        'tong_khau_tru' => 'decimal:2',
        'tong_luong' => 'decimal:2',
        'luong_thuc_nhan' => 'decimal:2',
        'so_ngay_cong' => 'decimal:2',
        'gio_tang_ca' => 'decimal:2',
        'cong_tang_ca' => 'decimal:2',
        'ngay_nghi_phep' => 'decimal:2',
        'ngay_nghi_khong_phep' => 'decimal:2',
        'ngay_le' => 'decimal:2',
        'thue_thu_nhap_ca_nhan' => 'decimal:2',
    ];
    
    public function bangLuong()
    {
        return $this->belongsTo(BangLuong::class, 'bang_luong_id');
    }
    
    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_dung_id');
    }
}