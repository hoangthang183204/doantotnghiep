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
        'ip_dang_nhap_cuoi',
        'luong_co_ban',
        'luong_theo_gio',
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
        'luong_co_ban' => 'float',
        'luong_theo_gio' => 'float',
    ];

    protected $appends = [
        'ho_ten',
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
    // QUAN HỆ
    // =============================================

    public function vaiTro()
    {
        return $this->belongsTo(VaiTro::class, 'vai_tro_id');
    }

    public function phongBan()
    {
        return $this->belongsTo(PhongBan::class, 'phong_ban_id');
    }

    public function chucVu()
    {
        return $this->belongsTo(ChucVu::class, 'chuc_vu_id');
    }

    public function chiNhanh()
    {
        return $this->belongsTo(ChiNhanhCongTy::class, 'branch_id');
    }

    public function hoSo()
    {
        return $this->hasOne(HoSoNguoiDung::class, 'nguoi_dung_id');
    }

    public function chamCongs()
    {
        return $this->hasMany(ChamCong::class, 'nguoi_dung_id');
    }

    public function donXinNghis()
    {
        return $this->hasMany(DonXinNghi::class, 'nguoi_dung_id');
    }

    public function hopDongs()
    {
        return $this->hasMany(HopDongLaoDong::class, 'nguoi_dung_id');
    }

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

    public function luongNhanViens()
    {
        return $this->hasMany(LuongNhanVien::class, 'nguoi_dung_id');
    }

    public function luong()
    {
        return $this->hasMany(Luong::class, 'nguoi_dung_id');
    }

    public function taiLieus()
    {
        return $this->hasMany(TaiLieu::class, 'nguoi_dung_id');
    }

    public function phuCapNhanViens()
    {
        return $this->hasMany(PhuCapNhanVien::class, 'nguoi_dung_id');
    }

    public function vaiTros()
    {
        return $this->belongsToMany(VaiTro::class, 'nguoi_dung_vai_tro', 'nguoi_dung_id', 'vai_tro_id')
            ->withTimestamps();
    }

    public function lichSuLuong()
    {
        return $this->hasMany(LichSuLuong::class, 'nguoi_dung_id');
    }

    public function dangKyTangCa()
    {
        return $this->hasMany(DangKyTangCa::class, 'nguoi_dung_id');
    }

    // =============================================
    // ALIAS (Tương thích ngược với code cũ)
    // =============================================

    public function ho_so()
    {
        return $this->hoSo();
    }

    public function vai_tro()
    {
        return $this->vaiTro();
    }

    public function phong_ban()
    {
        return $this->phongBan();
    }

    public function chuc_vu()
    {
        return $this->chucVu();
    }

    public function chi_nhanh()
    {
        return $this->chiNhanh();
    }

    public function cham_congs()
    {
        return $this->chamCongs();
    }

    public function don_xin_nghis()
    {
        return $this->donXinNghis();
    }

    public function hop_dongs()
    {
        return $this->hopDongs();
    }

    public function hop_dong_hien_tai()
    {
        return $this->hopDongHienTai();
    }

    public function luong_nhan_viens()
    {
        return $this->luongNhanViens();
    }

    public function hopDongLaoDong()
    {
        return $this->hopDongs();
    }

    // =============================================
    // ACCESSOR - HỌ TÊN
    // =============================================

    public function getHoTenAttribute()
    {
        if ($this->hoSo && $this->hoSo->ho) {
            return trim($this->hoSo->ho . ' ' . $this->hoSo->ten);
        }
        return $this->ten_dang_nhap;
    }

    // =============================================
    // ACCESSOR - LƯƠNG
    // =============================================

    /**
     * Lấy lương cơ bản hiện tại
     * Ưu tiên: bảng nguoi_dung -> bảng luong -> hợp đồng -> lịch sử lương
     */
    public function getLuongCoBanHienTai()
    {
        // 1. Từ bảng nguoi_dung
        if ($this->luong_co_ban && $this->luong_co_ban > 0) {
            return (float) $this->luong_co_ban;
        }

        // 2. Từ bảng luong (mới nhất)
        $luong = $this->luong()->orderBy('created_at', 'desc')->first();
        if ($luong && $luong->luong_co_ban > 0) {
            return (float) $luong->luong_co_ban;
        }

        // 3. Từ bảng hop_dong_lao_dong (đang hiệu lực)
        $hopDong = $this->hopDongLaoDong()
            ->where('trang_thai_hop_dong', 'hieu_luc')
            ->orderBy('id', 'desc')
            ->first();
        if ($hopDong && $hopDong->luong_co_ban > 0) {
            return (float) $hopDong->luong_co_ban;
        }

        // 4. Từ lịch sử lương
        $lichSu = $this->lichSuLuong()
            ->where('trang_thai', 'da_duyet')
            ->orderBy('ngay_ap_dung', 'desc')
            ->first();
        if ($lichSu && $lichSu->luong_moi > 0) {
            return (float) $lichSu->luong_moi;
        }

        return 0;
    }

    /**
     * Lấy lương theo giờ
     * Mặc định: 1 tháng 26 ngày, 1 ngày 8 giờ
     */
    public function getLuongTheoGio()
    {
        // Nếu đã có giá trị trong database hoặc attribute
        if (isset($this->attributes['luong_theo_gio']) && $this->attributes['luong_theo_gio'] > 0) {
            return (float) $this->attributes['luong_theo_gio'];
        }

        // Tính từ lương cơ bản - ưu tiên attribute được set trước
        $luongCoBan = $this->luong_co_ban ?? $this->getLuongCoBanHienTai();

        if ($luongCoBan > 0) {
            $workingDaysPerMonth = 26;
            $hoursPerDay = 8;
            return round($luongCoBan / ($workingDaysPerMonth * $hoursPerDay), 0);
        }

        return 0;
    }

    /**
     * Lấy lương hiện tại
     */
    public function getLuongHienTaiAttribute()
    {
        $hopDong = $this->hopDongLaoDong()
            ->where('trang_thai_hop_dong', 'hieu_luc')
            ->first();

        if ($hopDong) {
            $lichSu = $this->lichSuLuong()
                ->where('trang_thai', 'da_duyet')
                ->orderBy('ngay_ap_dung', 'desc')
                ->first();

            return $lichSu ? (float) $lichSu->luong_moi : (float) $hopDong->luong_co_ban;
        }

        return 0;
    }

    /**
     * Lấy lương tại một thời điểm cụ thể
     */
    public function getLuongTaiNgay($ngay)
    {
        $lichSu = $this->lichSuLuong()
            ->where('trang_thai', 'da_duyet')
            ->where('ngay_ap_dung', '<=', $ngay)
            ->orderBy('ngay_ap_dung', 'desc')
            ->first();

        if ($lichSu) {
            return (float) $lichSu->luong_moi;
        }

        $hopDong = $this->hopDongLaoDong()
            ->where('trang_thai_hop_dong', 'hieu_luc')
            ->where('ngay_bat_dau', '<=', $ngay)
            ->orderBy('ngay_bat_dau', 'desc')
            ->first();

        return $hopDong ? (float) $hopDong->luong_co_ban : 0;
    }

    /**
     * Cập nhật lương theo giờ từ lương cơ bản
     */
    public function updateLuongTheoGio()
    {
        $luongCoBan = $this->getLuongCoBanHienTai();
        if ($luongCoBan > 0) {
            $workingDaysPerMonth = 26;
            $hoursPerDay = 8;
            $this->luong_theo_gio = round($luongCoBan / ($workingDaysPerMonth * $hoursPerDay), 0);
            $this->save();
            return true;
        }
        return false;
    }

    // =============================================
    // PERMISSION CHECKS
    // =============================================

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

    // =============================================
    // SCOPES
    // =============================================

    public function scopeDangLam($query)
    {
        return $query->where('trang_thai_cong_viec', 'dang_lam')
            ->where('trang_thai', 1);
    }

    public function scopeCoLuong($query)
    {
        return $query->whereNotNull('luong_co_ban')
            ->where('luong_co_ban', '>', 0);
    }

    public function scopeCoLuongTheoGio($query)
    {
        return $query->whereNotNull('luong_theo_gio')
            ->where('luong_theo_gio', '>', 0);
    }

    // =============================================
    // HELPER METHODS
    // =============================================

    /**
     * Kiểm tra xem user có lương không
     */
    public function hasSalary()
    {
        return $this->getLuongCoBanHienTai() > 0;
    }

    /**
     * Kiểm tra xem user có lương theo giờ không
     */
    public function hasHourlyRate()
    {
        return $this->getLuongTheoGio() > 0;
    }

    /**
     * Lấy thông tin lương đầy đủ
     */
    public function getSalaryInfo()
    {
        return [
            'luong_co_ban' => $this->getLuongCoBanHienTai(),
            'luong_theo_gio' => $this->getLuongTheoGio(),
            'luong_hien_tai' => $this->getLuongHienTaiAttribute,
            'has_salary' => $this->hasSalary(),
            'has_hourly_rate' => $this->hasHourlyRate(),
        ];
    }
}
