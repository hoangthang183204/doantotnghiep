<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KhauTruLuong extends Model
{
    use HasFactory;
    
    protected $table = 'khau_tru_luong';
    
    protected $fillable = [
        'luong_nhan_vien_id',
        'loai_khau_tru',
        'so_tien',
        'ghi_chu'
    ];
    
    protected $casts = [
        'so_tien' => 'decimal:2',
    ];
    
    public function luongNhanVien()
    {
        return $this->belongsTo(LuongNhanVien::class, 'luong_nhan_vien_id');
    }
    
    // Dịch loại khấu trừ thành tiếng Việt
    public function getLoaiKhauTruVietNamAttribute()
    {
        $translations = [
            'bhxh' => 'Bảo hiểm xã hội',
            'bhyt' => 'Bảo hiểm y tế',
            'bhtn' => 'Bảo hiểm thất nghiệp',
            'thue_tncn' => 'Thuế thu nhập cá nhân',
            'khau_tru_khac' => 'Khấu trừ khác',
        ];
        
        return $translations[$this->loai_khau_tru] ?? $this->loai_khau_tru;
    }
}
