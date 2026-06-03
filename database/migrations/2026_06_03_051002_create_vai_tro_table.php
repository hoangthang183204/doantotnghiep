<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vai_tro', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('ten_hien_thi');
            $table->text('mo_ta')->nullable();
            $table->tinyInteger('la_vai_tro_he_thong')->default(0);
            $table->tinyInteger('trang_thai')->default(1);
            $table->string('guard_name')->default('web');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vai_tro');
    }
};
