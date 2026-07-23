<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FaceData extends Model
{
    use HasFactory;

    protected $table = 'face_data';

    protected $fillable = [
        'nguoi_dung_id',
        'embedding_path',
        'image_path',
        'face_id',
        'metadata',
        'is_active',
        'last_used_at'
    ];

    protected $casts = [
        'metadata' => 'array',
        'is_active' => 'boolean',
        'last_used_at' => 'datetime',
    ];

    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_dung_id');
    }

    public function chamCongFaces()
    {
        return $this->hasMany(chamCongFace::class, 'face_id', 'face_id');
    }
}