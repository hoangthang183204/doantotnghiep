<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChamCong;
use App\Models\PhongBan;
use App\Models\DonXinVeSom;
use App\Models\NguoiDung;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ChamCongController extends Controller
{
    /**
     * Danh sách chấm công - Hiển thị mỗi nhân viên 1 dòng
     */
    public function index(Request $request)
    {
        // Lấy danh sách nhân viên đã có chấm công (distinct)
        $employeeIds = ChamCong::select('nguoi_dung_id')
            ->distinct()
            ->pluck('nguoi_dung_id')
            ->toArray();

        // Lấy thông tin chi tiết của những nhân viên này
        $query = NguoiDung::with(['hoSo', 'phongBan'])
            ->whereIn('id', $employeeIds)
            ->where('trang_thai', 1);

        // Nếu không có nhân viên nào chấm công, trả về empty collection
        if (empty($employeeIds)) {
            $chamCongs = collect();
        } else {
            // Áp dụng bộ lọc
            if ($request->filled('ten_nhan_vien')) {
                $keyword = trim($request->ten_nhan_vien);
                $query->where(function($q) use ($keyword) {
                    $q->where('ten_dang_nhap', 'like', "%{$keyword}%")
                        ->orWhereHas('hoSo', function ($hs) use ($keyword) {
                            $hs->where('ho', 'like', "%{$keyword}%")
                                ->orWhere('ten', 'like', "%{$keyword}%")
                                ->orWhereRaw("CONCAT(ho, ' ', ten) LIKE ?", ["%{$keyword}%"]);
                        });
                });
            }

            if ($request->filled('nguoi_dung_id')) {
                $query->where('id', $request->nguoi_dung_id);
            }

            $chamCongs = $query->orderBy('id', 'desc')->paginate(20)->appends($request->query());
        }

        // ========== THỐNG KÊ ==========
        $tongSoBanGhi = ChamCong::count();
        $tongDungGio = ChamCong::where('trang_thai', 'dung_gio')->count();
        $tyLeDungGio = $tongSoBanGhi > 0 ? round(($tongDungGio / $tongSoBanGhi) * 100) : 0;
        $homNay = ChamCong::whereDate('ngay_cham_cong', Carbon::today())->count();
        $diMuonHomNay = ChamCong::whereDate('ngay_cham_cong', Carbon::today())
            ->where('trang_thai', 'di_muon')
            ->count();

        // THỐNG KÊ ĐƠN XIN VỀ SỚM
        $donVeSomChoDuyet = DonXinVeSom::where('trang_thai', 'cho_duyet')->count();
        $donVeSomDaDuyet = DonXinVeSom::where('trang_thai', 'da_duyet')->count();

        $phongBan = PhongBan::all();

        // Lấy danh sách nhân viên cho dropdown
        $nhanViens = NguoiDung::with('hoSo')
            ->where('trang_thai', 1)
            ->get()
            ->map(function ($user) {
                $hoSo = $user->hoSo;
                return (object) [
                    'id' => $user->id,
                    'ten' => $hoSo ? trim(($hoSo->ho ?? '') . ' ' . ($hoSo->ten ?? '')) : $user->ten_dang_nhap,
                    'ma_nhan_vien' => $hoSo ? $hoSo->ma_nhan_vien : null,
                ];
            })
            ->sortBy('ten')
            ->values();

        return view('admin.cham-cong.index', compact(
            'chamCongs',
            'tongSoBanGhi',
            'tyLeDungGio',
            'homNay',
            'diMuonHomNay',
            'phongBan',
            'donVeSomChoDuyet',
            'donVeSomDaDuyet',
            'nhanViens'
        ));
    }

    /**
     * Lấy danh sách chấm công của một nhân viên (AJAX) - Có phân trang
     */
    public function getByNhanVien(Request $request)
    {
        $request->validate([
            'nguoi_dung_id' => 'required|exists:nguoi_dung,id',
        ]);

        $query = ChamCong::with([
            'nguoi_dung.hoSo',
            'nguoi_dung.phongBan'
        ])->where('nguoi_dung_id', $request->nguoi_dung_id);

        // Áp dụng bộ lọc ngày nếu có
        if ($request->filled('tu_ngay')) {
            $query->whereDate('ngay_cham_cong', '>=', $request->tu_ngay);
        }
        if ($request->filled('den_ngay')) {
            $query->whereDate('ngay_cham_cong', '<=', $request->den_ngay);
        }

        // Phân trang 10 bản ghi mỗi trang
        $perPage = $request->per_page ?? 10;
        $chamCongs = $query
            ->orderBy('ngay_cham_cong', 'desc')
            ->paginate($perPage);

        // Lấy thông tin nhân viên từ bản ghi đầu tiên
        $employeeInfo = null;
        $firstRecord = $chamCongs->first();
        if ($firstRecord) {
            $nguoiDung = $firstRecord->nguoiDung ?? null;
            $hoSo = $nguoiDung ? $nguoiDung->hoSo ?? null : null;
            
            $employeeInfo = [
                'ho_ten' => $hoSo ? trim(($hoSo->ho ?? '') . ' ' . ($hoSo->ten ?? '')) : ($nguoiDung->ten_dang_nhap ?? 'N/A'),
                'ma_nhan_vien' => $hoSo ? $hoSo->ma_nhan_vien : null,
                'phong_ban' => $nguoiDung && $nguoiDung->phongBan ? $nguoiDung->phongBan->ten_phong_ban : null,
            ];
        }

        // Format dữ liệu trả về
        $data = $chamCongs->map(function ($cc) {
            $nguoiDung = $cc->nguoiDung ?? null;
            $hoSo = $nguoiDung ? $nguoiDung->hoSo ?? null : null;

            $hoTen = '';
            if ($hoSo && ($hoSo->ho || $hoSo->ten)) {
                $hoTen = trim(($hoSo->ho ?? '') . ' ' . ($hoSo->ten ?? ''));
            }
            if (empty($hoTen) && $nguoiDung) {
                $hoTen = $nguoiDung->ten_dang_nhap ?? 'N/A';
            }
            if (empty($hoTen)) {
                $hoTen = 'NV#' . ($cc->nguoi_dung_id ?? '?');
            }

            // Xác định trạng thái hiển thị
            $soCong = $cc->so_cong ?? 0;
            $statusText = '';
            $statusClass = '';

            if ($cc->gio_vao && !$cc->gio_ra) {
                $statusText = '⏳ Đang làm';
                $statusClass = 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400';
            } elseif ($cc->gio_vao && $cc->gio_ra && $soCong > 0) {
                $statusText = '✅ Đúng giờ';
                $statusClass = 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300';
                if ($cc->trang_thai == 'di_muon') {
                    $statusText = '⚠️ Đi muộn';
                    $statusClass = 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-300';
                } elseif ($cc->trang_thai == 've_som') {
                    $statusText = '🔻 Về sớm';
                    $statusClass = 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-300';
                }
            } elseif ($cc->gio_vao && $cc->gio_ra && $soCong == 0) {
                $statusText = '❌ 0 công';
                $statusClass = 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300';
            } else {
                $statusText = '⏸️ Chưa chấm công';
                $statusClass = 'bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400';
                if ($cc->trang_thai == 'di_muon') {
                    $statusText = '⚠️ Đi muộn';
                    $statusClass = 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-300';
                } elseif ($cc->trang_thai == 've_som') {
                    $statusText = '🔻 Về sớm';
                    $statusClass = 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-300';
                } elseif ($cc->trang_thai == 'vang_mat') {
                    $statusText = '❌ Vắng mặt';
                    $statusClass = 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300';
                } elseif ($cc->trang_thai == 'nghi_phep') {
                    $statusText = '📋 Nghỉ phép';
                    $statusClass = 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-300';
                }
            }

            return [
                'id' => $cc->id,
                'ngay_cham_cong' => Carbon::parse($cc->ngay_cham_cong)->format('d/m/Y'),
                'thu' => Carbon::parse($cc->ngay_cham_cong)->locale('vi')->dayName,
                'gio_vao' => $cc->gio_vao ? Carbon::parse($cc->gio_vao)->format('H:i') : '--:--',
                'gio_ra' => $cc->gio_ra ? Carbon::parse($cc->gio_ra)->format('H:i') : '--:--',
                'so_gio_lam' => number_format($cc->so_gio_lam ?? 0, 1),
                'so_cong' => number_format($cc->so_cong ?? 0, 2),
                'phut_di_muon' => $cc->phut_di_muon ?? 0,
                'phut_ve_som' => $cc->phut_ve_som ?? 0,
                'trang_thai_text' => $statusText,
                'trang_thai_class' => $statusClass,
                'ghi_chu' => $cc->ghi_chu,
                'ho_ten' => $hoTen,
                'url_show' => route('admin.cham-cong.show', $cc->id),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data,
            'total' => $chamCongs->total(),
            'per_page' => $chamCongs->perPage(),
            'current_page' => $chamCongs->currentPage(),
            'last_page' => $chamCongs->lastPage(),
            'employee_info' => $employeeInfo,
        ]);
    }

    /**
     * Hiển thị lịch sử chấm công của một nhân viên (trang riêng)
     */
    public function showByNhanVien(Request $request, $id)
    {
        $nhanVien = NguoiDung::with(['hoSo', 'phongBan', 'chucVu'])->findOrFail($id);
        
        $query = ChamCong::with(['nguoi_dung.hoSo', 'nguoi_dung.phongBan'])
            ->where('nguoi_dung_id', $id);
        
        // Bộ lọc
        if ($request->filled('tu_ngay')) {
            $query->whereDate('ngay_cham_cong', '>=', $request->tu_ngay);
        }
        if ($request->filled('den_ngay')) {
            $query->whereDate('ngay_cham_cong', '<=', $request->den_ngay);
        }
        if ($request->filled('trang_thai')) {
            $query->where('trang_thai', $request->trang_thai);
        }
        
        $chamCongs = $query->orderBy('ngay_cham_cong', 'desc')
            ->paginate(20)
            ->appends($request->query());
        
        // Thống kê
        $thongKe = [
            'tong_ngay' => ChamCong::where('nguoi_dung_id', $id)->count(),
            'tong_gio' => ChamCong::where('nguoi_dung_id', $id)->sum('so_gio_lam'),
            'tong_cong' => ChamCong::where('nguoi_dung_id', $id)->sum('so_cong'),
            'tong_tang_ca' => ChamCong::where('nguoi_dung_id', $id)->sum('gio_tang_ca'),
            'di_muon' => ChamCong::where('nguoi_dung_id', $id)->where('trang_thai', 'di_muon')->count(),
            've_som' => ChamCong::where('nguoi_dung_id', $id)->where('trang_thai', 've_som')->count(),
            'dung_gio' => ChamCong::where('nguoi_dung_id', $id)->where('trang_thai', 'dung_gio')->count(),
            'vang_mat' => ChamCong::where('nguoi_dung_id', $id)->where('trang_thai', 'vang_mat')->count(),
        ];
        
        return view('admin.cham-cong.nhan-vien.show', compact('nhanVien', 'chamCongs', 'thongKe'));
    }

    /**
     * Chi tiết chấm công
     */
    public function show($id)
    {
        $chamCong = ChamCong::with([
            'nguoi_dung.hoSo',
            'nguoi_dung.phongBan',
            'faceRecords'
        ])->findOrFail($id);

        return view('admin.cham-cong.show', compact('chamCong'));
    }

    /**
     * Danh sách đơn xin về sớm
     */
    public function danhSachDonVeSom(Request $request)
    {
        $query = DonXinVeSom::with([
            'nguoiDung.hoSo',
            'nguoiDung.phongBan',
            'chamCong'
        ]);

        // Lọc theo trạng thái
        if ($request->filled('trang_thai')) {
            $query->where('trang_thai', $request->trang_thai);
        }

        // Lọc theo ngày
        if ($request->filled('tu_ngay')) {
            $query->whereDate('ngay', '>=', $request->tu_ngay);
        }
        if ($request->filled('den_ngay')) {
            $query->whereDate('ngay', '<=', $request->den_ngay);
        }

        // Lọc theo nhân viên
        if ($request->filled('ten_nhan_vien')) {
            $keyword = trim($request->ten_nhan_vien);
            $query->whereHas('nguoiDung', function ($q) use ($keyword) {
                $q->where('ten_dang_nhap', 'like', "%{$keyword}%")
                    ->orWhereHas('hoSo', function ($hs) use ($keyword) {
                        $hs->where('ho', 'like', "%{$keyword}%")
                            ->orWhere('ten', 'like', "%{$keyword}%")
                            ->orWhereRaw("CONCAT(ho, ' ', ten) LIKE ?", ["%{$keyword}%"]);
                    });
            });
        }

        $donVeSoms = $query->orderBy('created_at', 'desc')->paginate(20);

        // Thống kê
        $soChoDuyet = DonXinVeSom::where('trang_thai', 'cho_duyet')->count();
        $soDaDuyet = DonXinVeSom::where('trang_thai', 'da_duyet')->count();
        $soTuChoi = DonXinVeSom::where('trang_thai', 'tu_choi')->count();

        return view('admin.cham-cong.don-ve-som', compact(
            'donVeSoms',
            'soChoDuyet',
            'soDaDuyet',
            'soTuChoi'
        ));
    }

    /**
     * Duyệt đơn xin về sớm
     */
    public function duyetDonVeSom($id)
    {
        try {
            $don = DonXinVeSom::findOrFail($id);

            if ($don->trang_thai != 'cho_duyet') {
                return response()->json([
                    'success' => false,
                    'message' => 'Đơn này đã được xử lý!'
                ], 400);
            }

            $don->trang_thai = 'da_duyet';
            $don->nguoi_duyet_id = auth()->id();
            $don->thoi_gian_duyet = now();
            $don->save();

            return response()->json([
                'success' => true,
                'message' => '✅ Đã duyệt đơn xin về sớm!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Từ chối đơn xin về sớm
     */
    public function tuChoiDonVeSom(Request $request, $id)
    {
        try {
            $request->validate([
                'ly_do_tu_choi' => 'required|string|min:10'
            ]);

            $don = DonXinVeSom::findOrFail($id);

            if ($don->trang_thai != 'cho_duyet') {
                return response()->json([
                    'success' => false,
                    'message' => 'Đơn này đã được xử lý!'
                ], 400);
            }

            $don->trang_thai = 'tu_choi';
            $don->ly_do_tu_choi = $request->ly_do_tu_choi;
            $don->nguoi_duyet_id = auth()->id();
            $don->thoi_gian_duyet = now();
            $don->save();

            return response()->json([
                'success' => true,
                'message' => '❌ Đã từ chối đơn xin về sớm!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Xuất Excel
     */
    public function export(Request $request)
    {
        $query = ChamCong::with([
            'nguoi_dung.hoSo'
        ]);

        // Nếu có nguoi_dung_id thì chỉ lấy của nhân viên đó
        if ($request->filled('nguoi_dung_id')) {
            $query->where('nguoi_dung_id', $request->nguoi_dung_id);
        }

        if ($request->filled('tu_ngay')) {
            $query->whereDate('ngay_cham_cong', '>=', $request->tu_ngay);
        }
        if ($request->filled('den_ngay')) {
            $query->whereDate('ngay_cham_cong', '<=', $request->den_ngay);
        }

        // Lấy tên nhân viên để đặt tên file
        $tenNhanVien = 'tat_ca';
        if ($request->filled('nguoi_dung_id')) {
            $user = NguoiDung::with('hoSo')->find($request->nguoi_dung_id);
            if ($user && $user->hoSo) {
                $tenNhanVien = trim(($user->hoSo->ho ?? '') . ' ' . ($user->hoSo->ten ?? ''));
            } elseif ($user) {
                $tenNhanVien = $user->ten_dang_nhap;
            }
            // Xóa dấu tiếng Việt để tên file không bị lỗi
            $tenNhanVien = $this->removeAccents($tenNhanVien);
        }

        $fileName = 'cham_cong_' . $tenNhanVien . '_' . now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename={$fileName}",
        ];

        $callback = function () use ($query) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($file, [
                'ID',
                'Nhân viên',
                'Ngày chấm công',
                'Giờ vào',
                'Giờ ra',
                'Số giờ làm',
                'Số công',
                'Tăng ca',
                'Đi muộn (phút)',
                'Về sớm (phút)',
                'Trạng thái',
            ]);

            $query->chunk(500, function ($records) use ($file) {
                foreach ($records as $item) {
                    $hoTen = $item->nguoi_dung->hoSo
                        ? trim(($item->nguoi_dung->hoSo->ho ?? '') . ' ' . ($item->nguoi_dung->hoSo->ten ?? ''))
                        : ($item->nguoi_dung->ten_dang_nhap ?? 'N/A');

                    if (empty($hoTen)) {
                        $hoTen = 'NV#' . ($item->nguoi_dung_id ?? '?');
                    }

                    fputcsv($file, [
                        $item->id,
                        $hoTen,
                        optional($item->ngay_cham_cong)->format('d/m/Y'),
                        $item->gio_vao,
                        $item->gio_ra,
                        $item->so_gio_lam,
                        $item->so_cong,
                        $item->gio_tang_ca,
                        $item->phut_di_muon,
                        $item->phut_ve_som,
                        $item->trang_thai,
                    ]);
                }
            });

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Xóa dấu tiếng Việt
     */
    private function removeAccents($str)
    {
        $str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $str);
        $str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $str);
        $str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $str);
        $str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $str);
        $str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $str);
        $str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $str);
        $str = preg_replace("/(đ)/", 'd', $str);
        $str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $str);
        $str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $str);
        $str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $str);
        $str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $str);
        $str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $str);
        $str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $str);
        $str = preg_replace("/(Đ)/", 'D', $str);
        $str = preg_replace('/[^a-zA-Z0-9]/', '_', $str);
        return $str;
    }

    /**
     * Chi tiết đơn xin về sớm (AJAX)
     */
    public function chiTietDonVeSom($id)
    {
        $don = DonXinVeSom::with(['nguoiDung.hoSo', 'nguoiDung.phongBan', 'nguoiDuyet.hoSo'])
            ->findOrFail($id);

        $nguoiDung = $don->nguoiDung;
        $hoSo = $nguoiDung ? $nguoiDung->hoSo : null;

        $hoTen = '';
        if ($hoSo && ($hoSo->ho || $hoSo->ten)) {
            $hoTen = trim(($hoSo->ho ?? '') . ' ' . ($hoSo->ten ?? ''));
        }
        if (empty($hoTen) && $nguoiDung) {
            $hoTen = $nguoiDung->ten_dang_nhap ?? 'N/A';
        }

        $avatar = null;
        if ($hoSo && $hoSo->anh_dai_dien && file_exists(public_path('storage/' . $hoSo->anh_dai_dien))) {
            $avatar = asset('storage/' . $hoSo->anh_dai_dien);
        }

        $nguoiDuyet = null;
        if ($don->nguoi_duyet_id) {
            $duyetUser = $don->nguoiDuyet;
            $duyetHoSo = $duyetUser ? $duyetUser->hoSo : null;
            if ($duyetHoSo && ($duyetHoSo->ho || $duyetHoSo->ten)) {
                $nguoiDuyet = trim(($duyetHoSo->ho ?? '') . ' ' . ($duyetHoSo->ten ?? ''));
            } else {
                $nguoiDuyet = $duyetUser->ten_dang_nhap ?? 'N/A';
            }
        }

        return response()->json([
            'success' => true,
            'data' => [
                'ho_ten' => $hoTen,
                'ma_nhan_vien' => $hoSo ? $hoSo->ma_nhan_vien : null,
                'phong_ban' => $nguoiDung && $nguoiDung->phongBan ? $nguoiDung->phongBan->ten_phong_ban : null,
                'avatar' => $avatar,
                'initial' => strtoupper(substr($hoTen, 0, 1)),
                'ngay' => Carbon::parse($don->ngay)->format('d/m/Y'),
                'gio_ra_du_kien' => Carbon::parse($don->gio_ra_du_kien)->format('H:i'),
                'so_phut_ve_som' => $don->so_phut_ve_som,
                'ly_do' => $don->ly_do,
                'trang_thai' => $don->trang_thai,
                'ly_do_tu_choi' => $don->ly_do_tu_choi,
                'nguoi_duyet' => $nguoiDuyet,
                'thoi_gian_duyet' => $don->thoi_gian_duyet ? Carbon::parse($don->thoi_gian_duyet)->format('d/m/Y H:i') : null,
            ]
        ]);
    }
}