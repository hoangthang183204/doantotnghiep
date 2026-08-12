<?php

namespace App\Models;

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
        'ngay_nghi' => 'Ngày nghỉ (200%)',
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
     * ⭐ KIỂM TRA NHÂN VIÊN CÓ ĐANG TRONG GIỜ TĂNG CA KHÔNG
     */
    public static function isInOvertimePeriod($userId): bool
    {
        $overtime = self::getActiveOvertimeToday($userId);
        if (!$overtime) return false;

        $now = Carbon::now();
        $start = Carbon::parse($overtime->gio_bat_dau);
        $end = Carbon::parse($overtime->gio_ket_thuc);

        // Cho phép từ 30 phút trước giờ bắt đầu
        $checkinStart = $start->copy()->subMinutes(30);

        return $now->between($checkinStart, $end);
    }

    /**
     * ⭐ LẤY ĐƠN TĂNG CA ĐANG HOẠT ĐỘNG (KIỂM TRA CẢ THỜI GIAN)
     */
    public static function getActiveOvertimeNow($userId)
    {
        $overtime = self::getActiveOvertimeToday($userId);
        if (!$overtime) return null;

        $now = Carbon::now();
        $start = Carbon::parse($overtime->gio_bat_dau);
        $end = Carbon::parse($overtime->gio_ket_thuc);

        // Cho phép từ 30 phút trước giờ bắt đầu đến 2 giờ sau giờ kết thúc
        $checkinStart = $start->copy()->subMinutes(30);
        $checkoutEnd = $end->copy()->addHours(2);

        if ($now->between($checkinStart, $checkoutEnd)) {
            return $overtime;
        }

        return null;
    }

    /**
     * ⭐ KIỂM TRA NHÂN VIÊN ĐÃ ĐẾN GIỜ TĂNG CA CHƯA
     */
    public static function isOvertimeStarted($userId): bool
    {
        $overtime = self::getActiveOvertimeToday($userId);
        if (!$overtime) return false;

        $now = Carbon::now();
        $start = Carbon::parse($overtime->gio_bat_dau);
        $checkinStart = $start->copy()->subMinutes(30);

        return $now->gte($checkinStart);
    }

    /**
     * ⭐ TÍNH THỜI GIAN CÒN LẠI ĐẾN GIỜ TĂNG CA
     */
    public static function getTimeUntilOvertimeStart($userId): ?array
    {
        $overtime = self::getActiveOvertimeToday($userId);
        if (!$overtime) return null;

        $now = Carbon::now();
        $start = Carbon::parse($overtime->gio_bat_dau);
        $checkinStart = $start->copy()->subMinutes(30);

        if ($now->gte($checkinStart)) {
            return null;
        }

        $diffInMinutes = $now->diffInMinutes($checkinStart);
        $hours = floor($diffInMinutes / 60);
        $minutes = $diffInMinutes % 60;

        return [
            'hours' => $hours,
            'minutes' => $minutes,
            'total_minutes' => $diffInMinutes,
            'overtime' => $overtime,
            'text' => $hours > 0 ? "Còn {$hours} giờ {$minutes} phút nữa đến giờ tăng ca" : "Còn {$minutes} phút nữa đến giờ tăng ca"
        ];
    }
}   