<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('ung_vien', function (Blueprint $table) {
            if (!Schema::hasColumn('ung_vien', 'kinh_nghiem')) {
                $table->decimal('kinh_nghiem', 4, 1)->default(0)->after('phong_ban_id');
            }
        });
    }

    public function down()
    {
        Schema::table('ung_vien', function (Blueprint $table) {
            if (Schema::hasColumn('ung_vien', 'kinh_nghiem')) {
                $table->dropColumn('kinh_nghiem');
            }
        });
    }
};