<?php
// database/migrations/2026_01_xx_xxxxxx_add_hoan_thanh_to_dang_ky_tang_ca.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('dang_ky_tang_ca', function (Blueprint $table) {
            $table->boolean('da_hoan_thanh')->default(false)->after('trang_thai');
            $table->timestamp('thoi_gian_hoan_thanh')->nullable()->after('da_hoan_thanh');
            $table->decimal('luong_tang_ca', 15, 2)->nullable()->after('thoi_gian_hoan_thanh');
        });
    }

    public function down()
    {
        Schema::table('dang_ky_tang_ca', function (Blueprint $table) {
            $table->dropColumn(['da_hoan_thanh', 'thoi_gian_hoan_thanh', 'luong_tang_ca']);
        });
    }
};