<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CauHinhBhxhThue extends Model
{
    protected $table = 'cau_hinh_bhxh_thue';

    protected $fillable = [
        'nam',
        'ty_le_bhxh_nld', 'ty_le_bhyt_nld', 'ty_le_bhtn_nld',
        'ty_le_bhxh_dn', 'ty_le_bhyt_dn', 'ty_le_bhtn_dn',
        'giam_tru_ban_than', 'giam_tru_npt', 'tran_dong_bhxh',
        'bac_thue', 'ghi_chu',
    ];

    protected $casts = [
        'nam' => 'integer',
        'ty_le_bhxh_nld' => 'float',
        'ty_le_bhyt_nld' => 'float',
        'ty_le_bhtn_nld' => 'float',
        'ty_le_bhxh_dn' => 'float',
        'ty_le_bhyt_dn' => 'float',
        'ty_le_bhtn_dn' => 'float',
        'giam_tru_ban_than' => 'float',
        'giam_tru_npt' => 'float',
        'tran_dong_bhxh' => 'float',
        'bac_thue' => 'array',
    ];

    public static function choNam(int $nam): self
    {
        $row = static::where('nam', $nam)->first()
            ?? static::where('nam', '<=', $nam)->orderByDesc('nam')->first();

        if ($row) {
            return $row;
        }

        $fallback = new static([
            'nam' => $nam,
            'ty_le_bhxh_nld' => 0.08,
            'ty_le_bhyt_nld' => 0.015,
            'ty_le_bhtn_nld' => 0.01,
            'ty_le_bhxh_dn' => 0.175,
            'ty_le_bhyt_dn' => 0.03,
            'ty_le_bhtn_dn' => 0.01,
            'giam_tru_ban_than' => $nam >= 2026 ? 15500000 : 11000000,
            'giam_tru_npt' => $nam >= 2026 ? 6200000 : 4400000,
            'tran_dong_bhxh' => 46800000,
            'bac_thue' => $nam >= 2026
                ? [
                    ['bac' => 1, 'den' => 10000000, 'thue_suat' => 0.05],
                    ['bac' => 2, 'den' => 30000000, 'thue_suat' => 0.10],
                    ['bac' => 3, 'den' => 60000000, 'thue_suat' => 0.20],
                    ['bac' => 4, 'den' => 100000000, 'thue_suat' => 0.30],
                    ['bac' => 5, 'den' => null, 'thue_suat' => 0.35],
                ]
                : [
                    ['bac' => 1, 'den' => 5000000, 'thue_suat' => 0.05],
                    ['bac' => 2, 'den' => 10000000, 'thue_suat' => 0.10],
                    ['bac' => 3, 'den' => 18000000, 'thue_suat' => 0.15],
                    ['bac' => 4, 'den' => 32000000, 'thue_suat' => 0.20],
                    ['bac' => 5, 'den' => 52000000, 'thue_suat' => 0.25],
                    ['bac' => 6, 'den' => 80000000, 'thue_suat' => 0.30],
                    ['bac' => 7, 'den' => null, 'thue_suat' => 0.35],
                ],
        ]);

        return $fallback;
    }

    public function bacThue(): array
    {
        $bac = $this->bac_thue;
        return is_array($bac) && count($bac) ? $bac : [];
    }

    public function tyLeNld(): float
    {
        return (float) $this->ty_le_bhxh_nld + (float) $this->ty_le_bhyt_nld + (float) $this->ty_le_bhtn_nld;
    }

    public function tyLeDn(): float
    {
        return (float) $this->ty_le_bhxh_dn + (float) $this->ty_le_bhyt_dn + (float) $this->ty_le_bhtn_dn;
    }
}
