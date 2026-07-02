<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\NguoiDung;
class SoDuPhep extends Model
{
    use HasFactory;

    protected $table = 'so_du_phep';

    protected $fillable = [
        'nguoi_dung_id',
        'nam',
        'phep_nam_moi',
        'phep_cu_chuyen_sang',
        'phep_da_dung'
    ];

    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_dung_id');
    }
}