<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $table = 'audit_logs';

    protected $fillable = ['nguoi_dung_id', 'hanh_dong', 'route', 'mo_ta', 'du_lieu', 'ip'];

    protected $casts = ['du_lieu' => 'array'];

    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_dung_id');
    }
}
