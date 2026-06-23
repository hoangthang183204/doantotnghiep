<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Bổ sung trạng thái 'da_chot' (đã chốt lương) vào bảng lương.
     * Vòng đời: dang_xu_ly (nháp) -> da_chot (khoá) -> da_tra (đã thanh toán)
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE bang_luong MODIFY trang_thai
            ENUM('dang_xu_ly','cho_duyet','da_duyet','da_chot','da_tra')
            NOT NULL DEFAULT 'dang_xu_ly'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE bang_luong MODIFY trang_thai
            ENUM('dang_xu_ly','cho_duyet','da_duyet','da_tra')
            NOT NULL DEFAULT 'dang_xu_ly'");
    }
};
