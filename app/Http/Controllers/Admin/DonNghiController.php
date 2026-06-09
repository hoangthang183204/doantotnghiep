<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DonXinNghi; // Đảm bảo bạn đã import đúng Model
use Illuminate\Support\Facades\DB;

class DonNghiController extends Controller
{
    // 1. Hiển thị danh sách
    public function index()
    {
        // 1. Lấy danh sách đơn, sắp xếp mới nhất lên đầu và phân trang
        $danhSachDon = DonXinNghi::orderBy('created_at', 'desc')->paginate(10);
        
        // 2. Đếm số lượng theo từng trạng thái để hiển thị lên thẻ thống kê
        $countChoDuyet = DonXinNghi::where('trang_thai', 'cho_duyet')->count();
        $countDaDuyet = DonXinNghi::where('trang_thai', 'da_duyet')->count();
        $countTuChoi = DonXinNghi::where('trang_thai', 'tu_choi')->count();
        
        // 3. Truyền tất cả biến ra View
        return view('admin.don_nghi.index', compact(
            'danhSachDon', 
            'countChoDuyet', 
            'countDaDuyet', 
            'countTuChoi'
        ));
    }

    // 2. Xử lý duyệt hoặc từ chối
    public function capNhatTrangThai(Request $request, $id)
    {
        // 1. Bổ sung 'cho_duyet' vào danh sách được phép
        $request->validate([
            'trang_thai' => 'required|in:da_duyet,tu_choi,cho_duyet' 
        ]);

        try {
            $donNghi = DonXinNghi::findOrFail($id);
            $donNghi->trang_thai = $request->trang_thai;
            $donNghi->save();

            // 2. Custom lại câu thông báo
            if ($request->trang_thai == 'cho_duyet') {
                $thongBao = 'Đã hoàn tác đơn về trạng thái Chờ duyệt!';
            } else {
                $thongBao = $request->trang_thai == 'da_duyet' ? 'Đã duyệt đơn nghỉ phép thành công!' : 'Đã từ chối đơn nghỉ phép!';
            }
            
            return redirect()->back()->with('success', $thongBao);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
}