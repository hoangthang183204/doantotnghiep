<?php
// database/migrations/2026_01_15_000001_create_xin_ve_som_tang_ca_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('xin_ve_som_tang_ca', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dang_ky_tang_ca_id')->constrained('dang_ky_tang_ca')->onDelete('cascade');
            $table->foreignId('nguoi_dung_id')->constrained('nguoi_dung')->onDelete('cascade');
            $table->time('gio_ve_som_du_kien');
            $table->integer('so_phut_ve_som');
            $table->text('ly_do')->nullable();
            $table->string('trang_thai')->default('cho_duyet');
            $table->foreignId('nguoi_duyet_id')->nullable()->constrained('nguoi_dung');
            $table->timestamp('thoi_gian_duyet')->nullable();
            $table->text('ly_do_tu_choi')->nullable();
            $table->timestamps();
            
            $table->index(['dang_ky_tang_ca_id', 'trang_thai']);
            $table->index('nguoi_dung_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('xin_ve_som_tang_ca');
    }
};