<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('ung_vien', function (Blueprint $table) {
            if (!Schema::hasColumn('ung_vien', 'phong_ban_id')) {
                $table->foreignId('phong_ban_id')->nullable()->constrained('phong_ban')->nullOnDelete();
            }
        });
    }

    public function down()
    {
        Schema::table('ung_vien', function (Blueprint $table) {
            if (Schema::hasColumn('ung_vien', 'phong_ban_id')) {
                $table->dropForeign(['phong_ban_id']);
                $table->dropColumn('phong_ban_id');
            }
        });
    }
};