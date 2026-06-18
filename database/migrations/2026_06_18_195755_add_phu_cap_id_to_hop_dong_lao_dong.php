<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hop_dong_lao_dong', function (Blueprint $table) {
            $table->foreignId('phu_cap_id')
                ->nullable()
                ->after('phu_cap')
                ->constrained('phu_cap')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('hop_dong_lao_dong', function (Blueprint $table) {
            $table->dropForeign(['phu_cap_id']);
            $table->dropColumn('phu_cap_id');
        });
    }
};