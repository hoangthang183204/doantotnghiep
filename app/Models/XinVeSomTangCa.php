<?php
// app/Models/XinVeSomTangCa.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class XinVeSomTangCa extends Model
{
    protected $table = 'xin_ve_som_tang_ca';

    protected $fillable = [
        'dang_ky_tang_ca_id',
        'nguoi_dung_id',
        'gio_ve_som_du_kien',
        'so_phut_ve_som',
        'ly_do',
        'trang_thai',
        'nguoi_duyet_id',
        'thoi_gian_duyet',
        'ly_do_tu_choi',
    ];

    protected $casts = [
        'thoi_gian_duyet' => 'datetime',
    ];

    // Trạng thái
    public static $trangThaiLabels = [
        'cho_duyet' => '⏳ Chờ duyệt',
        'da_duyet' => '✅ Đã duyệt',
        'tu_choi' => '❌ Từ chối',
        'huy' => '🗑️ Đã hủy',
    ];

    public static $trangThaiColors = [
        'cho_duyet' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300',
        'da_duyet' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300',
        'tu_choi' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300',
        'huy' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
    ];

    // Relations
    public function dang_ky_tang_ca(): BelongsTo
    {
        return $this->belongsTo(DangKyTangCa::class, 'dang_ky_tang_ca_id');
    }

    public function nguoi_dung(): BelongsTo
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_dung_id');
    }

    public function nguoi_duyet(): BelongsTo
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_duyet_id');
    }

    public function isApproved(): bool
    {
        return $this->trang_thai === 'da_duyet';
    }

    public function isValid(): bool
    {
        return $this->trang_thai === 'da_duyet' && 
               !$this->dang_ky_tang_ca->da_hoan_thanh;
    }
}