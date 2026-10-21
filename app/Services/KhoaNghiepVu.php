<?php

namespace App\Services;

use Closure;

class KhoaNghiepVu
{
    /**
     * Giữ khóa Redis (hoặc cache database nếu Redis chưa chạy).
     * Trả về null khi đang có người khác giữ khóa.
     */
    public static function giu(string $ten, int $giay, Closure $viec)
    {
        $kho = DanhMucCache::kho();
        $khoa = 'lock:' . $ten;
        if (!$kho->add($khoa, auth()->id() ?: 'he-thong', $giay)) {
            return null;
        }

        try {
            return $viec();
        } finally {
            $kho->forget($khoa);
        }
    }
}
