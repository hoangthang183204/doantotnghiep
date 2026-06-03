<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class NguoiDung extends Authenticatable implements JWTSubject  // Đổi tên class
{
    use Notifiable;

    protected $table = 'nguoi_dung';
    
    protected $primaryKey = 'id';
    
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
        'lan_dang_nhap_cuoi',
        'ip_dang_nhap_cuoi'
    ];
    
    protected $hidden = [
        'password',
        'remember_token',
    ];
    
    protected $casts = [
        'trang_thai' => 'integer',
        'da_hoan_thanh_ho_so' => 'integer',
        'dang_nhap_lan_dau' => 'integer',
        'lan_dang_nhap_cuoi' => 'datetime',
    ];
    
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    
    public function getJWTCustomClaims()
    {
        return [
            'vai_tro_id' => $this->vai_tro_id,
            'vai_tro' => $this->vai_tro->ten_hien_thi ?? 'user',
            'ma_nhan_vien' => $this->ho_so->ma_nhan_vien ?? null
        ];
    }
    
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
    
    public function getHoTenAttribute()
    {
        if ($this->ho_so) {
            return $this->ho_so->ho . ' ' . $this->ho_so->ten;
        }
        return $this->ten_dang_nhap;
    }
}