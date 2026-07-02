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

        if ($request->filled('trang_thai')) {
            $query->where('trang_thai', $request->trang_thai);
        }

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
     * Hiển thị form tạo ứng viên mới
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
        $request->validate([
            'ho' => 'required|string|max:50',
            'ten' => 'required|string|max:50',
            'email' => 'required|email|unique:ung_vien,email',
            'so_dien_thoai' => 'required|string|max:15',
            'tin_tuyen_dung_id' => 'required|exists:tin_tuyen_dung,id',
            'phong_ban_id' => 'nullable|exists:phong_ban,id',
            'luong_mong_muon' => 'nullable|numeric|min:0',
            'trang_thai' => 'required|in:moi_nop,cho_duyet,da_duyet,dat,khong_dat',
            'cv' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'ghi_chu' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // Tạo mã hồ sơ
            $maHoSo = 'UV' . date('Ymd') . rand(100, 999);
            
            // Upload CV
            $cvPath = null;
            if ($request->hasFile('cv')) {
                $cvPath = $request->file('cv')->store('cv', 'public');
            }

            // Lưu ứng viên
            $ungVien = UngVien::create([
                'ma_ho_so' => $maHoSo,
                'ho' => $request->ho,
                'ten' => $request->ten,
                'email' => $request->email,
                'so_dien_thoai' => $request->so_dien_thoai,
                'tin_tuyen_dung_id' => $request->tin_tuyen_dung_id,
                'phong_ban_id' => $request->phong_ban_id,
                'luong_mong_muon' => $request->luong_mong_muon,
                'trang_thai' => $request->trang_thai,
                'cv_path' => $cvPath,
                'ghi_chu' => $request->ghi_chu,
            ]);

            DB::commit();

            return redirect()->route('admin.ung_vien.index')
                ->with('success', 'Thêm ứng viên "' . $ungVien->ho . ' ' . $ungVien->ten . '" thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Hiển thị chi tiết ứng viên
     */
    public function show($id)
    {
        $ungVien = UngVien::with([
            'tinTuyenDung', 
            'phongBan',
            'lichSuEmails.nguoiGui'
        ])->findOrFail($id);
        
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

            // Nếu trạng thái cũ là chờ duyệt, không cho phép quay lại mới nộp
            if ($ungVien->trang_thai == 'cho_duyet' && $request->trang_thai == 'moi_nop') {
                return redirect()->back()
                    ->with('warning', 'Ứng viên đã ở trạng thái chờ duyệt, không thể quay lại mới nộp.');
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
}