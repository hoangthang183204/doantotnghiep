<?php

namespace App\Models;

use App\Models\XinVeSomTangCa;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Carbon\Carbon;

class DangKyTangCa extends Model
{
    protected $table = 'dang_ky_tang_ca';

    protected $fillable = [
        'nguoi_dung_id',
        'nguoi_tao_id',
        'loai_tao',
        'ngay_tang_ca',
        'gio_bat_dau',
        'gio_ket_thuc',
        'so_gio_tang_ca',
        'loai_tang_ca',
        'ly_do_tang_ca',
        'trang_thai',
        'don_tang_ca_id',
        'nguoi_duyet_id',
        'thoi_gian_duyet',
        'ly_do_tu_choi',
        'luong_tang_ca',
        'da_hoan_thanh',
        'da_checkout_thay_the',
        'thoi_gian_hoan_thanh',
    ];

    protected $casts = [
        'ngay_tang_ca' => 'date',
        'thoi_gian_duyet' => 'datetime',
        'thoi_gian_hoan_thanh' => 'datetime',
        'da_hoan_thanh' => 'boolean',
        'da_checkout_thay_the' => 'boolean',
    ];

    // Trạng thái đơn
    public static $trangThaiLabels = [
        'cho_duyet' => 'Chờ duyệt',
        'da_duyet' => 'Đã duyệt',
        'tu_choi' => 'Từ chối',
        'huy' => 'Đã hủy',
    ];

    // Loại tăng ca
    public static $loaiLabels = [
        'ngay_thuong' => 'Ngày thường (150%)',
        'ngay_nghi' => 'Ngày cuối tuần (200%)',
        'le_tet' => 'Lễ, Tết (400%)',
    ];

    // Loại tạo đơn
    public static $loaiTaoLabels = [
        'nhan_vien' => 'Nhân viên tự tạo',
        'truong_phong' => 'Trưởng phòng tạo',
    ];

    // Relations
    public function nguoi_dung(): BelongsTo
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_dung_id');
    }

    public function nguoi_duyet(): BelongsTo
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_duyet_id');
    }

    public function nguoi_tao(): BelongsTo
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_tao_id');
    }

    public function thuc_hien(): HasOne
    {
        return $this->hasOne(ThucHienTangCa::class, 'dang_ky_tang_ca_id');
    }

    /**
     * Kiểm tra đơn tăng ca có hiệu lực cho ngày hôm nay không
     */
    public function isActiveToday(): bool
    {
        $today = now()->format('Y-m-d');
        return $this->ngay_tang_ca && $this->ngay_tang_ca->format('Y-m-d') === $today
            && $this->trang_thai === 'da_duyet'
            && !$this->da_hoan_thanh;
    }

    /**
     * Kiểm tra nhân viên có đơn tăng ca đang hoạt động hôm nay không
     */
    public static function hasActiveOvertimeToday($userId): bool
    {
        return self::where('nguoi_dung_id', $userId)
            ->whereDate('ngay_tang_ca', now()->format('Y-m-d'))
            ->where('trang_thai', 'da_duyet')
            ->where('da_hoan_thanh', false)
            ->exists();
    }

    /**
     * Lấy đơn tăng ca đang hoạt động hôm nay của nhân viên
     */
    public static function getActiveOvertimeToday($userId)
    {
        return self::where('nguoi_dung_id', $userId)
            ->whereDate('ngay_tang_ca', now()->format('Y-m-d'))
            ->where('trang_thai', 'da_duyet')
            ->where('da_hoan_thanh', false)
            ->first();
    }

    /**
     * ⭐ KIỂM TRA CÓ THỂ CHECK-OUT KHÔNG
     * (CHỈ CHO PHÉP CHECK-OUT KHI CÒN 10 PHÚT CUỐI HOẶC ĐÃ QUA GIỜ KẾT THÚC)
     */
    public static function canCheckout($overtimeId)
    {
        $overtime = self::with(['thuc_hien', 'xin_ve_som' => function ($q) {
            $q->where('trang_thai', 'da_duyet');
        }])->find($overtimeId);

        if (!$overtime) {
            return [
                'valid' => false,
                'message' => 'Không tìm thấy đơn tăng ca'
            ];
        }

        if ($overtime->trang_thai !== 'da_duyet') {
            return [
                'valid' => false,
                'message' => 'Đơn tăng ca chưa được duyệt'
            ];
        }

        if ($overtime->da_hoan_thanh) {
            return [
                'valid' => false,
                'message' => 'Đơn tăng ca đã hoàn thành'
            ];
        }

        $now = Carbon::now('Asia/Ho_Chi_Minh');
        $ngayTangCa = Carbon::parse($overtime->ngay_tang_ca)->startOfDay();
        $gioBatDau = Carbon::parse($overtime->gio_bat_dau);
        $gioKetThuc = Carbon::parse($overtime->gio_ket_thuc);

        $thoiGianBatDau = Carbon::parse(
            $ngayTangCa->format('Y-m-d') . ' ' . $gioBatDau->format('H:i:s')
        );
        $thoiGianKetThuc = Carbon::parse(
            $ngayTangCa->format('Y-m-d') . ' ' . $gioKetThuc->format('H:i:s')
        );

        // Kiểm tra đã đến giờ bắt đầu chưa
        if ($now->lt($thoiGianBatDau)) {
            $diffMinutes = $now->diffInMinutes($thoiGianBatDau);
            $hours = floor($diffMinutes / 60);
            $minutes = $diffMinutes % 60;
            $timeText = $hours > 0 ? "Còn {$hours} giờ {$minutes} phút" : "Còn {$minutes} phút";
            return [
                'valid' => false,
                'message' => "⏳ Chưa đến giờ tăng ca. {$timeText} nữa."
            ];
        }

        // ⭐⭐ KIỂM TRA CHECK-OUT - CHỈ CHO PHÉP KHI CÒN 10 PHÚT CUỐI HOẶC ĐÃ QUA GIỜ KẾT THÚC ⭐⭐
        $conLaiBaoNhieuPhut = $now->diffInMinutes($thoiGianKetThuc, false);

        // Nếu còn nhiều hơn 10 phút (và chưa đến giờ kết thúc) -> KHÔNG CHO CHECK-OUT
        if ($conLaiBaoNhieuPhut > 10) {
            $hours = floor($conLaiBaoNhieuPhut / 60);
            $minutes = $conLaiBaoNhieuPhut % 60;
            $timeText = $hours > 0 ? "còn {$hours} giờ {$minutes} phút" : "còn {$minutes} phút";
            return [
                'valid' => false,
                'message' => "⏳ Chưa đến giờ check-out. {$timeText} nữa."
            ];
        }

        // Nếu còn 10 phút hoặc ít hơn, HOẶC đã qua giờ kết thúc -> CHO PHÉP CHECK-OUT
        // ⭐⭐ KIỂM TRA XIN VỀ SỚM ⭐⭐
        $xinVeSom = $overtime->xin_ve_som;
        $isEarly = $now->lt($thoiGianKetThuc);

        // Nếu về sớm (trước giờ kết thúc)
        if ($isEarly) {
            // Nếu CÓ đơn xin về sớm được duyệt
            if ($xinVeSom && $xinVeSom->isValid()) {
                $soPhutVeSom = $xinVeSom->so_phut_ve_som;
                $gioVeSom = Carbon::parse($xinVeSom->gio_ve_som_du_kien);

                // Kiểm tra đã đến giờ được phép về sớm chưa
                if ($now->lt($gioVeSom)) {
                    $diffMinutes = $now->diffInMinutes($gioVeSom);
                    $hours = floor($diffMinutes / 60);
                    $minutes = $diffMinutes % 60;
                    $timeText = $hours > 0 ? "Còn {$hours} giờ {$minutes} phút" : "Còn {$minutes} phút";
                    return [
                        'valid' => false,
                        'message' => "⏳ Chưa đến giờ về sớm đã được duyệt ({$xinVeSom->gio_ve_som_du_kien}). {$timeText} nữa."
                    ];
                }

                // Được phép về sớm
                $soGioThucTe = $thoiGianBatDau->diffInHours($now);
                $soGioThucTe = min($soGioThucTe, $overtime->so_gio_tang_ca);

                return [
                    'valid' => true,
                    'message' => '✅ Đã được duyệt về sớm',
                    'is_early' => true,
                    'early_minutes' => $soPhutVeSom,
                    'so_gio_thuc_te' => $soGioThucTe,
                    'has_xin_ve_som' => true,
                    'so_phut_ve_som' => $soPhutVeSom,
                ];
            }

            // Nếu KHÔNG có đơn xin về sớm
            // Cho phép check-out khi còn 10 phút cuối
            $soGioThucTe = $thoiGianBatDau->diffInHours($now);
            $soGioThucTe = min($soGioThucTe, $overtime->so_gio_tang_ca);

            return [
                'valid' => true,
                'message' => '✅ Check-out (còn 10 phút cuối)',
                'is_early' => true,
                'early_minutes' => $now->diffInMinutes($thoiGianKetThuc),
                'so_gio_thuc_te' => $soGioThucTe,
                'has_xin_ve_som' => false,
            ];
        }

        // Check-out đúng giờ hoặc sau giờ kết thúc
        $soGioThucTe = $thoiGianBatDau->diffInHours($now);
        $soGioThucTe = min($soGioThucTe, $overtime->so_gio_tang_ca);

        if ($soGioThucTe < 0.5) {
            return [
                'valid' => false,
                'message' => '⛔ Thời gian làm tăng ca quá ngắn (tối thiểu 0.5 giờ).'
            ];
        }

        return [
            'valid' => true,
            'message' => '✅ Có thể check-out',
            'is_early' => false,
            'early_minutes' => 0,
            'so_gio_thuc_te' => $soGioThucTe,
            'has_xin_ve_som' => false,
        ];
    }

    /**
     * ⭐ TÍNH THỜI GIAN CÒN LẠI ĐẾN GIỜ TĂNG CA
     */
    public static function getTimeUntilOvertimeStart($userId): ?array
    {
        $overtime = self::getActiveOvertimeToday($userId);
        if (!$overtime) return null;

        $now = Carbon::now('Asia/Ho_Chi_Minh');
        $ngayTangCa = Carbon::parse($overtime->ngay_tang_ca)->startOfDay();
        $start = Carbon::parse($overtime->gio_bat_dau);

        $thoiGianBatDau = Carbon::parse(
            $ngayTangCa->format('Y-m-d') . ' ' . $start->format('H:i:s')
        );

        if ($now->gte($thoiGianBatDau)) {
            return null;
        }

        $diffInMinutes = $now->diffInMinutes($thoiGianBatDau);
        $hours = floor($diffInMinutes / 60);
        $minutes = $diffInMinutes % 60;

        if ($diffInMinutes < 60) {
            $text = "Còn {$diffInMinutes} phút nữa đến giờ tăng ca";
        } elseif ($hours > 0 && $minutes > 0) {
            $text = "Còn {$hours} giờ {$minutes} phút nữa đến giờ tăng ca";
        } elseif ($hours > 0) {
            $text = "Còn {$hours} giờ nữa đến giờ tăng ca";
        } else {
            $text = "Còn {$minutes} phút nữa đến giờ tăng ca";
        }

        return [
            'overtime' => $overtime,
            'text' => $text,
            'remaining_minutes' => $diffInMinutes,
            'hours' => $hours,
            'minutes' => $minutes,
            'start_time' => $thoiGianBatDau->format('H:i:s'),
        ];
    }

    public function xin_ve_som(): HasOne
    {
        return $this->hasOne(XinVeSomTangCa::class, 'dang_ky_tang_ca_id');
    }

    public function xin_ve_som_da_duyet(): HasOne
    {
        return $this->hasOne(XinVeSomTangCa::class, 'dang_ky_tang_ca_id')
            ->where('trang_thai', 'da_duyet');
    }
}
