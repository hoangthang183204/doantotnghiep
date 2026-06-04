<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HopDongLaoDong extends Model
{
    use HasFactory;
    
    // Chỉ định đúng tên bảng
    protected $table = 'hop_dong_lao_dong';
    
    protected $fillable = [
        'created_by',
        'nguoi_dung_id',
        'chuc_vu_id',
        'so_hop_dong',
        'loai_hop_dong',
        'ngay_bat_dau',
        'ngay_ket_thuc',
        'luong_co_ban',
        'phu_cap',
        'hinh_thuc_lam_viec',
        'dia_diem_lam_viec',
        'ghi_chu',
        'ly_do_huy',
        'duong_dan_file',
        'file_dinh_kem',
        'file_hop_dong_da_ky',
        'dieu_khoan',
        'trang_thai_hop_dong',
        'trang_thai_ky',
        'trang_thai_tai_ky',
        'nguoi_ky_id',
        'thoi_gian_ky',
        'nguoi_huy_id',
        'thoi_gian_huy'
    ];
    
    protected $casts = [
        'ngay_bat_dau' => 'date',
        'ngay_ket_thuc' => 'date',
        'thoi_gian_ky' => 'datetime',
        'thoi_gian_huy' => 'datetime',
        'luong_co_ban' => 'decimal:2',
        'phu_cap' => 'decimal:2',
    ];
    
    public function nguoi_dung()
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_dung_id');
    }
    
    public function chuc_vu()
    {
        return $this->belongsTo(ChucVu::class, 'chuc_vu_id');
    }
    
    public function created_by_user()
    {
        return $this->belongsTo(NguoiDung::class, 'created_by');
    }
    
    public function nguoi_ky()
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_ky_id');
    }
}