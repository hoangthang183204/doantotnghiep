<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChiNhanhCongTy extends Model
{
    use HasFactory;
    
    protected $table = 'chi_nhanh_cong_ty';
    
    protected $fillable = [
        'ten',
        'ma',
        'dia_chi',
        'dien_thoai',
        'email',
        'truong_chi_nhanh_id',
        'trang_thai'
    ];
    
    protected $casts = [
        'trang_thai' => 'integer',
    ];
    
    public function truong_chi_nhanh()
    {
        return $this->belongsTo(NguoiDung::class, 'truong_chi_nhanh_id');
    }
    
    public function nguoi_dungs()
    {
        return $this->hasMany(NguoiDung::class, 'branch_id');
    }
}