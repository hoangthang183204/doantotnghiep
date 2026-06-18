<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaiLieu extends Model
{
    protected $table = 'tai_lieu';

    protected $fillable = [
        'nguoi_dung_id',
        'ung_vien_id',
        'loai_tai_lieu',
        'tieu_de',
        'mo_ta',
        'ten_file_goc',
        'duong_dan_file',
        'kich_thuoc_file',
        'loai_mime',
        'bao_mat',
        'ngay_het_han',
        'nguoi_tai_len_id',
        'thoi_gian_tai_len',
        'trang_thai',
    ];

    protected $casts = [
        'ngay_het_han' => 'date',
        'thoi_gian_tai_len' => 'datetime',
        'bao_mat' => 'boolean',
        'kich_thuoc_file' => 'integer',
    ];

    public function nguoi_dung(): BelongsTo
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_dung_id');
    }

    public function nguoi_tai_len(): BelongsTo
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_tai_len_id');
    }

    public function getDuongDanAttribute(): string
    {
        return asset('storage/' . $this->duong_dan_file);
    }

    public function getKichThuocAttribute(): string
    {
        $size = $this->kich_thuoc_file;
        if ($size < 1024) return $size . ' B';
        if ($size < 1048576) return round($size / 1024, 1) . ' KB';
        return round($size / 1048576, 1) . ' MB';
    }
}