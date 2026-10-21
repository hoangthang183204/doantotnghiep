<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MauThongBao;
use App\Models\NguoiDung;
use App\Models\NhatKyThongBao;
use App\Services\ThongBaoDaKenhService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ThongBaoKenhController extends Controller
{
    public function index()
    {
        $kenh = DB::table('cau_hinh_kenh_thong_bao')->first();
        $maus = MauThongBao::orderBy('ma')->get();
        $nhatKy = NhatKyThongBao::orderByDesc('id')->limit(30)->get();
        $nhanViens = NguoiDung::with('hoSo')->where('trang_thai', 1)->orderBy('id')->get();
        return view('admin.thong-bao-kenh.index', compact('kenh', 'maus', 'nhatKy', 'nhanViens'));
    }

    public function updateKenh(Request $request)
    {
        $payload = [
            'email' => $request->boolean('email'),
            'zalo' => $request->boolean('zalo'),
            'zalo_access_token' => $request->input('zalo_access_token'),
            'sms' => $request->boolean('sms'),
            'sms_api_url' => $request->input('sms_api_url'),
            'sms_api_key' => $request->input('sms_api_key'),
            'push' => $request->boolean('push'),
            'updated_at' => now(),
        ];
        $row = DB::table('cau_hinh_kenh_thong_bao')->first();
        if ($row) {
            DB::table('cau_hinh_kenh_thong_bao')->where('id', $row->id)->update($payload);
        } else {
            $payload['created_at'] = now();
            DB::table('cau_hinh_kenh_thong_bao')->insert($payload);
        }
        return back()->with('success', 'Đã lưu kênh thông báo.');
    }

    public function updateMau(Request $request, $id)
    {
        $mau = MauThongBao::findOrFail($id);
        $mau->update($request->validate([
            'tieu_de' => 'required|string|max:200',
            'noi_dung' => 'required|string',
        ]));
        return back()->with('success', 'Đã lưu mẫu thông báo.');
    }

    public function guiThu(Request $request, ThongBaoDaKenhService $service)
    {
        $data = $request->validate([
            'nguoi_dung_id' => 'required|integer',
            'ma' => 'required|string',
        ]);
        $user = NguoiDung::findOrFail($data['nguoi_dung_id']);
        $service->guiTheoMau($user, $data['ma'], [
            'loai' => 'thử nghiệm',
            'trang_thai' => 'đã gửi',
            'ghi_chu' => 'Đây là thông báo thử.',
            'thang' => now()->month,
            'nam' => now()->year,
            'thuc_nhan' => '0 đ',
            'ma' => 'HD-THU',
            'url' => url('/employee/dashboard'),
        ]);
        return back()->with('success', 'Đã gửi thử. Xem nhật ký kênh bên dưới.');
    }
}
