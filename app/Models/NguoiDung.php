<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class NguoiDung extends Authenticatable implements JWTSubject
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
            'vai_tro' => $this->vai_tro->ten_hien_thi ?? null,
            'phong_ban_id' => $this->phong_ban_id,
            'chuc_vu_id' => $this->chuc_vu_id,
        ];
    }

    // Relationships
    public function hoSo()
    {
        return $this->hasOne(
            HoSoNguoiDung::class,
            'nguoi_dung_id'
        );
    }

    public function ho_so()
    {
        return $this->hoSo();
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

    // THÊM RELATIONSHIP NÀY
    public function hop_dongs()
    {
        return $this->hasMany(HopDongLaoDong::class, 'nguoi_dung_id');
    }

    // Lấy hợp đồng hiện tại
    public function hop_dong_hien_tai()
    {
        return $this->hasOne(HopDongLaoDong::class, 'nguoi_dung_id')
            ->where('trang_thai_hop_dong', 'da_ky')
            ->where(function ($q) {
                $q->whereNull('ngay_ket_thuc')
                    ->orWhere('ngay_ket_thuc', '>=', now());
            })
            ->latest('ngay_bat_dau');
    }

    public function luong_nhan_viens()
    {
        return $this->hasMany(LuongNhanVien::class, 'nguoi_dung_id');
    }

    // Accessor lấy họ tên đầy đủ

    public function getHoTenAttribute()
    {
        if ($this->hoSo && $this->hoSo->ho) {
            return trim($this->hoSo->ho . ' ' . $this->hoSo->ten);
        }

        return $this->ten_dang_nhap;
    }

    public function hasPermission($permissionName)
    {
        // Kiểm tra qua vai trò
        foreach ($this->vaiTros as $vaiTro) {
            if ($vaiTro->hasPermission($permissionName)) {
                return true;
            }
        }
        return false;
    }

    public function canAccess($permissionName)
    {
        return $this->hasPermission($permissionName);
    }

    public function vaiTros()
    {
        return $this->belongsToMany(VaiTro::class, 'nguoi_dung_vai_tro', 'nguoi_dung_id', 'vai_tro_id')
            ->withTimestamps();
    }
    
    
}
