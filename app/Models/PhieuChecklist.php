<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhieuChecklist extends Model
{
    protected $table = 'phieu_checklist';
    protected $fillable = ['nguoi_dung_id', 'loai', 'trang_thai', 'ngay_bat_dau'];
    protected $casts = ['ngay_bat_dau' => 'date'];

    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_dung_id');
    }

    public function viecs()
    {
        return $this->hasMany(ViecChecklist::class, 'phieu_checklist_id');
    }
}
