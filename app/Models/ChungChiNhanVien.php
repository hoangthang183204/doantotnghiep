<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChungChiNhanVien extends Model
{
    protected $table = 'chung_chi_nhan_vien';

    protected $fillable = [
        'ho_so_id',
        'ten_chung_chi',
        'to_chuc_cap',
        'nam_cap',
        'ngay_het_han',
        'file_dinh_kem',
    ];

    protected $casts = [
        'ngay_het_han' => 'date',
        'nam_cap' => 'integer',
    ];

    public function hoSo(): BelongsTo
    {
        return $this->belongsTo(HoSo::class, 'ho_so_id');
    }

    // Kiểm tra còn hiệu lực không
    public function getConHanAttribute(): bool
    {
        if (!$this->ngay_het_han) return true;
        return $this->ngay_het_han->isFuture();
    }

    // Lấy trạng thái hiển thị
    public function getTrangThaiHienThiAttribute(): string
    {
        if (!$this->ngay_het_han) return '✅ Còn hiệu lực';
        return $this->con_han ? '✅ Còn hiệu lực' : '⛔ Hết hạn';
    }

    // Lấy màu trạng thái
    public function getMauTrangThaiAttribute(): string
    {
        if (!$this->ngay_het_han) return 'bg-green-100 text-green-700';
        return $this->con_han ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700';
    }
}