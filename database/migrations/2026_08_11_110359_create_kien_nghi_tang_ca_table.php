<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('kien_nghi_tang_ca', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('nguoi_dung_id');
            $table->text('ly_do');
            $table->string('trang_thai')->default('cho_xu_ly'); // cho_xu_ly, da_dong_y, tu_choi
            $table->unsignedBigInteger('nguoi_xu_ly_id')->nullable();
            $table->text('ly_do_tu_choi')->nullable();
            $table->timestamp('thoi_gian_xu_ly')->nullable();
            $table->unsignedBigInteger('don_tang_ca_id')->nullable();
            $table->timestamps();
            
            $table->foreign('nguoi_dung_id')->references('id')->on('nguoi_dung')->onDelete('cascade');
            $table->foreign('nguoi_xu_ly_id')->references('id')->on('nguoi_dung')->onDelete('set null');
            $table->foreign('don_tang_ca_id')->references('id')->on('dang_ky_tang_ca')->onDelete('set null');
            
            // Indexes
            $table->index('trang_thai');
            $table->index('nguoi_dung_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('kien_nghi_tang_ca');
    }
};