<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UngVien extends Model
{
    protected $table = 'ung_vien';
    protected $fillable = [
    'ho',
    'ten',
    'email',
    'so_dien_thoai',
    'ma_ho_so',
    'luong_mong_muon',
    'tin_tuyen_dung_id',
    'trang_thai'
];

    public function tinTuyenDung()
    {
        return $this->belongsTo(TinTuyenDung::class, 'tin_tuyen_dung_id');
    }
}