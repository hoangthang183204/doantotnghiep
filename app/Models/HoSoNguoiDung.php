<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HoSoNguoiDung extends Model
{
    use HasFactory;

    protected $table = 'ho_so_nguoi_dung';

    protected $fillable = [
        'nguoi_dung_id',
        'ma_nhan_vien',
        'ho',
        'ten',
        'email_cong_ty',
        'so_dien_thoai',
        'ngay_sinh',
        'gioi_tinh',
        'dia_chi_hien_tai',
        'dia_chi_thuong_tru',
        'cmnd_cccd',
        'so_ho_chieu',
        'tinh_trang_hon_nhan',
        'anh_dai_dien',
        'lien_he_khan_cap',
        'sdt_khan_cap',
        'quan_he_khan_cap',
        'anh_cccd_truoc',
        'anh_cccd_sau',
    ];

    protected $casts = [
        'ngay_sinh' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Quan hệ với NguoiDung
    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_dung_id');
    }

    // ========== THÊM QUAN HỆ NÀY ==========
    public function hopDongLaoDong()
    {
        return $this->hasMany(HopDongLaoDong::class, 'nguoi_dung_id', 'nguoi_dung_id');
    }

    public function hoSo()
    {
        return $this->belongsTo(HoSo::class, 'nguoi_dung_id', 'nguoi_dung_id');
    }
}
