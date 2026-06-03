<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cong_viec', function (Blueprint $table) {
            $table->id();
            $table->string('ten_cong_viec');
            $table->text('mo_ta')->nullable();
            $table->enum('trang_thai', ['chua_bat_dau', 'dang_thuc_hien', 'da_hoan_thanh', 'tre_han', 'huy'])->default('chua_bat_dau');
            $table->enum('do_uu_tien', ['thap', 'trung_binh', 'cao', 'khan_cap'])->default('trung_binh');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cong_viec');
    }
};