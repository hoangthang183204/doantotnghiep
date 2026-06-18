<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KyNangNhanVien extends Model
{
    protected $table = 'ky_nang_nhan_vien';

    protected $fillable = [
        'ho_so_id',
        'ten_ky_nang',
        'cap_do',
    ];

    public function hoSo(): BelongsTo
    {
        return $this->belongsTo(HoSo::class, 'ho_so_id');
    }

    // Lấy màu sắc cho cấp độ
    public function getMauCapDoAttribute(): string
    {
        return match ($this->cap_do) {
            'Cơ bản' => 'bg-gray-100 text-gray-700',
            'Trung cấp' => 'bg-blue-100 text-blue-700',
            'Thành thạo' => 'bg-green-100 text-green-700',
            'Chuyên gia' => 'bg-purple-100 text-purple-700',
            default => 'bg-gray-100 text-gray-700',
        };
    }

    // Lấy icon cho cấp độ
    public function getIconCapDoAttribute(): string
    {
        return match ($this->cap_do) {
            'Cơ bản' => '🌱',
            'Trung cấp' => '📚',
            'Thành thạo' => '⚡',
            'Chuyên gia' => '🏆',
            default => '📌',
        };
    }
}