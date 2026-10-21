<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KetQuaDanhGia extends Model
{
    protected $table = 'ket_qua_danh_gia';
    protected $fillable = [
        'ky_danh_gia_id', 'nguoi_dung_id', 'diem', 'xep_loai', 'nhan_xet', 'nguoi_danh_gia_id',
    ];
    protected $casts = ['diem' => 'float'];

    public function ky()
    {
        return $this->belongsTo(KyDanhGia::class, 'ky_danh_gia_id');
    }

    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_dung_id');
    }

    public function mucTieus()
    {
        return $this->hasMany(MucTieuDanhGia::class, 'ket_qua_danh_gia_id');
    }

    public static function diemTrungBinhNam(int $nguoiDungId, int $nam): ?float
    {
        $diem = static::where('nguoi_dung_id', $nguoiDungId)
            ->whereNotNull('diem')
            ->whereHas('ky', function ($q) use ($nam) {
                $q->whereYear('den_ngay', $nam);
            })
            ->avg('diem');

        return $diem === null ? null : round((float) $diem, 2);
    }
}
