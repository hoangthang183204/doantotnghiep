<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ViecChecklist extends Model
{
    protected $table = 'viec_checklist';
    protected $fillable = [
        'phieu_checklist_id', 'ten', 'nguoi_phu_trach_id',
        'han_hoan_thanh', 'trang_thai', 'ghi_chu',
    ];
    protected $casts = ['han_hoan_thanh' => 'date'];

    public function phieu()
    {
        return $this->belongsTo(PhieuChecklist::class, 'phieu_checklist_id');
    }

    public function nguoiPhuTrach()
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_phu_trach_id');
    }
}
