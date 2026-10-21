<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NgayLe extends Model
{
    protected $table = 'ngay_le';

    protected $fillable = ['ten', 'ngay', 'loai', 'co_huong_luong', 'lap_lai_hang_nam', 'ghi_chu'];

    protected $casts = [
        'ngay' => 'date',
        'co_huong_luong' => 'boolean',
        'lap_lai_hang_nam' => 'boolean',
    ];
}
