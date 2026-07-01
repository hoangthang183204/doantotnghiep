<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TinTuyenDung extends Model
{
    use HasFactory;

    protected $table = 'tin_tuyen_dung';

    protected $fillable = [
        'tieu_de',
        'ma',
        'phong_ban_id',
        'chuc_vu_id',
        'vai_tro_id',
        'loai_hop_dong',
        'cap_do_kinh_nghiem',
        'kinh_nghiem_toi_thieu',
        'kinh_nghiem_toi_da',
        'luong_toi_thieu',
        'luong_toi_da',
        'so_vi_tri',
        'mo_ta_cong_viec',
        'yeu_cau',
        'phuc_loi',
        'ky_nang_yeu_cau',
        'trinh_do_hoc_van',
        'han_nop_ho_so',
        'lam_viec_tu_xa',
        'tuyen_gap',
        'trang_thai',
        'nguoi_dang_id',
        'thoi_gian_dang',
    ];

    protected $casts = [
        'yeu_cau' => 'array',
        'phuc_loi' => 'array',
        'ky_nang_yeu_cau' => 'array',
        'han_nop_ho_so' => 'date',
        'thoi_gian_dang' => 'datetime',
        'luong_toi_thieu' => 'decimal:2',
        'luong_toi_da' => 'decimal:2',
        'lam_viec_tu_xa' => 'boolean',
        'tuyen_gap' => 'boolean',
    ];

    // Accessor cho trạng thái tiếng Việt
    public function getTrangThaiTextAttribute()
    {
        $statuses = [
            'nhap' => 'Nháp',
            'dang_tuyen' => 'Đang tuyển',
            'tam_dung' => 'Tạm dừng',
            'ket_thuc' => 'Kết thúc',
        ];

        return $statuses[$this->trang_thai] ?? $this->trang_thai;
    }

    // Accessor cho loại hợp đồng tiếng Việt
    public function getLoaiHopDongTextAttribute()
    {
        $types = [
            'thu_viec' => 'Thử việc',
            'xac_dinh_thoi_han' => 'Xác định thời hạn',
            'khong_xac_dinh_thoi_han' => 'Không xác định thời hạn',
        ];

        return $types[$this->loai_hop_dong] ?? $this->loai_hop_dong;
    }

    // Accessor cho cấp độ kinh nghiệm tiếng Việt
    public function getCapDoKinhNghiemTextAttribute()
    {
        $levels = [
            'intern' => 'Thực tập sinh',
            'fresher' => 'Fresher',
            'junior' => 'Junior',
            'middle' => 'Middle',
            'senior' => 'Senior',
        ];

        return $levels[$this->cap_do_kinh_nghiem] ?? $this->cap_do_kinh_nghiem;
    }

    // Quan hệ
    public function phongBan()
    {
        return $this->belongsTo(PhongBan::class, 'phong_ban_id');
    }

    public function chucVu()
    {
        return $this->belongsTo(ChucVu::class, 'chuc_vu_id');
    }

    public function vaiTro()
    {
        return $this->belongsTo(VaiTro::class, 'vai_tro_id');
    }

    public function nguoiDang()
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_dang_id');
    }

    public function ungViens()
    {
        return $this->hasMany(UngVien::class, 'tin_tuyen_dung_id');
    }
}