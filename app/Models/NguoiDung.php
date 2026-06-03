<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class NguoiDung extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'nguoi_dung';
    
    protected $fillable = [
        'ten_dang_nhap',
        'email',
        'password',
        'vai_tro_id',
        'trang_thai',
        'trang_thai_cong_viec',
        'phong_ban_id',
        'chuc_vu_id',
        'branch_id',
        'da_hoan_thanh_ho_so',
        'dang_nhap_lan_dau',
        'theme'
    ];
    
    protected $hidden = [
        'password',
        'remember_token',
    ];
    
    protected $casts = [
        'email_verified_at' => 'datetime',
        'lan_dang_nhap_cuoi' => 'datetime',
        'trang_thai' => 'integer',
        'da_hoan_thanh_ho_so' => 'integer',
        'dang_nhap_lan_dau' => 'integer',
    ];
    
    // Relationships
    public function ho_so()
    {
        return $this->hasOne(HoSoNguoiDung::class, 'nguoi_dung_id');
    }
    
    public function vai_tro()
    {
        return $this->belongsTo(VaiTro::class, 'vai_tro_id');
    }
    
    public function phong_ban()
    {
        return $this->belongsTo(PhongBan::class, 'phong_ban_id');
    }
    
    public function chuc_vu()
    {
        return $this->belongsTo(ChucVu::class, 'chuc_vu_id');
    }
    
    public function chi_nhanh()
    {
        return $this->belongsTo(ChiNhanhCongTy::class, 'branch_id');
    }
    
    public function cham_congs()
    {
        return $this->hasMany(ChamCong::class, 'nguoi_dung_id');
    }
    
    public function don_xin_nghis()
    {
        return $this->hasMany(DonXinNghi::class, 'nguoi_dung_id');
    }
    
    public function hop_dongs()
    {
        return $this->hasMany(HopDongLaoDong::class, 'nguoi_dung_id');
    }
    
    // Accessor lấy họ tên đầy đủ
    public function getHoTenAttribute()
    {
        if ($this->ho_so) {
            return $this->ho_so->ho . ' ' . $this->ho_so->ten;
        }
        return $this->ten_dang_nhap;
    }
}