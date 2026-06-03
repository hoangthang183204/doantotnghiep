<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoaiNghiPhep extends Model
{
    use HasFactory;
    
    protected $table = 'loai_nghi_phep';
    
    protected $fillable = [
        'ten',
        'ma',
        'mo_ta',
        'so_ngay_nam',
        'toi_da_ngay_lien_tiep',
        'so_ngay_bao_truoc',
        'cho_phep_chuyen_nam',
        'toi_da_ngay_chuyen',
        'gioi_tinh_ap_dung',
        'yeu_cau_giay_to',
        'co_luong',
        'trang_thai',
        'tinh_theo_ty_le',
        'nghi_che_do'
    ];
    
    protected $casts = [
        'so_ngay_nam' => 'integer',
        'yeu_cau_giay_to' => 'integer',
        'co_luong' => 'integer',
        'trang_thai' => 'integer',
    ];
    
    public function don_xin_nghis()
    {
        return $this->hasMany(DonXinNghi::class, 'loai_nghi_phep_id');
    }
}