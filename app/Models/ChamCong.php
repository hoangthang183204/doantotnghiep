<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ChamCong extends Model
{
    use HasFactory;

    protected $table = 'cham_cong';

    protected $fillable = [
        'nguoi_dung_id',
        'ngay_cham_cong',
        'gio_vao',
        'gio_ra',
        'so_gio_lam',
        'so_cong',
        'gio_tang_ca',
        'phut_di_muon',
        'phut_ve_som',
        'trang_thai',
        'dia_chi_ip',
        'ten_wifi',
        'dia_chi_mac',
        'ten_thiet_bi',
        'phuong_thuc_cham_cong',
        'ghi_chu',
        'nguoi_phe_duyet_id',
        'trang_thai_duyet',
        'ghi_chu_duyet',
        'thoi_gian_phe_duyet',
    ];

    protected $casts = [
        'ngay_cham_cong'      => 'date',
        'thoi_gian_phe_duyet' => 'datetime',
        'trang_thai_duyet'    => 'integer',
        'so_gio_lam'          => 'float',
        'so_cong'             => 'float',
        'gio_tang_ca'         => 'float',
        'phut_di_muon'        => 'integer',
        'phut_ve_som'         => 'integer',
    ];

    // =========================================================================
    // CONSTANTS - Trạng thái duyệt
    // =========================================================================
    const TRANG_THAI_DUYET_CHUA_DUYET = 0;
    const TRANG_THAI_DUYET_DA_DUYET = 1;
    const TRANG_THAI_DUYET_TU_CHOI = 2;
    const TRANG_THAI_DUYET_DANG_DUYET = 3;

    const TRANG_THAI_DEN_SOM = 'den_som';
    const TRANG_THAI_DUNG_GIO = 'dung_gio';
    const TRANG_THAI_DI_MUON = 'di_muon';
    const TRANG_THAI_VE_SOM = 've_som';
    const TRANG_THAI_KHONG_CHAM_CONG = 'khong_cham_cong';
    const TRANG_THAI_NGHI_PHEP = 'nghi_phep';
    const TRANG_THAI_VANG_MAT = 'vang_mat';

    // =========================================================================
    // Relationships
    // =========================================================================

    public function nguoi_dung()
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_dung_id');
    }

    public function nguoi_phe_duyet()
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_phe_duyet_id');
    }


    public function getTrangThaiTextAttribute(): string
    {
        $statuses = [
            self::TRANG_THAI_DEN_SOM => 'Đến sớm',
            self::TRANG_THAI_DUNG_GIO => 'Đúng giờ',
            self::TRANG_THAI_DI_MUON => 'Đi muộn',
            self::TRANG_THAI_VE_SOM => 'Về sớm',
            self::TRANG_THAI_VANG_MAT => 'Vắng mặt',
            self::TRANG_THAI_NGHI_PHEP => 'Nghỉ phép',
            self::TRANG_THAI_KHONG_CHAM_CONG => 'Không chấm công',
        ];

        return $statuses[$this->trang_thai] ?? $this->trang_thai;
    }

    // Cập nhật getTrangThaiBadgeAttribute
    public function getTrangThaiBadgeAttribute(): string
    {
        $badges = [
            self::TRANG_THAI_DEN_SOM => 'info',
            self::TRANG_THAI_DUNG_GIO => 'success',
            self::TRANG_THAI_DI_MUON => 'warning',
            self::TRANG_THAI_VE_SOM => 'warning',
            self::TRANG_THAI_VANG_MAT => 'danger',
            self::TRANG_THAI_NGHI_PHEP => 'info',
            self::TRANG_THAI_KHONG_CHAM_CONG => 'secondary',
        ];

        return $badges[$this->trang_thai] ?? 'secondary';
    }


    public function getTrangThaiDuyetTextAttribute(): string
    {
        $statuses = [
            self::TRANG_THAI_DUYET_CHUA_DUYET => 'Chưa duyệt',
            self::TRANG_THAI_DUYET_DA_DUYET => 'Đã duyệt',
            self::TRANG_THAI_DUYET_TU_CHOI => 'Từ chối',
            self::TRANG_THAI_DUYET_DANG_DUYET => 'Đang duyệt',
        ];

        return $statuses[$this->trang_thai_duyet] ?? 'Không xác định';
    }

    public function getTrangThaiDuyetBadgeAttribute(): string
    {
        $badges = [
            self::TRANG_THAI_DUYET_CHUA_DUYET => 'secondary',
            self::TRANG_THAI_DUYET_DA_DUYET => 'success',
            self::TRANG_THAI_DUYET_TU_CHOI => 'danger',
            self::TRANG_THAI_DUYET_DANG_DUYET => 'warning',
        ];

        return $badges[$this->trang_thai_duyet] ?? 'secondary';
    }


    public function getPhuongThucChamCongTextAttribute(): string
    {
        $methods = [
            'ip' => 'IP',
            'wifi' => 'WiFi',
            'mac' => 'MAC Address',
            'manual' => 'Thủ công',
            'qr' => 'QR Code',
            'face' => 'Nhận diện khuôn mặt',
            'finger' => 'Vân tay',
        ];

        return $methods[$this->phuong_thuc_cham_cong] ?? $this->phuong_thuc_cham_cong;
    }

    public function getPhuongThucChamCongIconAttribute(): string
    {
        $icons = [
            'ip' => 'fas fa-network-wired',
            'wifi' => 'fas fa-wifi',
            'mac' => 'fas fa-microchip',
            'manual' => 'fas fa-user-edit',
            'qr' => 'fas fa-qrcode',
            'face' => 'fas fa-user-circle',
            'finger' => 'fas fa-fingerprint',
        ];

        return $icons[$this->phuong_thuc_cham_cong] ?? 'fas fa-clock';
    }

    // =========================================================================
    // Scopes (Query Builder)
    // =========================================================================

    /**
     * Scope lọc theo ngày
     */
    public function scopeNgay($query, $ngay)
    {
        return $query->whereDate('ngay_cham_cong', $ngay);
    }

    /**
     * Scope lọc theo tháng
     */
    public function scopeThang($query, $thang, $nam = null)
    {
        $nam = $nam ?? Carbon::now()->year;
        return $query->whereMonth('ngay_cham_cong', $thang)
            ->whereYear('ngay_cham_cong', $nam);
    }

    /**
     * Scope lọc theo nhân viên
     */
    public function scopeCuaNhanVien($query, $nguoiDungId)
    {
        return $query->where('nguoi_dung_id', $nguoiDungId);
    }

    /**
     * Scope lọc theo trạng thái duyệt
     */
    public function scopeDaDuyet($query)
    {
        return $query->where('trang_thai_duyet', self::TRANG_THAI_DUYET_DA_DUYET);
    }

    public function scopeChoDuyet($query)
    {
        return $query->where('trang_thai_duyet', self::TRANG_THAI_DUYET_CHUA_DUYET);
    }

    public function scopeTuChoi($query)
    {
        return $query->where('trang_thai_duyet', self::TRANG_THAI_DUYET_TU_CHOI);
    }

    /**
     * Scope lọc theo phương thức chấm công
     */
    public function scopePhuongThuc($query, $method)
    {
        return $query->where('phuong_thuc_cham_cong', $method);
    }

    // =========================================================================
    // Business Logic Methods
    // =========================================================================

    /**
     * Tính số giờ làm từ giờ vào và giờ ra
     * Hỗ trợ ca qua đêm
     */
    public static function tinhSoGioLam(?string $gioVao, ?string $gioRa): float
    {
        if (!$gioVao || !$gioRa) {
            return 0.0;
        }

        [$hV, $mV] = array_map('intval', explode(':', $gioVao));
        [$hR, $mR] = array_map('intval', explode(':', $gioRa));

        $phutVao = $hV * 60 + $mV;
        $phutRa  = $hR * 60 + $mR;

        // Ca qua đêm
        if ($phutRa < $phutVao) {
            $phutRa += 24 * 60;
        }

        return round(($phutRa - $phutVao) / 60, 2);
    }

    /**
     * Tính số phút đi muộn so với giờ chuẩn vào
     */
    public static function tinhPhutDiMuon(?string $gioVao, string $gioChuanVao = '08:30'): int
    {
        if (!$gioVao) {
            return 0;
        }

        [$hV, $mV] = array_map('intval', explode(':', $gioVao));
        [$hC, $mC] = array_map('intval', explode(':', $gioChuanVao));

        $phutVao = $hV * 60 + $mV;
        $phutChuan = $hC * 60 + $mC;

        return max(0, $phutVao - $phutChuan);
    }

    /**
     * Tính số phút về sớm so với giờ chuẩn ra
     */
    public static function tinhPhutVeSom(?string $gioRa, string $gioChuanRa = '17:30'): int
    {
        if (!$gioRa) {
            return 0;
        }

        [$hR, $mR] = array_map('intval', explode(':', $gioRa));
        [$hC, $mC] = array_map('intval', explode(':', $gioChuanRa));

        $phutRa = $hR * 60 + $mR;
        $phutChuan = $hC * 60 + $mC;

        return max(0, $phutChuan - $phutRa);
    }

    /**
     * Xác định trạng thái từ giờ vào/ra
     */
    public static function xacDinhTrangThai(
        ?string $gioVao,
        ?string $gioRa,
        int $phutDiMuon,
        int $phutVeSom
    ): string {
        if (!$gioVao && !$gioRa) {
            return self::TRANG_THAI_KHONG_CHAM_CONG;
        }

        if ($phutDiMuon > 0 && $phutVeSom > 0) {
            return $phutDiMuon >= $phutVeSom ? self::TRANG_THAI_DI_MUON : self::TRANG_THAI_VE_SOM;
        }

        if ($phutDiMuon > 0) {
            return self::TRANG_THAI_DI_MUON;
        }

        if ($phutVeSom > 0) {
            return self::TRANG_THAI_VE_SOM;
        }

        return self::TRANG_THAI_DUNG_GIO;
    }

    /**
     * Cập nhật trạng thái chấm công dựa trên giờ vào/ra
     */
    public function capNhatTrangThai(): self
    {
        $gioVao = $this->gio_vao;
        $gioRa = $this->gio_ra;

        // Nếu không có giờ vào và giờ ra -> không chấm công
        if (!$gioVao && !$gioRa) {
            $this->trang_thai = self::TRANG_THAI_KHONG_CHAM_CONG;
            return $this;
        }

        // Kiểm tra đi muộn
        if ($gioVao && $this->kiemTraDiMuon()) {
            $this->trang_thai = self::TRANG_THAI_DI_MUON;
        }
        // Kiểm tra về sớm
        elseif ($gioRa && $this->kiemTraVeSom()) {
            $this->trang_thai = self::TRANG_THAI_VE_SOM;
        }
        // Bình thường
        elseif ($gioVao && $gioRa) {
            $this->trang_thai = self::TRANG_THAI_DUNG_GIO;
        }
        // Chỉ có check-in hoặc check-out
        else {
            $this->trang_thai = self::TRANG_THAI_KHONG_CHAM_CONG;
        }

        return $this;
    }

    public function kiemTraDiMuon(): bool
    {
        if (!$this->gio_vao) {
            return false;
        }

        $gioLamViec = GioLamViec::first();
        if (!$gioLamViec) {
            return false;
        }

        $gioVao = Carbon::parse($this->gio_vao);
        $gioBatDau = Carbon::parse($gioLamViec->gio_bat_dau);

        // Cho phép trễ tối đa
        $phutChoPhep = $gioLamViec->so_phut_cho_phep_di_tre ?? 15;
        $phutTre = $gioBatDau->diffInMinutes($gioVao, false);

        return $phutTre > $phutChoPhep;
    }

    public function kiemTraVeSom(): bool
    {
        if (!$this->gio_ra) {
            return false;
        }

        $gioLamViec = GioLamViec::first();
        if (!$gioLamViec) {
            return false;
        }

        $gioRa = Carbon::parse($this->gio_ra);
        $gioKetThuc = Carbon::parse($gioLamViec->gio_ket_thuc);

        // Cho phép về sớm tối đa
        $phutChoPhep = $gioLamViec->so_phut_cho_phep_ve_som ?? 15;
        $phutSom = $gioRa->diffInMinutes($gioKetThuc, false);

        return $phutSom > $phutChoPhep;
    }

    /**
     * Tính số công (quy đổi ra ngày công)
     * 1 công = 8 giờ
     */
    public function tinhSoCong(): float
    {
        if (!$this->so_gio_lam) {
            return 0;
        }

        return round($this->so_gio_lam / 8, 2);
    }

    /**
     * Tính lương cho ngày chấm công này
     */
    public function tinhLuongNgay(float $luongCoBan, float $tyLeTangCa = 1.5): array
    {
        $soGioLam = $this->so_gio_lam ?? 0;
        $soGioTangCa = $this->gio_tang_ca ?? 0;
        $soGioThuong = $soGioLam - $soGioTangCa;

        // Lương theo giờ
        $luongGio = $luongCoBan / (26 * 8);

        $tienLuongThuong = $soGioThuong * $luongGio;
        $tienLuongTangCa = $soGioTangCa * $luongGio * $tyLeTangCa;

        return [
            'luong_gio' => round($luongGio, 2),
            'so_gio_thuong' => round($soGioThuong, 2),
            'so_gio_tang_ca' => round($soGioTangCa, 2),
            'tien_luong_thuong' => round($tienLuongThuong, 2),
            'tien_luong_tang_ca' => round($tienLuongTangCa, 2),
            'tong_luong_ngay' => round($tienLuongThuong + $tienLuongTangCa, 2),
        ];
    }

    // =========================================================================
    // Static Helper Methods
    // =========================================================================

    /**
     * Kiểm tra đã chấm công hôm nay chưa
     */
    public static function daChamCongHomNay(int $nguoiDungId): bool
    {
        return self::where('nguoi_dung_id', $nguoiDungId)
            ->whereDate('ngay_cham_cong', Carbon::today())
            ->exists();
    }

    /**
     * Lấy bản ghi chấm công hôm nay
     */
    public static function layChamCongHomNay(int $nguoiDungId): ?self
    {
        return self::where('nguoi_dung_id', $nguoiDungId)
            ->whereDate('ngay_cham_cong', Carbon::today())
            ->first();
    }

    /**
     * Lấy tổng số giờ làm trong tháng
     */
    public static function tongGioLamTrongThang(int $nguoiDungId, int $thang, int $nam): float
    {
        return self::cuaNhanVien($nguoiDungId)
            ->thang($thang, $nam)
            ->sum('so_gio_lam');
    }

    /**
     * Lấy tổng số công trong tháng
     */
    public static function tongCongTrongThang(int $nguoiDungId, int $thang, int $nam): float
    {
        return self::cuaNhanVien($nguoiDungId)
            ->thang($thang, $nam)
            ->sum('so_cong');
    }

    /**
     * Lấy tổng số ngày đi muộn trong tháng
     */
    public static function tongNgayDiMuonTrongThang(int $nguoiDungId, int $thang, int $nam): int
    {
        return self::cuaNhanVien($nguoiDungId)
            ->thang($thang, $nam)
            ->where('trang_thai', self::TRANG_THAI_DI_MUON)
            ->count();
    }

    /**
     * Lấy tổng số ngày về sớm trong tháng
     */
    public static function tongNgayVeSomTrongThang(int $nguoiDungId, int $thang, int $nam): int
    {
        return self::cuaNhanVien($nguoiDungId)
            ->thang($thang, $nam)
            ->where('trang_thai', self::TRANG_THAI_VE_SOM)
            ->count();
    }

    /**
     * Lấy thống kê chấm công theo tháng
     */
    public static function thongKeTheoThang(int $nguoiDungId, int $thang, int $nam): array
    {
        $data = self::cuaNhanVien($nguoiDungId)
            ->thang($thang, $nam)
            ->selectRaw('
                COUNT(*) as tong_ngay,
                SUM(so_gio_lam) as tong_gio_lam,
                SUM(so_cong) as tong_cong,
                SUM(gio_tang_ca) as tong_tang_ca,
                SUM(phut_di_muon) as tong_phut_di_muon,
                SUM(phut_ve_som) as tong_phut_ve_som
            ')
            ->first();

        // Đếm theo trạng thái
        $trangThai = self::cuaNhanVien($nguoiDungId)
            ->thang($thang, $nam)
            ->selectRaw('trang_thai, COUNT(*) as so_luong')
            ->groupBy('trang_thai')
            ->pluck('so_luong', 'trang_thai')
            ->toArray();

        return [
            'tong_ngay' => $data->tong_ngay ?? 0,
            'tong_gio_lam' => round($data->tong_gio_lam ?? 0, 2),
            'tong_cong' => round($data->tong_cong ?? 0, 2),
            'tong_tang_ca' => round($data->tong_tang_ca ?? 0, 2),
            'tong_phut_di_muon' => $data->tong_phut_di_muon ?? 0,
            'tong_phut_ve_som' => $data->tong_phut_ve_som ?? 0,
            'trang_thai' => $trangThai,
        ];
    }

    // =========================================================================
    // Methods for API/JSON Response
    // =========================================================================

    public function toApiResponse(): array
    {
        return [
            'id' => $this->id,
            'ngay_cham_cong' => $this->ngay_cham_cong?->format('Y-m-d'),
            'gio_vao' => $this->gio_vao_format,
            'gio_ra' => $this->gio_ra_format,
            'so_gio_lam' => $this->so_gio_lam,
            'so_cong' => $this->so_cong,
            'gio_tang_ca' => $this->gio_tang_ca,
            'phut_di_muon' => $this->phut_di_muon,
            'phut_ve_som' => $this->phut_ve_som,
            'trang_thai' => $this->trang_thai,
            'trang_thai_text' => $this->trang_thai_text,
            'trang_thai_badge' => $this->trang_thai_badge,
            'phuong_thuc_cham_cong' => $this->phuong_thuc_cham_cong,
            'phuong_thuc_text' => $this->phuong_thuc_cham_cong_text,
            'phuong_thuc_icon' => $this->phuong_thuc_cham_cong_icon,
            'trang_thai_duyet' => $this->trang_thai_duyet,
            'trang_thai_duyet_text' => $this->trang_thai_duyet_text,
            'trang_thai_duyet_badge' => $this->trang_thai_duyet_badge,
            'ghi_chu' => $this->ghi_chu,
            'ghi_chu_duyet' => $this->ghi_chu_duyet,
            'ten_thiet_bi' => $this->ten_thiet_bi,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }

    public function getGioVaoFormatAttribute(): string
    {
        if (!$this->gio_vao) {
            return '--:--';
        }
        // Parse với timezone Asia/Ho_Chi_Minh
        $time = Carbon::parse($this->gio_vao, 'Asia/Ho_Chi_Minh');
        return $time->format('H:i');
    }

    public function getGioRaFormatAttribute(): string
    {
        if (!$this->gio_ra) {
            return '--:--';
        }
        $time = Carbon::parse($this->gio_ra, 'Asia/Ho_Chi_Minh');
        return $time->format('H:i');
    }

    public function getNgayChamCongFormatAttribute(): string
    {
        if (!$this->ngay_cham_cong) {
            return '--/--/----';
        }
        return Carbon::parse($this->ngay_cham_cong)->format('d/m/Y');
    }
    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_dung_id');
    }
}
