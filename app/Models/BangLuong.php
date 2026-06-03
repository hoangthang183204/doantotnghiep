<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BangLuong extends Model
{
    use HasFactory;
    
    protected $table = 'bang_luong';
    
    protected $fillable = [
        'ma_bang_luong',
        'loai_bang_luong',
        'nam',
        'thang',
        'trang_thai',
        'nguoi_xu_ly_id',
        'thoi_gian_xu_ly',
        'nguoi_phe_duyet_id',
        'thoi_gian_phe_duyet'
    ];
    
    protected $casts = [
        'nam' => 'integer',
        'thang' => 'integer',
        'thoi_gian_xu_ly' => 'datetime',
        'thoi_gian_phe_duyet' => 'datetime',
    ];
    
    public function nguoi_xu_ly()
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_xu_ly_id');
    }
    
    public function nguoi_phe_duyet()
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_phe_duyet_id');
    }
    
    public function luong_nhan_viens()
    {
        return $this->hasMany(LuongNhanVien::class, 'bang_luong_id');
    }
}