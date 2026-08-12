<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('dang_ky_tang_ca', function (Blueprint $table) {
            // Cho phép NULL các trường
            $table->date('ngay_tang_ca')->nullable()->change();
            $table->time('gio_bat_dau')->nullable()->change();
            $table->time('gio_ket_thuc')->nullable()->change();
            $table->string('loai_tang_ca')->nullable()->change();
            $table->float('so_gio_tang_ca')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('dang_ky_tang_ca', function (Blueprint $table) {
            // Khôi phục lại
            $table->date('ngay_tang_ca')->nullable(false)->change();
            $table->time('gio_bat_dau')->nullable(false)->change();
            $table->time('gio_ket_thuc')->nullable(false)->change();
            $table->string('loai_tang_ca')->nullable(false)->change();
            $table->float('so_gio_tang_ca')->nullable(false)->change();
        });
    }
};