<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UngVien extends Model
{
    use HasFactory;

    protected $table = 'ung_vien';

    protected $fillable = [
        'ma_ho_so',
        'ho',
        'ten',
        'email',
        'so_dien_thoai',
        'tin_tuyen_dung_id',
        'phong_ban_id',
        'luong_mong_muon',
        'trang_thai',
        'nguoi_dung_id',
        'ghi_chu',
        'cv_path',
    ];

    // Accessor để lấy trạng thái tiếng Việt
    public function getTrangThaiTextAttribute()
    {
        $statuses = [
            'moi_nop' => 'Mới nộp',
            'cho_duyet' => 'Chờ duyệt',
            'da_duyet' => 'Đã duyệt',
            'hen_phong_van' => 'Hẹn phỏng vấn',
            'cho_phong_van' => 'Chờ phỏng vấn',
            'da_phong_van' => 'Đã phỏng vấn',
            'dat' => 'Trúng tuyển',
            'khong_dat' => 'Không đạt',
            'da_huy' => 'Đã hủy',
            'tam_dung' => 'Tạm dừng',
        ];

        return $statuses[$this->trang_thai] ?? $this->trang_thai;
    }

    // Accessor để lấy màu sắc cho trạng thái
    public function getTrangThaiColorAttribute()
    {
        $colors = [
            'moi_nop' => 'blue',
            'cho_duyet' => 'yellow',
            'da_duyet' => 'green',
            'hen_phong_van' => 'purple',
            'cho_phong_van' => 'indigo',
            'da_phong_van' => 'cyan',
            'dat' => 'emerald',
            'khong_dat' => 'red',
            'da_huy' => 'gray',
            'tam_dung' => 'orange',
        ];

        return $colors[$this->trang_thai] ?? 'gray';
    }

    // Accessor để lấy badge HTML
    public function getTrangThaiBadgeAttribute()
    {
        $text = $this->trang_thai_text;
        $color = $this->trang_thai_color;
        
        $colorClasses = [
            'blue' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
            'yellow' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400',
            'green' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
            'red' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
            'purple' => 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400',
            'indigo' => 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400',
            'cyan' => 'bg-cyan-100 text-cyan-700 dark:bg-cyan-900/30 dark:text-cyan-400',
            'gray' => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-400',
            'orange' => 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400',
            'emerald' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400',
        ];

        $class = $colorClasses[$color] ?? $colorClasses['gray'];

        return '<span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold ' . $class . '">
                    <span class="w-1.5 h-1.5 rounded-full bg-current opacity-60"></span>
                    ' . $text . '
                </span>';
    }

    // Quan hệ với tin tuyển dụng
    public function tinTuyenDung()
    {
        return $this->belongsTo(TinTuyenDung::class, 'tin_tuyen_dung_id');
    }

    // Quan hệ với phòng ban
    public function phongBan()
    {
        return $this->belongsTo(PhongBan::class, 'phong_ban_id');
    }

    // Quan hệ với người dùng
    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_dung_id');
    }

    // Quan hệ với lịch sử email
    public function lichSuEmails()
    {
        return $this->hasMany(LichSuEmail::class, 'ung_vien_id');
    }
}