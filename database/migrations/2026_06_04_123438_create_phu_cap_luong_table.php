<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('phu_cap_luong', function (Blueprint $table) {
            $table->id();
            $table->foreignId('luong_nhan_vien_id')->constrained('luong_nhan_vien')->cascadeOnDelete();
            $table->foreignId('phu_cap_id')->constrained('phu_cap');
            $table->decimal('so_tien', 12, 2);
            $table->text('ghi_chu')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('phu_cap_luong');
    }
};