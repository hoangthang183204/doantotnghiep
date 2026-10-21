<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Support\Facades\Crypt;

/**
 * Mã hóa cột nhạy cảm bằng APP_KEY. Dữ liệu cũ còn plaintext vẫn đọc được,
 * lần lưu sau sẽ được mã hóa.
 */
class DuLieuNhayCam implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes)
    {
        if ($value === null || $value === '') {
            return $value;
        }
        try {
            return Crypt::decryptString($value);
        } catch (\Throwable $e) {
            return $value;
        }
    }

    public function set($model, string $key, $value, array $attributes)
    {
        if ($value === null || $value === '') {
            return null;
        }
        try {
            Crypt::decryptString($value);
            return $value;
        } catch (\Throwable $e) {
            return Crypt::encryptString((string) $value);
        }
    }
}
