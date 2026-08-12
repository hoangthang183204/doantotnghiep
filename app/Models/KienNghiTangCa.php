<?php
// app/Models/KienNghiTangCa.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KienNghiTangCa extends Model
{
    protected $table = 'kien_nghi_tang_ca';

    protected $fillable = [
        'nguoi_dung_id',
        'ly_do',
        'trang_thai',
        'nguoi_xu_ly_id',
        'ly_do_tu_choi',
        'thoi_gian_xu_ly',
        'don_tang_ca_id',
    ];

    protected $casts = [
        'thoi_gian_xu_ly' => 'datetime',
    ];

    // Trạng thái
    public static $trangThaiLabels = [
        'cho_xu_ly' => '🟡 Chờ xử lý',
        'da_dong_y' => '🟢 Đã đồng ý',
        'tu_choi' => '🔴 Từ chối',
    ];

    // Relations
    public function nguoi_dung(): BelongsTo
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_dung_id');
    }

    public function nguoi_xu_ly(): BelongsTo
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_xu_ly_id');
    }

    public function don_tang_ca(): BelongsTo
    {
        return $this->belongsTo(DangKyTangCa::class, 'don_tang_ca_id');
    }
}