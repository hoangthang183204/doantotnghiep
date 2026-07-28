<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // =============================================
        // 1️⃣ SỬA ENUM phuong_thuc_cham_cong - THÊM 'face'
        // =============================================
        DB::statement("ALTER TABLE cham_cong MODIFY COLUMN phuong_thuc_cham_cong ENUM('ip','wifi','mac','manual','face') NULL DEFAULT NULL");

        // =============================================
        // 2️⃣ THÊM CÁC CỘT MỚI NẾU CHƯA CÓ
        // =============================================
        
        // Thêm cột loai_cham_cong nếu chưa có
        if (!Schema::hasColumn('cham_cong', 'loai_cham_cong')) {
            Schema::table('cham_cong', function (Blueprint $table) {
                $table->enum('loai_cham_cong', ['check_in', 'check_out'])->default('check_in')->after('ca_lam_viec_id');
            });
        }

        // Thêm cột ly_do_ve_som nếu chưa có
        if (!Schema::hasColumn('cham_cong', 'ly_do_ve_som')) {
            Schema::table('cham_cong', function (Blueprint $table) {
                $table->text('ly_do_ve_som')->nullable()->after('loai_cham_cong');
            });
        }

        // Thêm cột da_xac_nhan_ve_som nếu chưa có
        if (!Schema::hasColumn('cham_cong', 'da_xac_nhan_ve_som')) {
            Schema::table('cham_cong', function (Blueprint $table) {
                $table->boolean('da_xac_nhan_ve_som')->default(0)->after('ly_do_ve_som');
            });
        }

        // =============================================
        // 3️⃣ TẠO BẢNG cham_cong_face NẾU CHƯA TỒN TẠI
        // =============================================
        if (!Schema::hasTable('cham_cong_face')) {
            Schema::create('cham_cong_face', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('nguoi_dung_id');
                $table->unsignedBigInteger('cham_cong_id')->nullable();
                $table->string('face_id')->nullable();
                $table->double('confidence')->default(0)->comment('Độ tin cậy (0-1)');
                $table->string('image_path')->nullable()->comment('Đường dẫn ảnh chụp khuôn mặt khi chấm công');
                $table->enum('loai', ['check_in', 'check_out']);
                $table->enum('trang_thai', ['thanh_cong', 'that_bai', 'can_xac_nhan'])->default('thanh_cong');
                $table->string('ip_address')->nullable();
                $table->text('device_info')->nullable();
                $table->text('error_message')->nullable();
                $table->timestamps();

                $table->foreign('nguoi_dung_id')->references('id')->on('nguoi_dung')->onDelete('cascade');
                $table->foreign('cham_cong_id')->references('id')->on('cham_cong')->onDelete('set null');
                
                $table->index('nguoi_dung_id');
                $table->index('face_id');
                $table->index(['loai', 'trang_thai']);
                $table->index('created_at');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // =============================================
        // 1️⃣ XÓA BẢNG cham_cong_face
        // =============================================
        Schema::dropIfExists('cham_cong_face');

        // =============================================
        // 2️⃣ XÓA CÁC CỘT ĐÃ THÊM
        // =============================================
        Schema::table('cham_cong', function (Blueprint $table) {
            if (Schema::hasColumn('cham_cong', 'loai_cham_cong')) {
                $table->dropColumn('loai_cham_cong');
            }
            if (Schema::hasColumn('cham_cong', 'ly_do_ve_som')) {
                $table->dropColumn('ly_do_ve_som');
            }
            if (Schema::hasColumn('cham_cong', 'da_xac_nhan_ve_som')) {
                $table->dropColumn('da_xac_nhan_ve_som');
            }
        });

        // =============================================
        // 3️⃣ KHÔI PHỤC ENUM (XÓA 'face')
        // =============================================
        DB::statement("ALTER TABLE cham_cong MODIFY COLUMN phuong_thuc_cham_cong ENUM('ip','wifi','mac','manual') NULL DEFAULT NULL");
    }
};