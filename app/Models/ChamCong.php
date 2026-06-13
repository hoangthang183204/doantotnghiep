<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    public function nguoi_dung()
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_dung_id');
    }

    public function nguoi_phe_duyet()
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_phe_duyet_id');
    }

    // ========== CÁC METHOD MỚI THÊM CHO VIEW ==========

    /**
     * Kiểm tra đi muộn
     */
    public function kiemTraDiMuon(): bool
    {
        return $this->phut_di_muon > 0;
    }

    /**
     * Kiểm tra về sớm
     */
    public function kiemTraVeSom(): bool
    {
        return $this->phut_ve_som > 0;
    }

    /**
     * Getter cho giờ vào format (H:i)
     */
    public function getGioVaoFormatAttribute(): string
    {
        if (!$this->gio_vao) {
            return '--:--';
        }
        // Nếu gio_vao đã là string 'H:i' hoặc 'H:i:s'
        $parts = explode(':', $this->gio_vao);
        return count($parts) >= 2 ? $parts[0] . ':' . $parts[1] : $this->gio_vao;
    }

    /**
     * Getter cho giờ ra format (H:i)
     */
    public function getGioRaFormatAttribute(): string
    {
        if (!$this->gio_ra) {
            return '--:--';
        }
        $parts = explode(':', $this->gio_ra);
        return count($parts) >= 2 ? $parts[0] . ':' . $parts[1] : $this->gio_ra;
    }

    /**
     * Getter trạng thái text tiếng Việt
     */
    public function getTrangThaiTextAttribute(): string
    {
        $statuses = [
            'dung_gio' => 'Đúng giờ',
            'di_muon' => 'Đi muộn',
            've_som' => 'Về sớm',
            'vang_mat' => 'Vắng mặt',
            'nghi_phep' => 'Nghỉ phép',
            'khong_cham_cong' => 'Không chấm công',
        ];
        
        return $statuses[$this->trang_thai] ?? $this->trang_thai;
    }

    // -------------------------------------------------------------------------
    // Helpers — dùng chung cho Controller
    // -------------------------------------------------------------------------

    /**
     * Tính số giờ làm từ gio_vao và gio_ra (định dạng "HH:MM" hoặc "HH:MM:SS").
     * Hỗ trợ ca qua đêm (gio_ra < gio_vao).
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
     * Tính số phút đi muộn so với giờ chuẩn vào.
     */
    public static function tinhPhutDiMuon(?string $gioVao, string $gioChuanVao = '08:30'): int
    {
        if (!$gioVao) {
            return 0;
        }

        [$hV, $mV] = array_map('intval', explode(':', $gioVao));
        [$hC, $mC] = array_map('intval', explode(':', $gioChuanVao));

        return max(0, ($hV * 60 + $mV) - ($hC * 60 + $mC));
    }

    /**
     * Tính số phút về sớm so với giờ chuẩn ra.
     */
    public static function tinhPhutVeSom(?string $gioRa, string $gioChuanRa = '17:30'): int
    {
        if (!$gioRa) {
            return 0;
        }

        [$hR, $mR] = array_map('intval', explode(':', $gioRa));
        [$hC, $mC] = array_map('intval', explode(':', $gioChuanRa));

        return max(0, ($hC * 60 + $mC) - ($hR * 60 + $mR));
    }

    /**
     * Xác định trạng thái từ giờ vào/ra và số phút sai.
     * Trả về: 'dung_gio' | 'di_muon' | 've_som' | 'khong_cham_cong'
     */
    public static function xacDinhTrangThai(
        ?string $gioVao,
        ?string $gioRa,
        int $phutDiMuon,
        int $phutVeSom
    ): string {
        if (!$gioVao && !$gioRa) {
            return 'khong_cham_cong';
        }

        if ($phutDiMuon > 0 && $phutVeSom > 0) {
            return $phutDiMuon >= $phutVeSom ? 'di_muon' : 've_som';
        }

        if ($phutDiMuon > 0) return 'di_muon';
        if ($phutVeSom > 0)  return 've_som';

        return 'dung_gio';
    }
}