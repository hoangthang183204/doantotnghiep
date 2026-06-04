<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ky_nang', function (Blueprint $table) {
            $table->id();
            $table->string('ten');
            $table->string('danh_muc')->nullable();
            $table->text('mo_ta')->nullable();
            $table->boolean('trang_thai')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ky_nang');
    }
};