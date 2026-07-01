<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('tin_tuyen_dung', function (Blueprint $table) {
            // Kiểm tra và thêm cột nếu chưa tồn tại
            if (!Schema::hasColumn('tin_tuyen_dung', 'mo_ta')) {
                $table->text('mo_ta')->nullable()->after('so_luong');
            }
            
            if (!Schema::hasColumn('tin_tuyen_dung', 'vi_tri')) {
                $table->string('vi_tri')->nullable()->after('tieu_de');
            }
            
            if (!Schema::hasColumn('tin_tuyen_dung', 'so_luong')) {
                $table->integer('so_luong')->default(1)->after('vi_tri');
            }
            
            if (!Schema::hasColumn('tin_tuyen_dung', 'yeu_cau')) {
                $table->text('yeu_cau')->nullable()->after('mo_ta');
            }
            
            if (!Schema::hasColumn('tin_tuyen_dung', 'quyen_loi')) {
                $table->text('quyen_loi')->nullable()->after('yeu_cau');
            }
            
            if (!Schema::hasColumn('tin_tuyen_dung', 'han_nop_ho_so')) {
                $table->date('han_nop_ho_so')->nullable()->after('quyen_loi');
            }
            
            if (!Schema::hasColumn('tin_tuyen_dung', 'ngay_dang')) {
                $table->timestamp('ngay_dang')->nullable()->after('trang_thai');
            }
            
            if (!Schema::hasColumn('tin_tuyen_dung', 'ngay_dung')) {
                $table->timestamp('ngay_dung')->nullable()->after('ngay_dang');
            }
        });
    }

    public function down()
    {
        Schema::table('tin_tuyen_dung', function (Blueprint $table) {
            $columns = ['mo_ta', 'vi_tri', 'so_luong', 'yeu_cau', 'quyen_loi', 'han_nop_ho_so', 'ngay_dang', 'ngay_dung'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('tin_tuyen_dung', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};