<?php

namespace App\Observers;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;

class GhiNhatKyThayDoi
{
    private array $an = [
        'cmnd_cccd', 'so_ho_chieu', 'ma_so_thue', 'so_bhxh', 'so_tai_khoan',
        'password', 'cmnd_cccd_hash', 'ma_so_thue_hash',
    ];

    public function updated(Model $model): void
    {
        $moi = collect($model->getChanges())->except(['updated_at', 'created_at'])->all();
        if ($moi === []) {
            return;
        }

        $cu = [];
        foreach (array_keys($moi) as $cot) {
            $cu[$cot] = $model->getOriginal($cot);
            if (in_array($cot, $this->an, true)) {
                $cu[$cot] = $cu[$cot] === null ? null : '***';
                $moi[$cot] = $moi[$cot] === null ? null : '***';
            }
        }

        try {
            AuditLog::create([
                'nguoi_dung_id' => auth()->id(),
                'hanh_dong' => 'sua_gia_tri',
                'route' => class_basename($model) . '#' . $model->getKey(),
                'mo_ta' => 'Sửa ' . class_basename($model) . ' #' . $model->getKey(),
                'du_lieu' => ['cu' => $cu, 'moi' => $moi],
                'ip' => request()?->ip(),
            ]);
        } catch (\Throwable $e) {
            //
        }
    }
}
