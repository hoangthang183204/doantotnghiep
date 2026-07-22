<?php

namespace App\Models;

use App\Services\TinhLuongService;
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
        'luong_theo_cong',
        'tong_phu_cap',
        'tien_tang_ca',
        'tong_khau_tru',
        'tong_luong',
        'luong_thuc_nhan',
        'so_ngay_cong',
        'so_ngay_cong_chuan',
        'gio_tang_ca',
        'cong_tang_ca',
        'ngay_nghi_phep',
        'ngay_nghi_khong_phep',
        'ngay_le',
        'thue_thu_nhap_ca_nhan',
        'ghi_chu',

        // Căn cứ khấu trừ (snapshot tại thời điểm chốt lương)
        'phu_cap_chiu_thue',
        'bhxh',
        'bhyt',
        'bhtn',
        'tong_bao_hiem',
        'so_nguoi_phu_thuoc',
        'giam_tru_ban_than',
        'giam_tru_nguoi_phu_thuoc',
        'giam_tru_gia_canh',
        'thu_nhap_chiu_thue',
        'thu_nhap_tinh_thue',
        'tong_khau_tru_khac',
    ];

    protected $casts = [
        'luong_thang'           => 'integer',
        'luong_nam'             => 'integer',
        'luong_co_ban'          => 'decimal:2',
        'luong_theo_cong'       => 'decimal:2',
        'tong_phu_cap'          => 'decimal:2',
        'tien_tang_ca'          => 'decimal:2',
        'tong_khau_tru'         => 'decimal:2',
        'tong_luong'            => 'decimal:2',
        'luong_thuc_nhan'       => 'decimal:2',
        'so_ngay_cong'          => 'decimal:2',
        'so_ngay_cong_chuan'    => 'decimal:2',
        'gio_tang_ca'           => 'decimal:2',
        'cong_tang_ca'          => 'decimal:2',
        'ngay_nghi_phep'        => 'decimal:2',
        'ngay_nghi_khong_phep'  => 'decimal:2',
        'ngay_le'               => 'decimal:2',
        'thue_thu_nhap_ca_nhan' => 'decimal:2',

        'phu_cap_chiu_thue'        => 'decimal:2',
        'bhxh'                     => 'decimal:2',
        'bhyt'                     => 'decimal:2',
        'bhtn'                     => 'decimal:2',
        'tong_bao_hiem'            => 'decimal:2',
        'so_nguoi_phu_thuoc'       => 'integer',
        'giam_tru_ban_than'        => 'decimal:2',
        'giam_tru_nguoi_phu_thuoc' => 'decimal:2',
        'giam_tru_gia_canh'        => 'decimal:2',
        'thu_nhap_chiu_thue'       => 'decimal:2',
        'thu_nhap_tinh_thue'       => 'decimal:2',
        'tong_khau_tru_khac'       => 'decimal:2',
    ];

    // =====================================================================
    // Relationships
    // =====================================================================

    public function bangLuong()
    {
        return $this->belongsTo(BangLuong::class, 'bang_luong_id');
    }

    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_dung_id');
    }

    public function khauTrus()
    {
        return $this->hasMany(KhauTruLuong::class, 'luong_nhan_vien_id');
    }

    /** Chi tiết phụ cấp đã áp dụng cho dòng lương này */
    public function phuCapLuongs()
    {
        return $this->hasMany(PhuCapLuong::class, 'luong_nhan_vien_id');
    }

    /** Chi tiết khấu trừ (BHXH, BHYT, BHTN, thuế...) */
    public function khauTruLuongs()
    {
        return $this->hasMany(KhauTruLuong::class, 'luong_nhan_vien_id');
    }

    public function hoSo()
    {
        return $this->hasOne(HoSo::class, 'nguoi_dung_id', 'nguoi_dung_id');
    }

    // =====================================================================
    // Accessors hỗ trợ hiển thị công thức
    // =====================================================================

    /** Đơn giá 1 ngày công = lương cơ bản / số ngày công chuẩn */
    public function getLuongMotNgayAttribute(): float
    {
        $chuan = (float) $this->so_ngay_cong_chuan ?: 26;
        return $chuan > 0 ? round((float) $this->luong_co_ban / $chuan, 2) : 0;
    }

    /** Đơn giá 1 giờ công = lương 1 ngày / 8 */
    public function getLuongMotGioAttribute(): float
    {
        return round($this->luong_mot_ngay / 8, 2);
    }
    public function yeuCauXemXet()
{
    return $this->hasMany(YeuCauXemXetLuong::class, 'luong_nhan_vien_id');
}

    /**
     * Diễn giải toàn bộ phần khấu trừ: trừ khoản gì, căn cứ trên số nào,
     * theo tỷ lệ / mức giảm trừ nào và trừ vào đâu.
     *
     * Ưu tiên dùng snapshot đã lưu khi chốt lương; các phiếu lương cũ (chốt
     * trước khi có các cột này) được tính bù từ dữ liệu sẵn có để không vỡ giao diện.
     */
    public function dienGiai(): array
    {
        $luongCoBan = (float) $this->luong_co_ban;
        $coSnapshot = (float) $this->tong_bao_hiem > 0 || (float) $this->giam_tru_gia_canh > 0;

        // --- Bảo hiểm bắt buộc: trừ trên LƯƠNG CƠ BẢN (không phải tổng lương) ---
        $bhxh = $coSnapshot ? (float) $this->bhxh : round($luongCoBan * TinhLuongService::TY_LE_BHXH, 2);
        $bhyt = $coSnapshot ? (float) $this->bhyt : round($luongCoBan * TinhLuongService::TY_LE_BHYT, 2);
        $bhtn = $coSnapshot ? (float) $this->bhtn : round($luongCoBan * TinhLuongService::TY_LE_BHTN, 2);
        $tongBaoHiem = $coSnapshot && (float) $this->tong_bao_hiem > 0
            ? (float) $this->tong_bao_hiem
            : round($bhxh + $bhyt + $bhtn, 2);

        // --- Giảm trừ gia cảnh ---
        $soNPT = (int) $this->so_nguoi_phu_thuoc;
        $giamTruBanThan = $coSnapshot && (float) $this->giam_tru_ban_than > 0
            ? (float) $this->giam_tru_ban_than
            : TinhLuongService::GIAM_TRU_BAN_THAN;
        $giamTruNPT = $coSnapshot
            ? (float) $this->giam_tru_nguoi_phu_thuoc
            : round($soNPT * TinhLuongService::GIAM_TRU_NGUOI_PHU_THUOC, 2);
        $mucMoiNPT = $soNPT > 0 ? round($giamTruNPT / $soNPT, 2) : TinhLuongService::GIAM_TRU_NGUOI_PHU_THUOC;
        $giamTruGiaCanh = $coSnapshot && (float) $this->giam_tru_gia_canh > 0
            ? (float) $this->giam_tru_gia_canh
            : round($giamTruBanThan + $giamTruNPT, 2);

        // --- Căn cứ tính thuế ---
        $phuCapChiuThue = $coSnapshot ? (float) $this->phu_cap_chiu_thue : (float) $this->tong_phu_cap;
        $thuNhapChiuThue = (float) $this->thu_nhap_chiu_thue > 0
            ? (float) $this->thu_nhap_chiu_thue
            : round((float) $this->luong_theo_cong + $phuCapChiuThue + (float) $this->tien_tang_ca, 2);
        $thuNhapTinhThue = (float) $this->thu_nhap_tinh_thue > 0
            ? (float) $this->thu_nhap_tinh_thue
            : max(0, round($thuNhapChiuThue - $tongBaoHiem - $giamTruGiaCanh, 2));

        // --- Khấu trừ khác (tạm ứng, phạt, bồi thường...) ---
        $khoanKhac = $this->khauTrus->where('loai_khau_tru', 'khau_tru_khac');
        $tongKhauTruKhac = (float) $this->tong_khau_tru_khac > 0
            ? (float) $this->tong_khau_tru_khac
            : (float) $khoanKhac->sum('so_tien');

        return [
            'luong_co_ban'      => $luongCoBan,
            'ty_le_bhxh'        => TinhLuongService::TY_LE_BHXH,
            'ty_le_bhyt'        => TinhLuongService::TY_LE_BHYT,
            'ty_le_bhtn'        => TinhLuongService::TY_LE_BHTN,
            'bhxh'              => $bhxh,
            'bhyt'              => $bhyt,
            'bhtn'              => $bhtn,
            'tong_bao_hiem'     => $tongBaoHiem,

            'phu_cap_chiu_thue' => $phuCapChiuThue,
            'thu_nhap_chiu_thue' => $thuNhapChiuThue,

            'so_nguoi_phu_thuoc'       => $soNPT,
            'muc_giam_tru_moi_npt'     => $mucMoiNPT,
            'giam_tru_ban_than'        => $giamTruBanThan,
            'giam_tru_nguoi_phu_thuoc' => $giamTruNPT,
            'giam_tru_gia_canh'        => $giamTruGiaCanh,

            'thu_nhap_tinh_thue' => $thuNhapTinhThue,
            'chi_tiet_bac_thue'  => TinhLuongService::chiTietBacThue($thuNhapTinhThue),
            'thue_tncn'          => (float) $this->thue_thu_nhap_ca_nhan,

            'khau_tru_khac'      => $khoanKhac,
            'tong_khau_tru_khac' => $tongKhauTruKhac,

            'tong_khau_tru'      => (float) $this->tong_khau_tru,
            'tong_luong'         => (float) $this->tong_luong,
            'luong_thuc_nhan'    => (float) $this->luong_thuc_nhan,
        ];
    }

    /**
     * Người phụ thuộc được tính giảm trừ trong đúng kỳ lương này
     * (cùng điều kiện hiệu lực mà TinhLuongService đã dùng khi chốt lương).
     *
     * @return \Illuminate\Support\Collection<int, NguoiPhuThuoc>
     */
    public function nguoiPhuThuocTrongKy()
    {
        if (!$this->luong_thang || !$this->luong_nam) {
            return collect();
        }

        $dauThang  = \Carbon\Carbon::create($this->luong_nam, $this->luong_thang, 1)->startOfMonth();
        $cuoiThang = (clone $dauThang)->endOfMonth();

        return $this->nguoiPhuThuoc()
            ->where(function ($q) use ($cuoiThang) {
                $q->whereNull('nguoi_phu_thuoc.ngay_bat_dau')
                    ->orWhereDate('nguoi_phu_thuoc.ngay_bat_dau', '<=', $cuoiThang->toDateString());
            })
            ->where(function ($q) use ($dauThang) {
                $q->whereNull('nguoi_phu_thuoc.ngay_ket_thuc')
                    ->orWhereDate('nguoi_phu_thuoc.ngay_ket_thuc', '>=', $dauThang->toDateString());
            })
            ->orderBy('nguoi_phu_thuoc.ngay_bat_dau')
            ->get();
    }

    /** Danh sách người phụ thuộc đang khai báo trên hồ sơ (để đối chiếu) */
    public function nguoiPhuThuoc()
    {
        return $this->hasManyThrough(
            NguoiPhuThuoc::class,
            HoSo::class,
            'nguoi_dung_id',   // FK trên ho_so_nguoi_dung
            'ho_so_id',        // FK trên nguoi_phu_thuoc
            'nguoi_dung_id',   // key local trên luong_nhan_vien
            'id'               // key local trên ho_so_nguoi_dung
        );
    }
}
