<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nguoi_dung', function (Blueprint $table) {
            $table->id();
            $table->string('ten_dang_nhap');
            $table->string('email')->unique();
            $table->string('password');
            $table->unsignedBigInteger('vai_tro_id')->nullable();
            $table->boolean('email_verified_at')->default(false);
            $table->string('remember_token')->nullable();
            $table->tinyInteger('trang_thai')->default(1);
            $table->enum('trang_thai_cong_viec', ['dang_lam', 'da_nghi'])->default('dang_lam');
            $table->timestamp('lan_dang_nhap_cuoi')->nullable();
            $table->string('ip_dang_nhap_cuoi', 45)->nullable();
            $table->unsignedBigInteger('phong_ban_id')->nullable();
            $table->unsignedBigInteger('chuc_vu_id')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->boolean('da_hoan_thanh_ho_so')->default(false);
            $table->boolean('dang_nhap_lan_dau')->default(true);
            $table->string('theme')->default('light');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nguoi_dung');
    }
};