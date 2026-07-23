<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChamCongFace extends Model
{
    use HasFactory;

    protected $table = 'cham_cong_face';

    protected $fillable = [
        'nguoi_dung_id',
        'cham_cong_id',
        'face_id',
        'confidence',
        'image_path',
        'loai',
        'trang_thai',
        'ip_address',
        'device_info',
        'error_message'
    ];

    protected $casts = [
        'confidence' => 'float',
        'device_info' => 'array',
    ];

    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_dung_id');
    }

    public function chamCong()
    {
        return $this->belongsTo(ChamCong::class, 'cham_cong_id');
    }
}