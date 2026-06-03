<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('chi_nhanh_cong_ty', function (Blueprint $table) {
            $table->id();
            $table->string('ten');
            $table->string('ma')->unique();
            $table->text('dia_chi')->nullable();
            $table->string('dien_thoai', 20)->nullable();
            $table->string('email')->nullable();
            $table->foreignId('truong_chi_nhanh_id')->nullable()->constrained('nguoi_dung')->nullOnDelete();
            $table->tinyInteger('trang_thai')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chi_nhanh_cong_ty');
    }
};
