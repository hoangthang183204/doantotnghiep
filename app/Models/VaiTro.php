<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VaiTro extends Model
{
    use HasFactory;
    
    protected $table = 'vai_tro';
    
    protected $fillable = [
        'name',
        'ten_hien_thi',
        'mo_ta',
        'la_vai_tro_he_thong',
        'trang_thai',
        'guard_name'
    ];
    
    protected $casts = [
        'la_vai_tro_he_thong' => 'integer',
        'trang_thai' => 'integer',
    ];
    
    public function nguoi_dungs()
    {
        return $this->hasMany(NguoiDung::class, 'vai_tro_id');
    }
}