<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chuc_vu', function (Blueprint $table) {
            $table->id();
            $table->string('ten');
            $table->string('ma')->unique();
            $table->text('mo_ta')->nullable();
            $table->decimal('luong_co_ban', 15, 2)->default(0);
            $table->decimal('he_so_luong', 8, 2)->default(1);
            $table->foreignId('phong_ban_id')->nullable()->constrained('phong_ban')->nullOnDelete();
            $table->tinyInteger('trang_thai')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chuc_vu');
    }
};
