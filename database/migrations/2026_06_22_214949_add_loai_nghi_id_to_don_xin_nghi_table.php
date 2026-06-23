<?php
// database/migrations/2026_06_22_xxxxxx_add_loai_nghi_id_to_don_xin_nghi_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('don_xin_nghi', function (Blueprint $table) {
            $table->unsignedBigInteger('loai_nghi_id')->nullable()->after('nguoi_dung_id');
            $table->foreign('loai_nghi_id')->references('id')->on('loai_nghi_phep')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('don_xin_nghi', function (Blueprint $table) {
            $table->dropForeign(['loai_nghi_id']);
            $table->dropColumn('loai_nghi_id');
        });
    }
};