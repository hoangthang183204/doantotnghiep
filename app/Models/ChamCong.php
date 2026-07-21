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
        // ========== THÊM MỚI ==========
        'ca_lam_viec_id',
        'loai_cham_cong',
        'ly_do_ve_som',
        'da_xac_nhan_ve_som',
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
        'da_xac_nhan_ve_som'  => 'boolean',
    ];

    // =========================================================================
    // CONSTANTS
    // =========================================================================

    // Trạng thái duyệt
    const TRANG_THAI_DUYET_CHUA_DUYET = 0;
    const TRANG_THAI_DUYET_DA_DUYET = 1;
    const TRANG_THAI_DUYET_TU_CHOI = 2;
    const TRANG_THAI_DUYET_DANG_DUYET = 3;

    // Trạng thái chấm công
    const TRANG_THAI_DEN_SOM = 'den_som';
    const TRANG_THAI_DUNG_GIO = 'dung_gio';
    const TRANG_THAI_DI_MUON = 'di_muon';
    const TRANG_THAI_VE_SOM = 've_som';
    const TRANG_THAI_KHONG_CHAM_CONG = 'khong_cham_cong';
    const TRANG_THAI_NGHI_PHEP = 'nghi_phep';
    const TRANG_THAI_VANG_MAT = 'vang_mat';
    const TRANG_THAI_TANG_CA = 'tang_ca';

    // Loại chấm công
    const LOAI_CHECK_IN = 'check_in';
    const LOAI_CHECK_OUT = 'check_out';

    // =========================================================================
    // RELATIONSHIPS
    // =========================================================================

    public function nguoi_dung()
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_dung_id');
    }

    public function nguoi_phe_duyet()
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_phe_duyet_id');
    }

    public function caLamViec()
    {
        return $this->belongsTo(CaLamViec::class, 'ca_lam_viec_id');
    }

    // =========================================================================
    // ACCESSORS
    // =========================================================================

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
            self::TRANG_THAI_TANG_CA => 'Tăng ca',
        ];

        return $statuses[$this->trang_thai] ?? $this->trang_thai;
    }

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
            self::TRANG_THAI_TANG_CA => 'primary',
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
            'ip' => '📡 IP',
            'wifi' => '📶 WiFi',
            'mac' => '💻 MAC',
            'manual' => '✍️ Thủ công',
            'qr' => '📱 QR Code',
            'face' => '👤 Nhận diện khuôn mặt',
            'finger' => '🖐️ Vân tay',
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

    // ========== Accessors cho ca làm việc ==========
    public function getTenCaAttribute(): string
    {
        return $this->caLamViec ? $this->caLamViec->ten : 'Chưa xác định';
    }

    public function getGioBatDauCaAttribute(): ?string
    {
        return $this->caLamViec ? $this->caLamViec->gio_bat_dau_format : null;
    }

    public function getGioKetThucCaAttribute(): ?string
    {
        return $this->caLamViec ? $this->caLamViec->gio_ket_thuc_format : null;
    }

    public function getLoaiChamCongTextAttribute(): string
    {
        $map = [
            self::LOAI_CHECK_IN => '📥 Check-in',
            self::LOAI_CHECK_OUT => '📤 Check-out',
        ];
        return $map[$this->loai_cham_cong] ?? $this->loai_cham_cong;
    }

    public function getLoaiChamCongIconAttribute(): string
    {
        $map = [
            self::LOAI_CHECK_IN => 'fas fa-sign-in-alt',
            self::LOAI_CHECK_OUT => 'fas fa-sign-out-alt',
        ];
        return $map[$this->loai_cham_cong] ?? 'fas fa-clock';
    }

    public function getGioVaoFormatAttribute(): string
    {
        if (!$this->gio_vao) {
            return '--:--';
        }
        return Carbon::parse($this->gio_vao, 'Asia/Ho_Chi_Minh')->format('H:i');
    }

    public function getGioRaFormatAttribute(): string
    {
        if (!$this->gio_ra) {
            return '--:--';
        }
        return Carbon::parse($this->gio_ra, 'Asia/Ho_Chi_Minh')->format('H:i');
    }

    public function getNgayChamCongFormatAttribute(): string
    {
        if (!$this->ngay_cham_cong) {
            return '--/--/----';
        }
        return Carbon::parse($this->ngay_cham_cong)->format('d/m/Y');
    }

    // =========================================================================
    // SCOPES
    // =========================================================================

    public function scopeNgay($query, $ngay)
    {
        return $query->whereDate('ngay_cham_cong', $ngay);
    }

    public function scopeThang($query, $thang, $nam = null)
    {
        $nam = $nam ?? Carbon::now('Asia/Ho_Chi_Minh')->year;
        return $query->whereMonth('ngay_cham_cong', $thang)
            ->whereYear('ngay_cham_cong', $nam);
    }

    public function scopeCuaNhanVien($query, $nguoiDungId)
    {
        return $query->where('nguoi_dung_id', $nguoiDungId);
    }

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

    public function scopePhuongThuc($query, $method)
    {
        return $query->where('phuong_thuc_cham_cong', $method);
    }

    // ========== Scopes cho ca và loại ==========
    public function scopeCaLamViec($query, $caId)
    {
        return $query->where('ca_lam_viec_id', $caId);
    }

    public function scopeCaSang($query)
    {
        $caSang = CaLamViec::getSang();
        if ($caSang) {
            return $query->where('ca_lam_viec_id', $caSang->id);
        }
        return $query;
    }

    public function scopeCaChieu($query)
    {
        $caChieu = CaLamViec::getChieu();
        if ($caChieu) {
            return $query->where('ca_lam_viec_id', $caChieu->id);
        }
        return $query;
    }

    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_dung_id');
    }

    public function scopeLoai($query, $loai)
    {
        return $query->where('loai_cham_cong', $loai);
    }

    public function scopeCheckIn($query)
    {
        return $query->where('loai_cham_cong', self::LOAI_CHECK_IN);
    }

    public function scopeCheckOut($query)
    {
        return $query->where('loai_cham_cong', self::LOAI_CHECK_OUT);
    }

    public function scopeVeSom($query)
    {
        return $query->where('trang_thai', self::TRANG_THAI_VE_SOM);
    }

    public function scopeDiMuon($query)
    {
        return $query->where('trang_thai', self::TRANG_THAI_DI_MUON);
    }

    // =========================================================================
    // BUSINESS LOGIC METHODS
    // =========================================================================

    public static function tinhSoGioLam(?string $gioVao, ?string $gioRa): float
    {
        if (!$gioVao || !$gioRa) {
            return 0.0;
        }

        [$hV, $mV] = array_map('intval', explode(':', $gioVao));
        [$hR, $mR] = array_map('intval', explode(':', $gioRa));

        $phutVao = $hV * 60 + $mV;
        $phutRa  = $hR * 60 + $mR;

        if ($phutRa < $phutVao) {
            $phutRa += 24 * 60;
        }

        return round(($phutRa - $phutVao) / 60, 2);
    }

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

    public function capNhatTrangThai(): self
    {
        $gioVao = $this->gio_vao;
        $gioRa = $this->gio_ra;

        if (!$gioVao && !$gioRa) {
            $this->trang_thai = self::TRANG_THAI_KHONG_CHAM_CONG;
            return $this;
        }

        if ($gioVao && $this->kiemTraDiMuon()) {
            $this->trang_thai = self::TRANG_THAI_DI_MUON;
        } elseif ($gioRa && $this->kiemTraVeSom()) {
            $this->trang_thai = self::TRANG_THAI_VE_SOM;
        } elseif ($gioVao && $gioRa) {
            $this->trang_thai = self::TRANG_THAI_DUNG_GIO;
        } else {
            $this->trang_thai = self::TRANG_THAI_KHONG_CHAM_CONG;
        }

        return $this;
    }

    public function kiemTraDiMuon(): bool
    {
        if (!$this->gio_vao) {
            return false;
        }

        if ($this->caLamViec) {
            $gioBatDau = Carbon::parse($this->caLamViec->gio_bat_dau);
            $phutChoPhep = $this->caLamViec->so_phut_cho_phep_di_tre ?? 15;
        } else {
            $gioLamViec = GioLamViec::first();
            if (!$gioLamViec) {
                return false;
            }
            $gioBatDau = Carbon::parse($gioLamViec->gio_bat_dau);
            $phutChoPhep = $gioLamViec->so_phut_cho_phep_di_tre ?? 15;
        }

        $gioVao = Carbon::parse($this->gio_vao);
        $phutTre = $gioBatDau->diffInMinutes($gioVao, false);

        return $phutTre > $phutChoPhep;
    }

    public function kiemTraVeSom(): bool
    {
        if (!$this->gio_ra) {
            return false;
        }

        if ($this->caLamViec) {
            $gioKetThuc = Carbon::parse($this->caLamViec->gio_ket_thuc);
            $phutChoPhep = $this->caLamViec->so_phut_cho_phep_ve_som ?? 15;
        } else {
            $gioLamViec = GioLamViec::first();
            if (!$gioLamViec) {
                return false;
            }
            $gioKetThuc = Carbon::parse($gioLamViec->gio_ket_thuc);
            $phutChoPhep = $gioLamViec->so_phut_cho_phep_ve_som ?? 15;
        }

        $gioRa = Carbon::parse($this->gio_ra);
        $phutSom = $gioRa->diffInMinutes($gioKetThuc, false);

        return $phutSom > $phutChoPhep;
    }

    public function tinhSoCong(): float
    {
        if (!$this->so_gio_lam) {
            return 0;
        }
        return round($this->so_gio_lam / 8, 2);
    }

    public function tinhLuongNgay(float $luongCoBan, float $tyLeTangCa = 1.5): array
    {
        $soGioLam = $this->so_gio_lam ?? 0;
        $soGioTangCa = $this->gio_tang_ca ?? 0;
        $soGioThuong = $soGioLam - $soGioTangCa;

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

    // ========== Logic cho ca sáng/chiều ==========

    public function isVeSom(): bool
    {
        if (!$this->gio_ra || !$this->caLamViec) {
            return false;
        }

        $gioRa = Carbon::parse($this->gio_ra);
        $gioKetThuc = Carbon::parse($this->caLamViec->gio_ket_thuc);

        return $gioRa->lt($gioKetThuc);
    }

    public function tinhSoCongCa(): float
    {
        if (!$this->gio_vao || !$this->gio_ra || !$this->caLamViec) {
            return 0;
        }

        $gioVao = Carbon::parse($this->gio_vao);
        $gioRa = Carbon::parse($this->gio_ra);
        $soPhutLam = $gioVao->diffInMinutes($gioRa);

        $soGioTieuChuan = (float) ($this->caLamViec->so_gio_lam_viec ?? 4);
        $soCong = round(($soPhutLam / 60 / $soGioTieuChuan) * 2) / 2;

        return min($soCong, 0.5);
    }

    // =========================================================================
    // STATIC HELPER METHODS
    // =========================================================================

    /**
     * Xác định ca làm việc dựa vào giờ
     */
    public static function xacDinhCaLamViec($gio): ?CaLamViec
    {
        if ($gio instanceof Carbon) {
            $gio = $gio->format('H:i');
        } elseif (strpos($gio, ':') === false) {
            $gio = Carbon::parse($gio)->format('H:i');
        }

        // Ca sáng: 06:00 - 12:30
        if ($gio >= '06:00' && $gio < '12:30') {
            return CaLamViec::getSang();
        }

        // Ca chiều: 13:00 - 17:30
        if ($gio >= '13:00' && $gio < '17:30') {
            return CaLamViec::getChieu();
        }

        // Khoảng 12:30 - 13:00 → vẫn là ca sáng
        if ($gio >= '12:30' && $gio < '13:00') {
            return CaLamViec::getSang();
        }

        // Sau 17:30 → ca chiều (tăng ca)
        if ($gio >= '17:30') {
            return CaLamViec::getChieu();
        }

        return CaLamViec::getDefault();
    }

    /**
     * Kiểm tra đã check-in hôm nay chưa
     */
    public static function daCheckInHomNay(int $nguoiDungId): bool
    {
        return self::where('nguoi_dung_id', $nguoiDungId)
            ->whereDate('ngay_cham_cong', Carbon::today('Asia/Ho_Chi_Minh'))
            ->whereNotNull('gio_vao')
            ->exists();
    }

    /**
     * Kiểm tra đã check-out hôm nay chưa
     */
    public static function daCheckOutHomNay(int $nguoiDungId): bool
    {
        return self::where('nguoi_dung_id', $nguoiDungId)
            ->whereDate('ngay_cham_cong', Carbon::today('Asia/Ho_Chi_Minh'))
            ->whereNotNull('gio_ra')
            ->exists();
    }

    /**
     * Kiểm tra đã chấm công hôm nay chưa
     */
    public static function daChamCongHomNay(int $nguoiDungId): bool
    {
        return self::where('nguoi_dung_id', $nguoiDungId)
            ->whereDate('ngay_cham_cong', Carbon::today('Asia/Ho_Chi_Minh'))
            ->exists();
    }

    /**
     * Lấy bản ghi chấm công hôm nay
     */
    public static function layChamCongHomNay(int $nguoiDungId): ?self
    {
        return self::where('nguoi_dung_id', $nguoiDungId)
            ->whereDate('ngay_cham_cong', Carbon::today('Asia/Ho_Chi_Minh'))
            ->first();
    }

    /**
     * Lấy bản ghi check-in của ca sáng hôm nay
     */
    public static function getCheckInSangHomNay(int $nguoiDungId): ?self
    {
        $caSang = CaLamViec::getSang();
        if (!$caSang) return null;

        return self::where('nguoi_dung_id', $nguoiDungId)
            ->whereDate('ngay_cham_cong', Carbon::today('Asia/Ho_Chi_Minh'))
            ->where('ca_lam_viec_id', $caSang->id)
            ->where('loai_cham_cong', self::LOAI_CHECK_IN)
            ->first();
    }

    /**
     * Lấy bản ghi check-out của ca sáng hôm nay
     */
    public static function getCheckOutSangHomNay(int $nguoiDungId): ?self
    {
        $caSang = CaLamViec::getSang();
        if (!$caSang) return null;

        return self::where('nguoi_dung_id', $nguoiDungId)
            ->whereDate('ngay_cham_cong', Carbon::today('Asia/Ho_Chi_Minh'))
            ->where('ca_lam_viec_id', $caSang->id)
            ->where('loai_cham_cong', self::LOAI_CHECK_OUT)
            ->first();
    }

    /**
     * Lấy bản ghi check-in của ca chiều hôm nay
     */
    public static function getCheckInChieuHomNay(int $nguoiDungId): ?self
    {
        $caChieu = CaLamViec::getChieu();
        if (!$caChieu) return null;

        return self::where('nguoi_dung_id', $nguoiDungId)
            ->whereDate('ngay_cham_cong', Carbon::today('Asia/Ho_Chi_Minh'))
            ->where('ca_lam_viec_id', $caChieu->id)
            ->where('loai_cham_cong', self::LOAI_CHECK_IN)
            ->first();
    }

    /**
     * Lấy bản ghi check-out của ca chiều hôm nay
     */
    public static function getCheckOutChieuHomNay(int $nguoiDungId): ?self
    {
        $caChieu = CaLamViec::getChieu();
        if (!$caChieu) return null;

        return self::where('nguoi_dung_id', $nguoiDungId)
            ->whereDate('ngay_cham_cong', Carbon::today('Asia/Ho_Chi_Minh'))
            ->where('ca_lam_viec_id', $caChieu->id)
            ->where('loai_cham_cong', self::LOAI_CHECK_OUT)
            ->first();
    }

    /**
     * Lấy tất cả bản ghi chấm công trong ngày
     */
    public static function getChamCongTrongNgay(int $nguoiDungId, ?string $ngay = null): \Illuminate\Database\Eloquent\Collection
    {
        $ngay = $ngay ? Carbon::parse($ngay) : Carbon::today('Asia/Ho_Chi_Minh');

        return self::with('caLamViec')
            ->where('nguoi_dung_id', $nguoiDungId)
            ->whereDate('ngay_cham_cong', $ngay)
            ->orderBy('ca_lam_viec_id')
            ->orderBy('loai_cham_cong')
            ->get();
    }

    /**
     * Kiểm tra ca sáng đã hoàn thành chưa
     */
    public static function isSangHoanThanh(int $nguoiDungId): bool
    {
        return self::getCheckInSangHomNay($nguoiDungId) && self::getCheckOutSangHomNay($nguoiDungId);
    }

    /**
     * Kiểm tra ca chiều đã hoàn thành chưa
     */
    public static function isChieuHoanThanh(int $nguoiDungId): bool
    {
        return self::getCheckInChieuHomNay($nguoiDungId) && self::getCheckOutChieuHomNay($nguoiDungId);
    }

    /**
     * Lấy tổng số công trong ngày
     */
    public static function tongCongTrongNgay(int $nguoiDungId, ?string $ngay = null): float
    {
        $ngay = $ngay ? Carbon::parse($ngay) : Carbon::today('Asia/Ho_Chi_Minh');

        return self::where('nguoi_dung_id', $nguoiDungId)
            ->whereDate('ngay_cham_cong', $ngay)
            ->where('loai_cham_cong', self::LOAI_CHECK_IN)
            ->sum('so_cong');
    }

    /**
     * Lấy tổng số giờ làm trong ngày
     */
    public static function tongGioLamTrongNgay(int $nguoiDungId, ?string $ngay = null): float
    {
        $ngay = $ngay ? Carbon::parse($ngay) : Carbon::today('Asia/Ho_Chi_Minh');

        return self::where('nguoi_dung_id', $nguoiDungId)
            ->whereDate('ngay_cham_cong', $ngay)
            ->where('loai_cham_cong', self::LOAI_CHECK_IN)
            ->sum('so_gio_lam');
    }

    /**
     * Lấy tổng số giờ làm trong tháng
     */
    public static function tongGioLamTrongThang(int $nguoiDungId, int $thang, int $nam): float
    {
        return self::cuaNhanVien($nguoiDungId)
            ->thang($thang, $nam)
            ->where('loai_cham_cong', self::LOAI_CHECK_IN)
            ->sum('so_gio_lam');
    }

    /**
     * Lấy tổng số công trong tháng
     */
    public static function tongCongTrongThang(int $nguoiDungId, int $thang, int $nam): float
    {
        return self::cuaNhanVien($nguoiDungId)
            ->thang($thang, $nam)
            ->where('loai_cham_cong', self::LOAI_CHECK_IN)
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
            ->where('loai_cham_cong', self::LOAI_CHECK_IN)
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
            ->where('loai_cham_cong', self::LOAI_CHECK_IN)
            ->count();
    }

    /**
     * Lấy thống kê chấm công theo tháng
     */
    public static function thongKeTheoThang(int $nguoiDungId, int $thang, int $nam): array
    {
        $data = self::cuaNhanVien($nguoiDungId)
            ->thang($thang, $nam)
            ->where('loai_cham_cong', self::LOAI_CHECK_IN)
            ->selectRaw('
                COUNT(*) as tong_ngay,
                SUM(so_gio_lam) as tong_gio_lam,
                SUM(so_cong) as tong_cong,
                SUM(gio_tang_ca) as tong_tang_ca,
                SUM(phut_di_muon) as tong_phut_di_muon,
                SUM(phut_ve_som) as tong_phut_ve_som
            ')
            ->first();

        $trangThai = self::cuaNhanVien($nguoiDungId)
            ->thang($thang, $nam)
            ->where('loai_cham_cong', self::LOAI_CHECK_IN)
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
    // API RESPONSE
    // =========================================================================

    public function toApiResponse(): array
    {
        return [
            'id' => $this->id,
            'ngay_cham_cong' => $this->ngay_cham_cong?->format('Y-m-d'),
            'ngay_cham_cong_format' => $this->ngay_cham_cong_format,
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
            'ca_lam_viec_id' => $this->ca_lam_viec_id,
            'ten_ca' => $this->ten_ca,
            'gio_bat_dau_ca' => $this->gio_bat_dau_ca,
            'gio_ket_thuc_ca' => $this->gio_ket_thuc_ca,
            'loai_cham_cong' => $this->loai_cham_cong,
            'loai_cham_cong_text' => $this->loai_cham_cong_text,
            'loai_cham_cong_icon' => $this->loai_cham_cong_icon,
            'ly_do_ve_som' => $this->ly_do_ve_som,
            'da_xac_nhan_ve_som' => $this->da_xac_nhan_ve_som,
            'is_ve_som' => $this->isVeSom(),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
