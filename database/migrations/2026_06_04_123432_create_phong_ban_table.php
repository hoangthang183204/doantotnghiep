<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('phong_ban', function (Blueprint $table) {
            $table->id();
            $table->string('ten_phong_ban');
            $table->string('ma_phong_ban')->unique();
            $table->text('mo_ta')->nullable();
            $table->unsignedBigInteger('truong_phong_id')->nullable(); // KHÔNG có foreign key
            $table->decimal('ngan_sach', 15, 2)->nullable();
            $table->tinyInteger('trang_thai')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('phong_ban');
    }
};