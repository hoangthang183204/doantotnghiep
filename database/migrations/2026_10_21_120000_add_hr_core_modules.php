<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('luong_nhan_vien', function (Blueprint $table) {
            if (!Schema::hasColumn('luong_nhan_vien', 'muc_dong_bhxh')) {
                $table->decimal('muc_dong_bhxh', 15, 2)->default(0)->after('luong_co_ban');
            }
            if (!Schema::hasColumn('luong_nhan_vien', 'bhxh_dn')) {
                $table->decimal('bhxh_dn', 15, 2)->default(0);
            }
            if (!Schema::hasColumn('luong_nhan_vien', 'bhyt_dn')) {
                $table->decimal('bhyt_dn', 15, 2)->default(0);
            }
            if (!Schema::hasColumn('luong_nhan_vien', 'bhtn_dn')) {
                $table->decimal('bhtn_dn', 15, 2)->default(0);
            }
            if (!Schema::hasColumn('luong_nhan_vien', 'tong_bao_hiem_dn')) {
                $table->decimal('tong_bao_hiem_dn', 15, 2)->default(0);
            }
        });

        Schema::table('cham_cong_face', function (Blueprint $table) {
            if (!Schema::hasColumn('cham_cong_face', 'latitude')) {
                $table->decimal('latitude', 10, 7)->nullable();
            }
            if (!Schema::hasColumn('cham_cong_face', 'longitude')) {
                $table->decimal('longitude', 10, 7)->nullable();
            }
        });

        Schema::table('ca_lam_viec', function (Blueprint $table) {
            if (!Schema::hasColumn('ca_lam_viec', 'loai')) {
                $table->string('loai', 30)->default('hanh_chinh');
            }
            if (!Schema::hasColumn('ca_lam_viec', 'mo_ta')) {
                $table->string('mo_ta')->nullable();
            }
        });

        if (!Schema::hasTable('cau_hinh_bhxh_thue')) {
            Schema::create('cau_hinh_bhxh_thue', function (Blueprint $table) {
                $table->id();
                $table->unsignedSmallInteger('nam')->unique();
                $table->decimal('ty_le_bhxh_nld', 6, 4)->default(0.08);
                $table->decimal('ty_le_bhyt_nld', 6, 4)->default(0.015);
                $table->decimal('ty_le_bhtn_nld', 6, 4)->default(0.01);
                $table->decimal('ty_le_bhxh_dn', 6, 4)->default(0.175);
                $table->decimal('ty_le_bhyt_dn', 6, 4)->default(0.03);
                $table->decimal('ty_le_bhtn_dn', 6, 4)->default(0.01);
                $table->decimal('giam_tru_ban_than', 15, 2);
                $table->decimal('giam_tru_npt', 15, 2);
                $table->decimal('tran_dong_bhxh', 15, 2)->default(46800000);
                $table->json('bac_thue');
                $table->string('ghi_chu')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('cau_hinh_lich_lam')) {
            Schema::create('cau_hinh_lich_lam', function (Blueprint $table) {
                $table->id();
                $table->time('gio_vao')->default('08:00:00');
                $table->time('gio_ra')->default('17:30:00');
                $table->time('gio_nghi_trua_bat_dau')->default('12:00:00');
                $table->time('gio_nghi_trua_ket_thuc')->default('13:30:00');
                $table->decimal('so_gio_chuan', 4, 1)->default(8);
                $table->json('ngay_lam');
                $table->unsignedTinyInteger('ngay_cong_chuan')->default(22);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('ngay_le')) {
            Schema::create('ngay_le', function (Blueprint $table) {
                $table->id();
                $table->string('ten');
                $table->date('ngay');
                $table->string('loai', 20)->default('quoc_gia');
                $table->boolean('co_huong_luong')->default(true);
                $table->boolean('lap_lai_hang_nam')->default(false);
                $table->string('ghi_chu')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('audit_logs')) {
            Schema::create('audit_logs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('nguoi_dung_id')->nullable()->index();
                $table->string('hanh_dong', 20);
                $table->string('route')->nullable();
                $table->string('mo_ta')->nullable();
                $table->json('du_lieu')->nullable();
                $table->string('ip', 45)->nullable();
                $table->timestamps();
                $table->index('created_at');
            });
        }

        if (!Schema::hasTable('phan_cong_ca')) {
            Schema::create('phan_cong_ca', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('nguoi_dung_id')->index();
                $table->unsignedBigInteger('ca_lam_viec_id');
                $table->date('ngay');
                $table->string('ghi_chu')->nullable();
                $table->timestamps();
                $table->unique(['nguoi_dung_id', 'ngay']);
            });
        }

        if (!Schema::hasTable('yeu_cau_doi_ca')) {
            Schema::create('yeu_cau_doi_ca', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('nguoi_dung_id')->index();
                $table->date('ngay');
                $table->unsignedBigInteger('ca_hien_tai_id')->nullable();
                $table->unsignedBigInteger('ca_moi_id');
                $table->string('ly_do');
                $table->string('trang_thai', 20)->default('cho_duyet');
                $table->unsignedBigInteger('nguoi_duyet_id')->nullable();
                $table->timestamp('thoi_gian_duyet')->nullable();
                $table->string('ly_do_tu_choi')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('mau_checklist')) {
            Schema::create('mau_checklist', function (Blueprint $table) {
                $table->id();
                $table->string('ten');
                $table->string('loai', 20);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('hang_muc_checklist')) {
            Schema::create('hang_muc_checklist', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('mau_checklist_id');
                $table->string('ten');
                $table->unsignedSmallInteger('thu_tu')->default(1);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('phieu_checklist')) {
            Schema::create('phieu_checklist', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('nguoi_dung_id')->index();
                $table->string('loai', 20);
                $table->string('trang_thai', 20)->default('dang_lam');
                $table->date('ngay_bat_dau')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('viec_checklist')) {
            Schema::create('viec_checklist', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('phieu_checklist_id')->index();
                $table->string('ten');
                $table->unsignedBigInteger('nguoi_phu_trach_id')->nullable();
                $table->date('han_hoan_thanh')->nullable();
                $table->string('trang_thai', 20)->default('chua_xong');
                $table->string('ghi_chu')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('tai_san')) {
            Schema::create('tai_san', function (Blueprint $table) {
                $table->id();
                $table->string('ma')->unique();
                $table->string('ten');
                $table->string('loai', 30);
                $table->string('serial')->nullable();
                $table->string('tinh_trang', 20)->default('san_sang');
                $table->string('ghi_chu')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('cap_phat_tai_san')) {
            Schema::create('cap_phat_tai_san', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('tai_san_id')->index();
                $table->unsignedBigInteger('nguoi_dung_id')->index();
                $table->date('ngay_cap');
                $table->date('ngay_thu')->nullable();
                $table->unsignedBigInteger('nguoi_cap_id')->nullable();
                $table->string('ghi_chu')->nullable();
                $table->string('trang_thai', 20)->default('dang_cap');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('mau_thong_bao')) {
            Schema::create('mau_thong_bao', function (Blueprint $table) {
                $table->id();
                $table->string('ma')->unique();
                $table->string('ten');
                $table->string('tieu_de');
                $table->text('noi_dung');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('cau_hinh_kenh_thong_bao')) {
            Schema::create('cau_hinh_kenh_thong_bao', function (Blueprint $table) {
                $table->id();
                $table->boolean('email')->default(true);
                $table->boolean('zalo')->default(false);
                $table->string('zalo_access_token')->nullable();
                $table->boolean('sms')->default(false);
                $table->string('sms_api_url')->nullable();
                $table->string('sms_api_key')->nullable();
                $table->boolean('push')->default(true);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('nhat_ky_thong_bao')) {
            Schema::create('nhat_ky_thong_bao', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('nguoi_dung_id')->nullable()->index();
                $table->string('kenh', 20);
                $table->string('tieu_de')->nullable();
                $table->text('noi_dung')->nullable();
                $table->string('trang_thai', 20);
                $table->string('chi_tiet')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('push_subscriptions')) {
            Schema::create('push_subscriptions', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('nguoi_dung_id')->index();
                $table->string('endpoint', 1000);
                $table->string('p256dh')->nullable();
                $table->string('auth')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('ky_danh_gia')) {
            Schema::create('ky_danh_gia', function (Blueprint $table) {
                $table->id();
                $table->string('ten');
                $table->string('loai', 20);
                $table->date('tu_ngay');
                $table->date('den_ngay');
                $table->string('trang_thai', 20)->default('dang_mo');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('ket_qua_danh_gia')) {
            Schema::create('ket_qua_danh_gia', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('ky_danh_gia_id')->index();
                $table->unsignedBigInteger('nguoi_dung_id')->index();
                $table->decimal('diem', 5, 2)->nullable();
                $table->string('xep_loai', 20)->nullable();
                $table->text('nhan_xet')->nullable();
                $table->unsignedBigInteger('nguoi_danh_gia_id')->nullable();
                $table->timestamps();
                $table->unique(['ky_danh_gia_id', 'nguoi_dung_id']);
            });
        }

        if (!Schema::hasTable('muc_tieu_danh_gia')) {
            Schema::create('muc_tieu_danh_gia', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('ket_qua_danh_gia_id')->index();
                $table->string('ten');
                $table->unsignedTinyInteger('trong_so')->default(0);
                $table->string('muc_tieu')->nullable();
                $table->string('thuc_dat')->nullable();
                $table->decimal('diem', 5, 2)->nullable();
                $table->timestamps();
            });
        }

        $this->seed();
    }

    public function down(): void
    {
        Schema::dropIfExists('muc_tieu_danh_gia');
        Schema::dropIfExists('ket_qua_danh_gia');
        Schema::dropIfExists('ky_danh_gia');
        Schema::dropIfExists('push_subscriptions');
        Schema::dropIfExists('nhat_ky_thong_bao');
        Schema::dropIfExists('cau_hinh_kenh_thong_bao');
        Schema::dropIfExists('mau_thong_bao');
        Schema::dropIfExists('cap_phat_tai_san');
        Schema::dropIfExists('tai_san');
        Schema::dropIfExists('viec_checklist');
        Schema::dropIfExists('phieu_checklist');
        Schema::dropIfExists('hang_muc_checklist');
        Schema::dropIfExists('mau_checklist');
        Schema::dropIfExists('yeu_cau_doi_ca');
        Schema::dropIfExists('phan_cong_ca');
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('ngay_le');
        Schema::dropIfExists('cau_hinh_lich_lam');
        Schema::dropIfExists('cau_hinh_bhxh_thue');
    }

    private function seed(): void
    {
        $bac7 = json_encode([
            ['bac' => 1, 'den' => 5000000, 'thue_suat' => 0.05],
            ['bac' => 2, 'den' => 10000000, 'thue_suat' => 0.10],
            ['bac' => 3, 'den' => 18000000, 'thue_suat' => 0.15],
            ['bac' => 4, 'den' => 32000000, 'thue_suat' => 0.20],
            ['bac' => 5, 'den' => 52000000, 'thue_suat' => 0.25],
            ['bac' => 6, 'den' => 80000000, 'thue_suat' => 0.30],
            ['bac' => 7, 'den' => null, 'thue_suat' => 0.35],
        ]);
        $bac5 = json_encode([
            ['bac' => 1, 'den' => 10000000, 'thue_suat' => 0.05],
            ['bac' => 2, 'den' => 30000000, 'thue_suat' => 0.10],
            ['bac' => 3, 'den' => 60000000, 'thue_suat' => 0.20],
            ['bac' => 4, 'den' => 100000000, 'thue_suat' => 0.30],
            ['bac' => 5, 'den' => null, 'thue_suat' => 0.35],
        ]);

        foreach ([2024, 2025] as $nam) {
            DB::table('cau_hinh_bhxh_thue')->updateOrInsert(['nam' => $nam], [
                'ty_le_bhxh_nld' => 0.08,
                'ty_le_bhyt_nld' => 0.015,
                'ty_le_bhtn_nld' => 0.01,
                'ty_le_bhxh_dn' => 0.175,
                'ty_le_bhyt_dn' => 0.03,
                'ty_le_bhtn_dn' => 0.01,
                'giam_tru_ban_than' => 11000000,
                'giam_tru_npt' => 4400000,
                'tran_dong_bhxh' => 46800000,
                'bac_thue' => $bac7,
                'ghi_chu' => 'NLĐ 10.5%, DN 21.5%, giảm trừ 11 triệu / 4.4 triệu, biểu 7 bậc',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        DB::table('cau_hinh_bhxh_thue')->updateOrInsert(['nam' => 2026], [
            'ty_le_bhxh_nld' => 0.08,
            'ty_le_bhyt_nld' => 0.015,
            'ty_le_bhtn_nld' => 0.01,
            'ty_le_bhxh_dn' => 0.175,
            'ty_le_bhyt_dn' => 0.03,
            'ty_le_bhtn_dn' => 0.01,
            'giam_tru_ban_than' => 15500000,
            'giam_tru_npt' => 6200000,
            'tran_dong_bhxh' => 46800000,
            'bac_thue' => $bac5,
            'ghi_chu' => 'Kỳ tính thuế 2026: giảm trừ 15.5 triệu / 6.2 triệu, biểu 5 bậc. BHXH giữ 10.5% / 21.5%',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        if (DB::table('cau_hinh_lich_lam')->count() === 0) {
            DB::table('cau_hinh_lich_lam')->insert([
                'gio_vao' => '08:00:00',
                'gio_ra' => '17:30:00',
                'gio_nghi_trua_bat_dau' => '12:00:00',
                'gio_nghi_trua_ket_thuc' => '13:30:00',
                'so_gio_chuan' => 8,
                'ngay_lam' => json_encode([1, 2, 3, 4, 5]),
                'ngay_cong_chuan' => 22,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $les = [
            ['Tết Dương lịch', '2026-01-01', 'quoc_gia', true],
            ['Tết Nguyên đán', '2026-02-16', 'quoc_gia', false],
            ['Tết Nguyên đán', '2026-02-17', 'quoc_gia', false],
            ['Tết Nguyên đán', '2026-02-18', 'quoc_gia', false],
            ['Tết Nguyên đán', '2026-02-19', 'quoc_gia', false],
            ['Tết Nguyên đán', '2026-02-20', 'quoc_gia', false],
            ['Giỗ Tổ Hùng Vương', '2026-04-26', 'quoc_gia', false],
            ['Ngày Giải phóng miền Nam', '2026-04-30', 'quoc_gia', true],
            ['Ngày Quốc tế Lao động', '2026-05-01', 'quoc_gia', true],
            ['Quốc khánh', '2026-09-02', 'quoc_gia', true],
        ];
        foreach ($les as [$ten, $ngay, $loai, $lap]) {
            DB::table('ngay_le')->updateOrInsert(
                ['ten' => $ten, 'ngay' => $ngay],
                [
                    'loai' => $loai,
                    'co_huong_luong' => true,
                    'lap_lai_hang_nam' => $lap,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        if (DB::table('mau_checklist')->count() === 0) {
            $on = DB::table('mau_checklist')->insertGetId([
                'ten' => 'Nhận việc',
                'loai' => 'onboarding',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $off = DB::table('mau_checklist')->insertGetId([
                'ten' => 'Nghỉ việc',
                'loai' => 'offboarding',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $items = [
                [$on, 'Tạo tài khoản và phân quyền'],
                [$on, 'Ký hợp đồng lao động'],
                [$on, 'Cấp thiết bị / thẻ / đồng phục'],
                [$on, 'Đào tạo hội nhập'],
                [$off, 'Thu hồi tài sản'],
                [$off, 'Bàn giao công việc'],
                [$off, 'Chốt công và lương'],
                [$off, 'Khóa tài khoản'],
            ];
            foreach ($items as $i => [$mau, $ten]) {
                DB::table('hang_muc_checklist')->insert([
                    'mau_checklist_id' => $mau,
                    'ten' => $ten,
                    'thu_tu' => $i + 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $maus = [
            ['don_duyet', 'Đơn được duyệt', 'Đơn của bạn đã được xử lý', 'Đơn {{loai}} đã {{trang_thai}}. {{ghi_chu}}'],
            ['phieu_luong', 'Phiếu lương', 'Phiếu lương tháng {{thang}}/{{nam}}', 'Lương thực nhận: {{thuc_nhan}}. Vui lòng xem chi tiết trên hệ thống.'],
            ['hop_dong_ky', 'Hợp đồng cần ký', 'Bạn có hợp đồng cần ký', 'Hợp đồng {{ma}} đang chờ bạn ký trên điện thoại hoặc máy tính.'],
        ];
        foreach ($maus as [$ma, $ten, $tieuDe, $noiDung]) {
            DB::table('mau_thong_bao')->updateOrInsert(['ma' => $ma], [
                'ten' => $ten,
                'tieu_de' => $tieuDe,
                'noi_dung' => $noiDung,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if (DB::table('cau_hinh_kenh_thong_bao')->count() === 0) {
            DB::table('cau_hinh_kenh_thong_bao')->insert([
                'email' => true,
                'zalo' => false,
                'sms' => false,
                'push' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
};
