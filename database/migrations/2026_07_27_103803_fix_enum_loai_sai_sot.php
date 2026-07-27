<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE yeu_cau_xem_xet_luongs
            MODIFY COLUMN loai_sai_sot ENUM(
                'cham_cong',
                'tang_ca',
                'phu_cap',
                'khau_tru',

                'cham_cong_tang_ca',
                'cham_cong_phu_cap',
                'cham_cong_khau_tru',

                'tang_ca_phu_cap',
                'tang_ca_khau_tru',

                'phu_cap_khau_tru',

                'cham_cong_tang_ca_phu_cap',
                'cham_cong_tang_ca_khau_tru',
                'cham_cong_phu_cap_khau_tru',

                'tang_ca_phu_cap_khau_tru',

                'tat_ca'
            ) NOT NULL DEFAULT 'cham_cong'
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE yeu_cau_xem_xet_luongs
            MODIFY COLUMN loai_sai_sot ENUM(
                'cham_cong',
                'tang_ca',
                'phu_cap',
                'khau_tru'
            ) NOT NULL DEFAULT 'cham_cong'
        ");
    }
};