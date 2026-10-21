<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('chot_du_lieu_thang')) {
            Schema::create('chot_du_lieu_thang', function (Blueprint $table) {
                $table->id();
                $table->unsignedTinyInteger('thang');
                $table->unsignedSmallInteger('nam');
                $table->string('trang_thai', 30)->default('da_chot_hr');
                $table->unsignedBigInteger('nguoi_chot_id')->nullable();
                $table->timestamp('thoi_gian_chot')->nullable();
                $table->string('ghi_chu')->nullable();
                $table->timestamps();
                $table->unique(['nam', 'thang']);
            });
        }

        $this->dongBoQuyen();
    }

    public function down(): void
    {
        Schema::dropIfExists('chot_du_lieu_thang');
    }

    private function dongBoQuyen(): void
    {
        $id = fn (string $name) => DB::table('vai_tro')->where('name', $name)->value('id');
        $quyenId = fn (string $name) => DB::table('quyen')->where('name', $name)->value('id');

        $go = function (?int $vaiTroId, array $tenQuyen) use ($quyenId) {
            if (!$vaiTroId) {
                return;
            }
            $ids = array_filter(array_map($quyenId, $tenQuyen));
            if ($ids) {
                DB::table('vai_tro_quyen')->where('vai_tro_id', $vaiTroId)->whereIn('quyen_id', $ids)->delete();
            }
        };

        $gan = function (?int $vaiTroId, array $tenQuyen) use ($quyenId) {
            if (!$vaiTroId) {
                return;
            }
            foreach ($tenQuyen as $ten) {
                $qid = $quyenId($ten);
                if (!$qid) {
                    continue;
                }
                $co = DB::table('vai_tro_quyen')->where('vai_tro_id', $vaiTroId)->where('quyen_id', $qid)->exists();
                if (!$co) {
                    DB::table('vai_tro_quyen')->insert([
                        'vai_tro_id' => $vaiTroId,
                        'quyen_id' => $qid,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        };

        $luong = DB::table('quyen')->where('name', 'like', 'salary%')->orWhere('name', 'like', 'allowance%')->pluck('name')->all();
        foreach (['quan_tri_vien', 'hr'] as $role) {
            $go($id($role), $luong);
        }

        $adminId = $id('admin');
        if ($adminId) {
            $gan($adminId, DB::table('quyen')->pluck('name')->all());
        }

        $camKeToan = [
            'attendance.overtime_approve', 'attendance.overtime_reject', 'attendance.overtime_bulk',
            'attendance.adjustment_approve', 'attendance.adjustment_reject', 'attendance.adjustment_bulk',
            'attendance.save_device',
            'leave.approve', 'leave.edit', 'leave.bulk', 'leave.reject', 'leave.delete',
            'leave.admin.approve', 'leave.admin.reject', 'leave.admin.bulk',
            'contract.create', 'contract.edit', 'contract.delete', 'contract.renew', 'contract.terminate',
            'contract.sign', 'contract.gui_ky', 'contract.huy', 'contract.tai_ky',
            'approval.recruitment', 'approval.leave', 'approval.overtime', 'approval.adjustment',
            'approval.recruitment_show', 'approval.recruitment_approve', 'approval.recruitment_reject',
            'hoso.create', 'hoso.edit', 'hoso.resign', 'hoso.activate',
            'tangluong.create', 'tangluong.store', 'tangluong.duyet', 'tangluong.tu-choi',
            'user.view', 'role.view', 'setting.permission', 'department.view', 'chucvu.view',
            'recruitment.index', 'recruitment.candidate', 'recruitment.passed',
            'khen_thuong.view',
        ];
        $go($id('ke_toan'), $camKeToan);

        $gan($id('ke_toan'), [
            'attendance.index', 'attendance.show', 'attendance.export', 'attendance.history',
            'leave.index', 'leave.show', 'leave.history', 'leave.admin.index', 'leave.admin.show',
            'contract.index', 'contract.show',
            'hoso.index', 'hoso.show',
            'salary.index', 'salary.show', 'salary.create', 'salary.calculate', 'salary.approve',
            'salary.export', 'salary.chot', 'salary.thanh_toan', 'salary.gui_email', 'salary.gui_tat_ca',
            'allowance.index',
        ]);
    }
};
