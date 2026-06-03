<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChucVu extends Model
{
    use HasFactory;
    
    protected $table = 'chuc_vu';
    
    protected $fillable = [
        'ten',
        'ma',
        'mo_ta',
        'luong_co_ban',
        'he_so_luong',
        'phong_ban_id',
        'trang_thai'
    ];
    
    protected $casts = [
        'luong_co_ban' => 'decimal:2',
        'he_so_luong' => 'decimal:2',
        'trang_thai' => 'integer',
    ];
    
    public function phong_ban()
    {
        return $this->belongsTo(PhongBan::class, 'phong_ban_id');
    }
    
    public function nguoi_dungs()
    {
        return $this->hasMany(NguoiDung::class, 'chuc_vu_id');
    }
}