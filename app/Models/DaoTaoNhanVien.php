<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DaoTaoNhanVien extends Model
{
    protected $table = 'dao_tao_nhan_vien';

    protected $fillable = [
        'ho_so_id',
        'ten_khoa_hoc',
        'to_chuc',
        'ngay_bat_dau',
        'ngay_ket_thuc',
        'ket_qua',
        'co_chung_chi',
        'chi_phi',
        'ghi_chu',
    ];

    protected $casts = [
        'ngay_bat_dau' => 'date',
        'ngay_ket_thuc' => 'date',
        'co_chung_chi' => 'boolean',
        'chi_phi' => 'decimal:2',
    ];

    public function hoSo(): BelongsTo
    {
        return $this->belongsTo(HoSo::class, 'ho_so_id');
    }
}