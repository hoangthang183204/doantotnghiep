<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class YeuCauDoiCa extends Model
{
    protected $table = 'yeu_cau_doi_ca';

    protected $fillable = [
        'nguoi_dung_id', 'ngay', 'ca_hien_tai_id', 'ca_moi_id', 'ly_do',
        'trang_thai', 'nguoi_duyet_id', 'thoi_gian_duyet', 'ly_do_tu_choi',
    ];

    protected $casts = [
        'ngay' => 'date',
        'thoi_gian_duyet' => 'datetime',
    ];

    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_dung_id');
    }

    public function caHienTai()
    {
        return $this->belongsTo(CaLamViec::class, 'ca_hien_tai_id');
    }

    public function caMoi()
    {
        return $this->belongsTo(CaLamViec::class, 'ca_moi_id');
    }
}
