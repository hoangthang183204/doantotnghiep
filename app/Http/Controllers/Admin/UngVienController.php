<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UngVien;
use App\Models\TinTuyenDung;
use App\Models\PhongBan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UngVienController extends Controller
{
    /**
     * Hiển thị danh sách ứng viên
     */
    public function index(Request $request)
    {
        $query = UngVien::with(['tinTuyenDung', 'phongBan']);

        // Tìm kiếm
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('ho', 'like', "%{$keyword}%")
                  ->orWhere('ten', 'like', "%{$keyword}%")
                  ->orWhere('email', 'like', "%{$keyword}%")
                  ->orWhere('so_dien_thoai', 'like', "%{$keyword}%")
                  ->orWhere('ma_ho_so', 'like', "%{$keyword}%");
            });
        }

        // Lọc theo trạng thái
        if ($request->filled('trang_thai')) {
            $query->where('trang_thai', $request->trang_thai);
        }

        // Lọc theo tin tuyển dụng
        if ($request->filled('tin_tuyen_dung_id')) {
            $query->where('tin_tuyen_dung_id', $request->tin_tuyen_dung_id);
        }

        $ungViens = $query->latest()->paginate(10);
        $tinTuyenDungs = TinTuyenDung::all();
        $trangThais = [
            'moi_nop' => 'Mới nộp',
            'cho_duyet' => 'Chờ duyệt',
            'da_duyet' => 'Đã duyệt',
            'hen_phong_van' => 'Hẹn phỏng vấn',
            'cho_phong_van' => 'Chờ phỏng vấn',
            'da_phong_van' => 'Đã phỏng vấn',
            'dat' => 'Trúng tuyển',
            'khong_dat' => 'Không đạt',
            'da_huy' => 'Đã hủy',
            'tam_dung' => 'Tạm dừng',
        ];

        return view('admin.ung-vien.index', compact('ungViens', 'tinTuyenDungs', 'trangThais'));
    }

    /**
     * Hiển thị chi tiết ứng viên
     */
    public function show($id)
    {
        $ungVien = UngVien::with(['tinTuyenDung', 'phongBan'])->findOrFail($id);
        
        return view('admin.ung-vien.show', compact('ungVien'));
    }

    /**
     * Cập nhật trạng thái ứng viên
     */
    public function updateStatus(Request $request, $id)
    {
        try {
            $request->validate([
                'trang_thai' => 'required|in:moi_nop,cho_duyet,da_duyet,hen_phong_van,cho_phong_van,da_phong_van,dat,khong_dat,da_huy,tam_dung',
            ]);

            $ungVien = UngVien::findOrFail($id);
            
            // Kiểm tra nếu đã trúng tuyển hoặc không đạt thì không cho thay đổi
            if ($ungVien->trang_thai == 'dat' || $ungVien->trang_thai == 'khong_dat') {
                return redirect()->back()
                    ->with('warning', 'Ứng viên đã ở trạng thái cuối cùng, không thể thay đổi.');
            }

            $oldStatus = $ungVien->trang_thai;
            $ungVien->update([
                'trang_thai' => $request->trang_thai,
            ]);

            // Nếu trạng thái là "Trúng tuyển" thì chuyển sang trang trúng tuyển
            if ($request->trang_thai == 'dat') {
                return redirect()->route('admin.trung-tuyen.index')
                    ->with('success', 'Ứng viên "' . $ungVien->ho . ' ' . $ungVien->ten . '" đã được chuyển sang danh sách trúng tuyển.');
            }

            return redirect()->back()
                ->with('success', 'Cập nhật trạng thái ứng viên "' . $ungVien->ho . ' ' . $ungVien->ten . '" từ "' . $this->getStatusText($oldStatus) . '" sang "' . $this->getStatusText($request->trang_thai) . '" thành công!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Duyệt ứng viên (Chuyển từ chờ duyệt sang đã duyệt)
     */
    public function approve($id)
    {
        try {
            $ungVien = UngVien::findOrFail($id);
            
            if ($ungVien->trang_thai != 'cho_duyet') {
                return redirect()->back()
                    ->with('warning', 'Ứng viên này không ở trạng thái chờ duyệt.');
            }

            $ungVien->update([
                'trang_thai' => 'da_duyet',
            ]);

            return redirect()->back()
                ->with('success', 'Duyệt ứng viên "' . $ungVien->ho . ' ' . $ungVien->ten . '" thành công!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Từ chối ứng viên
     */
    public function reject($id)
    {
        try {
            $ungVien = UngVien::findOrFail($id);
            
            if ($ungVien->trang_thai == 'dat' || $ungVien->trang_thai == 'khong_dat') {
                return redirect()->back()
                    ->with('warning', 'Ứng viên này đã được xử lý.');
            }

            $ungVien->update([
                'trang_thai' => 'khong_dat',
            ]);

            return redirect()->back()
                ->with('success', 'Từ chối ứng viên "' . $ungVien->ho . ' ' . $ungVien->ten . '" thành công!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Helper: Lấy text trạng thái
     */
    private function getStatusText($status)
    {
        $statuses = [
            'moi_nop' => 'Mới nộp',
            'cho_duyet' => 'Chờ duyệt',
            'da_duyet' => 'Đã duyệt',
            'hen_phong_van' => 'Hẹn phỏng vấn',
            'cho_phong_van' => 'Chờ phỏng vấn',
            'da_phong_van' => 'Đã phỏng vấn',
            'dat' => 'Trúng tuyển',
            'khong_dat' => 'Không đạt',
            'da_huy' => 'Đã hủy',
            'tam_dung' => 'Tạm dừng',
        ];

        return $statuses[$status] ?? $status;
    }

    // ... các method khác ...

    /**
     * Helper: Lấy text trạng thái
     */

    /**
     * Tạo form tạo ứng viên mới
     */
    public function create()
    {
        $tinTuyenDungs = TinTuyenDung::where('trang_thai', 'dang_tuyen')->get();
        $phongBans = PhongBan::all();
        return view('admin.ung-vien.create', compact('tinTuyenDungs', 'phongBans'));
    }

    /**
     * Lưu ứng viên mới
     */
    public function store(Request $request)
    {
        // Code xử lý lưu ứng viên
    }

    /**
     * Cập nhật ứng viên
     */
    public function update(Request $request, $id)
    {
        // Code xử lý cập nhật ứng viên
    }

    /**
     * Xóa ứng viên
     */
    public function destroy($id)
    {
        // Code xử lý xóa ứng viên
    }

    /**
     * Lưu trữ ứng viên
     */
    public function archive($id)
    {
        // Code xử lý lưu trữ ứng viên
    }

    /**
     * Khôi phục ứng viên
     */
    public function restore($id)
    {
        // Code xử lý khôi phục ứng viên
    }

    /**
     * Danh sách ứng viên đã lưu trữ
     */
    public function archived()
    {
        // Code xử lý hiển thị ứng viên đã lưu trữ
    }

    /**
     * Danh sách email phỏng vấn
     */
    public function emailList()
    {
        // Code xử lý danh sách email
    }

    /**
     * Tạo email phỏng vấn
     */
    public function createEmail()
    {
        // Code xử lý tạo email
    }

    /**
     * Gửi email phỏng vấn
     */
    public function sendEmail(Request $request)
    {
        // Code xử lý gửi email
    }
}