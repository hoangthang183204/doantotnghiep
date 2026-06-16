<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiaChiIPDuocPhep extends Model
{
    protected $table = 'dia_chi_ip_duoc_phep';

    protected $fillable = [
        'dia_chi_ip',
        'dai_ip_bat_dau',
        'dai_ip_ket_thuc',
        'ten_vi_tri',
        'mo_ta',
        'chi_nhanh_id',
        'trang_thai',
    ];

    protected $casts = [
        'trang_thai' => 'integer',
    ];

    public function chi_nhanh()
    {
        return $this->belongsTo(ChiNhanhCongTy::class, 'chi_nhanh_id');
    }

    /**
     * Kiểm tra IP có được phép không
     */
    public static function isIPAllowed(string $ip): bool
    {
        // Kiểm tra IP chính xác
        $exact = self::where('dia_chi_ip', $ip)
            ->where('trang_thai', 1)
            ->exists();

        if ($exact) {
            return true;
        }

        // Kiểm tra khoảng IP
        $range = self::where('trang_thai', 1)
            ->where(function ($query) use ($ip) {
                $query->whereRaw('INET_ATON(?) BETWEEN INET_ATON(dai_ip_bat_dau) AND INET_ATON(dai_ip_ket_thuc)', [$ip]);
            })
            ->exists();

        return $range;
    }

    /**
     * Lấy tất cả IP được phép theo chi nhánh
     */
    public static function getIPsByBranch(?int $branchId = null)
    {
        $query = self::where('trang_thai', 1);

        if ($branchId) {
            $query->where('chi_nhanh_id', $branchId);
        }

        return $query->get();
    }
}