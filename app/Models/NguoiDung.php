<?php
// app/Models/NguoiDung.php

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

    // =============================================
    // JWT
    // =============================================
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [
            'vai_tro_id' => $this->vai_tro_id,
            'vai_tro' => $this->vaiTro->ten_hien_thi ?? null,
            'phong_ban_id' => $this->phong_ban_id,
            'chuc_vu_id' => $this->chuc_vu_id,
        ];
    }

    // =============================================
    // QUAN HỆ - Cách viết chuẩn (không dấu gạch dưới)
    // =============================================

    /**
     * Quan hệ với bảng vai_tro
     */
    public function vaiTro()
    {
        return $this->belongsTo(VaiTro::class, 'vai_tro_id');
    }

    /**
     * Quan hệ với bảng phong_ban
     */
    public function phongBan()
    {
        return $this->belongsTo(PhongBan::class, 'phong_ban_id');
    }

    /**
     * Quan hệ với bảng chuc_vu
     */
    public function chucVu()
    {
        return $this->belongsTo(ChucVu::class, 'chuc_vu_id');
    }

    /**
     * Quan hệ với bảng chi_nhanh_cong_ty
     */
    public function chiNhanh()
    {
        return $this->belongsTo(ChiNhanhCongTy::class, 'branch_id');
    }

    /**
     * Quan hệ với bảng ho_so_nguoi_dung
     */
    public function hoSo()
    {
        return $this->hasOne(HoSoNguoiDung::class, 'nguoi_dung_id');
    }

    /**
     * Quan hệ với bảng cham_cong
     */
    public function chamCongs()
    {
        return $this->hasMany(ChamCong::class, 'nguoi_dung_id');
    }

    /**
     * Quan hệ với bảng don_xin_nghi
     */
    public function donXinNghis()
    {
        return $this->hasMany(DonXinNghi::class, 'nguoi_dung_id');
    }

    /**
     * Quan hệ với bảng hop_dong_lao_dong
     */
    public function hopDongs()
    {
        return $this->hasMany(HopDongLaoDong::class, 'nguoi_dung_id');
    }

    /**
     * Lấy hợp đồng hiện tại
     */
    public function hopDongHienTai()
    {
        return $this->hasOne(HopDongLaoDong::class, 'nguoi_dung_id')
            ->where('trang_thai_hop_dong', 'hieu_luc')
            ->where(function ($q) {
                $q->whereNull('ngay_ket_thuc')
                    ->orWhere('ngay_ket_thuc', '>=', now());
            })
            ->latest('ngay_bat_dau');
    }

    /**
     * Quan hệ với bảng luong_nhan_vien
     */
    public function luongNhanViens()
    {
        return $this->hasMany(LuongNhanVien::class, 'nguoi_dung_id');
    }

    /**
     * Quan hệ với bảng tai_lieu
     */
    public function taiLieus()
    {
        return $this->hasMany(TaiLieu::class, 'nguoi_dung_id');
    }

    /**
     * Quan hệ với bảng phu_cap_nhan_vien
     */
    public function phuCapNhanViens()
    {
        return $this->hasMany(PhuCapNhanVien::class, 'nguoi_dung_id');
    }

    /**
     * Quan hệ nhiều-nhiều với bảng vai_tro (qua bảng trung gian nguoi_dung_vai_tro)
     */
    public function vaiTros()
    {
        return $this->belongsToMany(VaiTro::class, 'nguoi_dung_vai_tro', 'nguoi_dung_id', 'vai_tro_id')
            ->withTimestamps();
    }

    // =============================================
    // ALIAS (Tương thích ngược với code cũ)
    // =============================================

    /**
     * Alias cho hoSo()
     */
    public function ho_so()
    {
        return $this->hoSo();
    }

    /**
     * Alias cho vaiTro()
     */
    public function vai_tro()
    {
        return $this->vaiTro();
    }

    /**
     * Alias cho phongBan()
     */
    public function phong_ban()
    {
        return $this->phongBan();
    }

    /**
     * Alias cho chucVu()
     */
    public function chuc_vu()
    {
        return $this->chucVu();
    }

    /**
     * Alias cho chiNhanh()
     */
    public function chi_nhanh()
    {
        return $this->chiNhanh();
    }

    /**
     * Alias cho chamCongs()
     */
    public function cham_congs()
    {
        return $this->chamCongs();
    }

    /**
     * Alias cho donXinNghis()
     */
    public function don_xin_nghis()
    {
        return $this->donXinNghis();
    }

    /**
     * Alias cho hopDongs()
     */
    public function hop_dongs()
    {
        return $this->hopDongs();
    }

    /**
     * Alias cho hopDongHienTai()
     */
    public function hop_dong_hien_tai()
    {
        return $this->hopDongHienTai();
    }

    /**
     * Alias cho luongNhanViens()
     */
    public function luong_nhan_viens()
    {
        return $this->luongNhanViens();
    }

    /**
     * Alias cho hopDongLaoDong
     */
    public function hopDongLaoDong()
    {
        return $this->hopDongs();
    }

    // =============================================
    // ACCESSOR
    // =============================================

    /**
     * Lấy họ tên đầy đủ
     */
    public function getHoTenAttribute()
    {
        if ($this->hoSo && $this->hoSo->ho) {
            return trim($this->hoSo->ho . ' ' . $this->hoSo->ten);
        }
        return $this->ten_dang_nhap;
    }

    /**
     * Alias cho hasPermission
     */
    public function canAccess($permissionName)
    {
        return $this->hasPermission($permissionName);
    }

    public function isAdmin()
    {
        return $this->vaiTros()->whereIn('name', ['admin', 'Super Admin'])->exists();
    }

    public function isHR()
    {
        return $this->vaiTros()->where('name', 'hr')->exists();
    }

    public function isTruongPhong()
    {
        return $this->vaiTros()->where('name', 'truong_phong')->exists();
    }

    public function isNhanVien()
    {
        return $this->vaiTros()->where('name', 'nhan_vien')->exists();
    }

    public function hasPermission($permissionName)
    {
        // Admin có tất cả quyền
        // if ($this->isAdmin()) {
        //     return true;
        // }

        // Lấy tất cả quyền của user
        $permissions = $this->vaiTros->flatMap(function ($role) {
            return $role->quyens->pluck('name');
        })->unique();

        return $permissions->contains($permissionName);
    }

    public function hasAnyPermission($permissionNames)
    {
        foreach ($permissionNames as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }
        return false;
    }

    public function hasAllPermissions($permissionNames)
    {
        foreach ($permissionNames as $permission) {
            if (!$this->hasPermission($permission)) {
                return false;
            }
        }
        return true;
    }
    // Thêm relationship
    public function lichSuLuong()
    {
        return $this->hasMany(LichSuLuong::class, 'nguoi_dung_id');
    }

    public function lichSuTangLuong()
    {
        return $this->hasMany(LichSuLuong::class, 'nguoi_dung_id')
            ->where('loai', 'tang_luong')
            ->where('trang_thai', 'da_duyet');
    }

    // Thêm method
    public function getLuongTaiNgay($ngay)
    {
        $lichSu = $this->lichSuLuong()
            ->where('trang_thai', 'da_duyet')
            ->where('ngay_ap_dung', '<=', $ngay)
            ->orderBy('ngay_ap_dung', 'desc')
            ->first();

        if ($lichSu) {
            return $lichSu->luong_moi;
        }

        $hopDong = $this->hopDongLaoDong()
            ->where('trang_thai_hop_dong', 'hieu_luc')
            ->where('ngay_bat_dau', '<=', $ngay)
            ->orderBy('ngay_bat_dau', 'desc')
            ->first();

        return $hopDong ? $hopDong->luong_co_ban : 0;
    }

    public function getLuongHienTaiAttribute()
    {
        $hopDong = $this->hopDongLaoDong()
            ->where('trang_thai_hop_dong', 'hieu_luc')
            ->first();

        if ($hopDong) {
            // Kiểm tra lịch sử lương gần nhất
            $lichSu = $this->lichSuLuong()
                ->where('trang_thai', 'da_duyet')
                ->orderBy('ngay_ap_dung', 'desc')
                ->first();

            return $lichSu ? $lichSu->luong_moi : $hopDong->luong_co_ban;
        }

        return 0;
    }
}
