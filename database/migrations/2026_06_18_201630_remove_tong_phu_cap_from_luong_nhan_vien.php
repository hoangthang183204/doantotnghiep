<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('luong_nhan_vien', function (Blueprint $table) {
            $table->dropColumn('tong_phu_cap');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('luong_nhan_vien', function (Blueprint $table) {
            //
        });
    }
};
