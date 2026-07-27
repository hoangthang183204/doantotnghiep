<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
            ALTER TABLE yeu_cau_xem_xet_luongs
            MODIFY COLUMN trang_thai ENUM(
                'cho_duyet',
                'da_duyet',
                'dang_sua',
                'da_cap_nhat',
                'tu_choi'
            ) NOT NULL DEFAULT 'cho_duyet'
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("
            ALTER TABLE yeu_cau_xem_xet_luongs
            MODIFY COLUMN trang_thai ENUM(
                'cho_duyet',
                'da_duyet',
                'tu_choi'
            ) NOT NULL DEFAULT 'cho_duyet'
        ");
    }
};