<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LichSuEmail extends Model
{
    use HasFactory;

    protected $table = 'lich_su_email';

    protected $fillable = [
        'ung_vien_id',
        'tin_tuyen_dung_id',
        'nguoi_gui_id',
        'tieu_de',
        'noi_dung',
        'trang_thai',
        'thoi_gian_gui',
        'thoi_gian_xem',
        'email_nguoi_nhan',
        'loai_email',
    ];

    protected $casts = [
        'thoi_gian_gui' => 'datetime',
        'thoi_gian_xem' => 'datetime',
    ];

    // Quan hệ với ứng viên
    public function ungVien()
    {
        return $this->belongsTo(UngVien::class, 'ung_vien_id');
    }

    // Quan hệ với tin tuyển dụng
    public function tinTuyenDung()
    {
        return $this->belongsTo(TinTuyenDung::class, 'tin_tuyen_dung_id');
    }

    // Quan hệ với người gửi
    public function nguoiGui()
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_gui_id');
    }

    // Accessor: Lấy trạng thái tiếng Việt
    public function getTrangThaiTextAttribute()
    {
        $statuses = [
            'da_gui' => 'Đã gửi',
            'da_xem' => 'Đã xem',
        ];

        return $statuses[$this->trang_thai] ?? $this->trang_thai;
    }

    // Accessor: Lấy loại email tiếng Việt
    public function getLoaiEmailTextAttribute()
    {
        $types = [
            'thong_bao' => 'Thông báo',
            'phong_van' => 'Phỏng vấn',
            'ket_qua' => 'Kết quả',
        ];

        return $types[$this->loai_email] ?? $this->loai_email;
    }

    // Accessor: Lấy badge trạng thái
    public function getTrangThaiBadgeAttribute()
    {
        if ($this->trang_thai == 'da_xem') {
            return '<span class="px-2 py-1 rounded-full bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 text-xs font-medium">✅ Đã xem</span>';
        }
        return '<span class="px-2 py-1 rounded-full bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400 text-xs font-medium">⏳ Đã gửi</span>';
    }
}