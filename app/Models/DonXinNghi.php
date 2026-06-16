<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonXinNghi extends Model
{
    use HasFactory;
    
    protected $table = 'don_xin_nghi';
    
    protected $fillable = [
        'ma_don_nghi',
        'nguoi_dung_id',
        'loai_nghi_phep_id',
        'ngay_bat_dau',
        'ngay_ket_thuc',
        'so_ngay_nghi',
        'ly_do',
        'tai_lieu_ho_tro',
        'lien_he_khan_cap',
        'sdt_khan_cap',
        'ban_giao_cho_id',
        'ghi_chu_ban_giao',
        'trang_thai',
        'cap_duyet_hien_tai'
    ];
    
    protected $casts = [
        'ngay_bat_dau' => 'date',
        'ngay_ket_thuc' => 'date',
        'tai_lieu_ho_tro' => 'array',
        'so_ngay_nghi' => 'decimal:2',
    ];
    
    public function nguoi_dung()
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_dung_id');
    }
    
    public function loai_nghi_phep()
    {
        return $this->belongsTo(LoaiNghiPhep::class, 'loai_nghi_phep_id');
    }
    
    public function ban_giao_cho()
    {
        return $this->belongsTo(NguoiDung::class, 'ban_giao_cho_id');
    }
    public function lichSuDuyet()
{
    return $this->hasMany(
        LichSuDuyetDonNghi::class,
        'don_xin_nghi_id'
    )->orderBy('cap_duyet');
}
}