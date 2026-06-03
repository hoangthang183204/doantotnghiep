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
            $table->foreignId('vai_tro_id')->nullable()->constrained('vai_tro')->nullOnDelete();
            $table->tinyInteger('email_verified_at')->nullable();
            $table->string('remember_token')->nullable();
            $table->tinyInteger('trang_thai')->default(1);
            $table->enum('trang_thai_cong_viec', ['dang_lam', 'nghi_viec', 'tam_nghi'])->default('dang_lam');
            $table->timestamp('lan_dang_nhap_cuoi')->nullable();
            $table->string('ip_dang_nhap_cuoi', 45)->nullable();
            $table->foreignId('phong_ban_id')->nullable()->constrained('phong_ban')->nullOnDelete();
            $table->foreignId('chuc_vu_id')->nullable()->constrained('chuc_vu')->nullOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained('chi_nhanh_cong_ty')->nullOnDelete();
            $table->tinyInteger('da_hoan_thanh_ho_so')->default(0);
            $table->tinyInteger('dang_nhap_lan_dau')->default(1);
            $table->string('theme')->default('light');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nguoi_dung');
    }
};