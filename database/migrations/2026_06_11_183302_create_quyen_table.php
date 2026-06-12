<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('quyen', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('ten_hien_thi'); 
            $table->string('nhom')->nullable(); 
            $table->string('mo_ta')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('quyen');
    }
};