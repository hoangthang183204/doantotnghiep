<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HoSo extends Model
{
    protected $table = 'ho_so_nguoi_dung';

    protected $fillable = [
        'nguoi_dung_id',        // ← THÊM: khóa ngoại link sang user
        'ma_nhan_vien',
        'ho',
        'ten',
        // 'email_cong_ty',      // ← BỎ: dùng email từ bảng nguoi_dung
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
    ];

    protected $casts = [
        'ngay_sinh' => 'date',
        'gioi_tinh' => 'string',
        'tinh_trang_hon_nhan' => 'string',
    ];

    /**
     * Quan hệ: Hồ sơ thuộc về 1 người dùng
     */
    public function nguoi_dung(): BelongsTo
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_dung_id');
    }

    /**
     * Accessor: Lấy họ tên đầy đủ
     */
    public function getHoTenAttribute(): string
    {
        return trim($this->ho . ' ' . $this->ten);
    }

    /**
     * Accessor: Lấy email từ bảng nguoi_dung
     */
    public function getEmailAttribute()
    {
        return $this->nguoi_dung?->email;
    }

    /**
     * Accessor: Lấy trạng thái từ bảng nguoi_dung
     */
    public function getTrangThaiAttribute()
    {
        return $this->nguoi_dung?->trang_thai;
    }

    /**
     * Accessor: Lấy trạng thái công việc từ bảng nguoi_dung
     */
    public function getTrangThaiCongViecAttribute()
    {
        return $this->nguoi_dung?->trang_thai_cong_viec;
    }

    /**
     * Accessor: Lấy phòng ban từ bảng nguoi_dung
     */
    public function getPhongBanAttribute()
    {
        return $this->nguoi_dung?->phong_ban;
    }

    /**
     * Accessor: Lấy chức vụ từ bảng nguoi_dung
     */
    public function getChucVuAttribute()
    {
        return $this->nguoi_dung?->chuc_vu;
    }
}