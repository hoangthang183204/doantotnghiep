<?php

namespace App\Http\Controllers\TruongPhong;

use App\Http\Controllers\Controller;
use App\Models\DonXinVeSom;
use App\Models\PhongBan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DuyetVeSomController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Tìm phòng ban của Trưởng phòng
        $phongBan = $user->phongBan ?? PhongBan::where('truong_phong_id', $user->id)->first();

        if (!$phongBan) {
            return redirect()->back()->with('error', 'Bạn không quản lý phòng ban nào!');
        }

        // Query lấy danh sách đơn của nhân viên trong phòng, LOẠI TRỪ TRƯỞNG PHÒNG
        $query = DonXinVeSom::with(['nguoiDung.hoSo'])
            ->whereIn('nguoi_dung_id', function ($q) use ($phongBan) {
                $q->select('id')
                  ->from('nguoi_dung')
                  ->where('phong_ban_id', $phongBan->id);
            })
            ->where('nguoi_dung_id', '!=', $user->id); // 🔴 Không thấy đơn của chính mình

        // Lọc theo trạng thái
        if ($request->filled('trang_thai')) {
            $query->where('trang_thai', $request->trang_thai);
        }

        // Lọc theo từ ngày - đến ngày
        if ($request->filled('tu_ngay')) {
            $query->whereDate('ngay', '>=', $request->tu_ngay);
        }
        if ($request->filled('den_ngay')) {
            $query->whereDate('ngay', '<=', $request->den_ngay);
        }

        $donVeSoms = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('truong-phong.duyet-ve-som.index', compact('donVeSoms', 'phongBan'));
    }

    public function duyet($id)
    {
        $user = Auth::user();
        $don = DonXinVeSom::findOrFail($id);

        // Bảo mật 2 lớp: Không cho phép tự duyệt đơn mình
        if ($don->nguoi_dung_id == $user->id) {
            return response()->json(['success' => false, 'message' => 'Bạn không thể tự duyệt đơn của chính mình!'], 403);
        }

        if ($don->trang_thai != 'cho_duyet') {
            return response()->json(['success' => false, 'message' => 'Đơn này đã được xử lý!'], 400);
        }

        $don->trang_thai = 'da_duyet';
        $don->nguoi_duyet_id = $user->id;
        $don->thoi_gian_duyet = now();
        $don->save();

        return response()->json(['success' => true, 'message' => '✅ Đã duyệt đơn xin về sớm thành công!']);
    }

    public function tuChoi(Request $request, $id)
    {
        $request->validate(['ly_do_tu_choi' => 'required|string|min:5']);
        $user = Auth::user();
        $don = DonXinVeSom::findOrFail($id);

        if ($don->nguoi_dung_id == $user->id) {
            return response()->json(['success' => false, 'message' => 'Bạn không thể thao tác trên đơn của chính mình!'], 403);
        }

        if ($don->trang_thai != 'cho_duyet') {
            return response()->json(['success' => false, 'message' => 'Đơn này đã được xử lý!'], 400);
        }

        $don->trang_thai = 'tu_choi';
        $don->ly_do_tu_choi = $request->ly_do_tu_choi;
        $don->nguoi_duyet_id = $user->id;
        $don->thoi_gian_duyet = now();
        $don->save();

        return response()->json(['success' => true, 'message' => '❌ Đã từ chối đơn xin về sớm!']);
    }
    /**
 * Xem chi tiết đơn xin về sớm
 */
public function show($id)
{
    $user = Auth::user();

    $don = DonXinVeSom::with([
        'nguoiDung.hoSo',
        'nguoiDung.phongBan',
        'nguoiDuyet.hoSo',
        'chamCong'
    ])->findOrFail($id);

    // Kiểm tra bảo mật: Không xem được đơn của chính mình ở giao diện duyệt
    if ($don->nguoi_dung_id == $user->id) {
        return redirect()->route('truong-phong.duyet-ve-som.index')
            ->with('error', 'Bạn không thể duyệt hoặc xem đơn về sớm của chính mình tại đây!');
    }

    return view('truong-phong.duyet-ve-som.show', compact('don'));
}
}