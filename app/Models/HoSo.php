<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class HoSo extends Model
{
    protected $table = 'ho_so_nguoi_dung';

    protected $fillable = [
        'nguoi_dung_id',
        'ma_nhan_vien',
        'ho',
        'ten',
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
        'chu_tai_khoan',
        'so_tai_khoan',
        'ten_ngan_hang',
        'chi_nhanh_ngan_hang',
        'so_bhxh',
        'ma_so_thue',
        'noi_dang_ky_kcb',
    ];

    protected $casts = [
        'ngay_sinh' => 'date',
    ];

    // ========== CÁC QUAN HỆ ==========

    public function nguoi_dung(): BelongsTo
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_dung_id');
    }

    public function cv(): HasOne
    {
        return $this->hasOne(TaiLieu::class, 'nguoi_dung_id', 'nguoi_dung_id')
            ->where('loai_tai_lieu', 'cv')
            ->latest();
    }

    public function hop_dong(): HasMany
    {
        return $this->hasMany(HopDongLaoDong::class, 'nguoi_dung_id', 'nguoi_dung_id');
    }

    public function lich_su_luong(): HasMany
    {
        return $this->hasMany(LuongNhanVien::class, 'nguoi_dung_id', 'nguoi_dung_id')
            ->orderBy('luong_nam', 'desc')
            ->orderBy('luong_thang', 'desc');
    }

    // ⭐ THÊM 3 QUAN HỆ NÀY (QUAN TRỌNG)
    public function ky_nang(): HasMany
    {
        return $this->hasMany(KyNangNhanVien::class, 'ho_so_id');
    }

    public function chung_chi(): HasMany
    {
        return $this->hasMany(ChungChiNhanVien::class, 'ho_so_id');
    }

    public function du_an(): HasMany
    {
        return $this->hasMany(DuAnNhanVien::class, 'ho_so_id');
    }

    // ========== ACCESSOR ==========

    public function getHoTenAttribute(): string
    {
        return trim($this->ho . ' ' . $this->ten);
    }

    public function getTrangThaiAttribute()
    {
        return $this->nguoi_dung?->trang_thai;
    }

    public function getTuoiAttribute(): ?int
    {
        return $this->ngay_sinh?->age;
    }

    public function getTinhTrangHonNhanTextAttribute()
    {
        $map = [
            'doc_than' => 'Độc thân',
            'da_ket_hon' => 'Đã kết hôn',
            'ly_hon' => 'Ly hôn',
            'goa' => 'Góa',
        ];
        return $map[$this->tinh_trang_hon_nhan] ?? $this->tinh_trang_hon_nhan ?? '---';
    }

    public function getGioiTinhTextAttribute()
    {
        $map = [
            'nam' => 'Nam',
            'nu' => 'Nữ',
            'khac' => 'Khác',
        ];
        return $map[$this->gioi_tinh] ?? $this->gioi_tinh ?? '---';
    }

    public function getThamNienAttribute(): string
    {
        // Lấy ngày vào làm từ bảng nguoi_dung
        $ngayVaoLam = $this->nguoi_dung?->created_at;

        // Nếu chưa có ngày vào làm, dùng ngày hiện tại
        if (!$ngayVaoLam) {
            return 'Chưa xác định';
        }

        $now = now();
        $diff = $ngayVaoLam->diff($now);

        $nam = $diff->y;
        $thang = $diff->m;
        $ngay = $diff->d;

        if ($nam > 0 && $thang > 0) {
            return $nam . ' năm ' . $thang . ' tháng';
        } elseif ($nam > 0 && $thang == 0) {
            return $nam . ' năm';
        } elseif ($nam == 0 && $thang > 0) {
            return $thang . ' tháng';
        } elseif ($nam == 0 && $thang == 0 && $ngay > 0) {
            return $ngay . ' ngày';
        } else {
            return 'Mới vào làm';
        }
    }

    public function nguoiPhuThuoc(): HasMany
    {
        return $this->hasMany(NguoiPhuThuoc::class, 'ho_so_id');
    }

    public function dao_tao(): HasMany
    {
        return $this->hasMany(DaoTaoNhanVien::class, 'ho_so_id')->orderBy('ngay_bat_dau', 'desc');
    }

    public function khen_thuong_ky_luat(): HasMany
    {
        return $this->hasMany(KhenThuongKyLuatNhanVien::class, 'ho_so_id')->orderBy('ngay', 'desc');
    }
}
