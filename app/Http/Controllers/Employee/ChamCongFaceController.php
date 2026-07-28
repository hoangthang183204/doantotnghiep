<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\FaceData;
use App\Models\ChamCong;
use App\Models\ChamCongFace;
use App\Models\CaLamViec;
use App\Models\DonXinVeSom;
use App\Services\FaceRecognitionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ChamCongFaceController extends Controller
{
    protected $faceService;

    public function __construct(FaceRecognitionService $faceService)
    {
        $this->faceService = $faceService;
    }

    /**
     * Trang chấm công bằng khuôn mặt
     */
    public function index()
    {
        $user = Auth::user();
        $faceData = FaceData::where('nguoi_dung_id', $user->id)
            ->where('is_active', true)
            ->first();

        if (!$faceData) {
            return view('employee.cham-cong-face.index', [
                'hasFace' => false,
                'message' => 'Bạn chưa đăng ký khuôn mặt. Vui lòng liên hệ HR để đăng ký.'
            ]);
        }

        $today = Carbon::today();
        $chamCongHomNay = ChamCong::where('nguoi_dung_id', $user->id)
            ->whereDate('ngay_cham_cong', $today)
            ->first();

        $checkedIn = $chamCongHomNay && $chamCongHomNay->gio_vao;
        $checkedOut = $chamCongHomNay && $chamCongHomNay->gio_ra;

        // Kiểm tra đơn xin về sớm
        $donVeSom = null;
        if ($checkedIn && !$checkedOut) {
            $donVeSom = DonXinVeSom::where('nguoi_dung_id', $user->id)
                ->where('ngay', $today)
                ->where('trang_thai', 'da_duyet')
                ->first();
        }

        $history = ChamCongFace::where('nguoi_dung_id', $user->id)
            ->where('trang_thai', 'thanh_cong')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('employee.cham-cong-face.index', [
            'hasFace' => true,
            'faceData' => $faceData,
            'checkedIn' => $checkedIn,
            'checkedOut' => $checkedOut,
            'checkInTime' => $chamCongHomNay ? $chamCongHomNay->gio_vao : null,
            'checkOutTime' => $chamCongHomNay ? $chamCongHomNay->gio_ra : null,
            'donVeSom' => $donVeSom,
            'history' => $history,
        ]);
    }

    /**
     * Xác định ca làm việc dựa vào giờ
     */
    private function xacDinhCaLamViec($gioVao)
    {
        if ($gioVao >= '06:00:00' && $gioVao <= '08:30:00') {
            return CaLamViec::where('ma', 'SANG')->first();
        } elseif ($gioVao >= '12:00:00' && $gioVao <= '13:30:00') {
            return CaLamViec::where('ma', 'CHIEU')->first();
        } elseif ($gioVao >= '08:30:00' && $gioVao < '12:00:00') {
            return CaLamViec::where('ma', 'SANG')->first();
        } elseif ($gioVao >= '13:30:00' && $gioVao < '17:00:00') {
            return CaLamViec::where('ma', 'CHIEU')->first();
        }
        return null;
    }

    /**
     * Kiểm tra số phút về sớm hiện tại
     */
    public function kiemTraVeSom()
    {
        $user = Auth::user();
        $today = Carbon::today();

        $chamCong = ChamCong::where('nguoi_dung_id', $user->id)
            ->whereDate('ngay_cham_cong', $today)
            ->first();

        if (!$chamCong || !$chamCong->gio_vao || $chamCong->gio_ra) {
            return response()->json([
                'so_phut_ve_som' => 0,
                'is_ve_som' => false
            ]);
        }

        // Lấy ca làm việc
        $ca = $chamCong->caLamViec;
        if (!$ca) {
            $ca = CaLamViec::where('is_default', 1)->first();
        }

        if (!$ca) {
            return response()->json([
                'so_phut_ve_som' => 0,
                'is_ve_som' => false
            ]);
        }

        $now = Carbon::now('Asia/Ho_Chi_Minh');
        $gioKetThuc = Carbon::parse($ca->gio_ket_thuc);

        if ($now->lt($gioKetThuc)) {
            $soPhutVeSom = $gioKetThuc->diffInMinutes($now);

            // Kiểm tra đã có đơn được duyệt chưa
            $donDaDuyet = DonXinVeSom::where('nguoi_dung_id', $user->id)
                ->where('ngay', $today)
                ->where('trang_thai', 'da_duyet')
                ->exists();

            // Nếu có đơn duyệt thì không tính là về sớm
            if ($donDaDuyet) {
                return response()->json([
                    'so_phut_ve_som' => 0,
                    'is_ve_som' => false,
                    'da_co_don_duyet' => true
                ]);
            }

            return response()->json([
                'so_phut_ve_som' => $soPhutVeSom,
                'is_ve_som' => true,
                'da_co_don_duyet' => false
            ]);
        }

        return response()->json([
            'so_phut_ve_som' => 0,
            'is_ve_som' => false
        ]);
    }

    /**
     * Xác thực khuôn mặt và chấm công
     */
    /**
     * Xác thực khuôn mặt và chấm công
     */
    public function authenticate(Request $request)
    {
        $user = Auth::user();
        $today = Carbon::today();

        $request->validate([
            'image' => 'required|string',
            'loai' => 'required|in:check_in,check_out',
        ]);

        // Khởi tạo biến
        $ca = null;
        $now = Carbon::now('Asia/Ho_Chi_Minh');

        // Lấy bản ghi chấm công hôm nay
        $chamCongHomNay = ChamCong::where('nguoi_dung_id', $user->id)
            ->whereDate('ngay_cham_cong', $today)
            ->first();

        // =============================================
        // KIỂM TRA CHECK-IN
        // =============================================
        if ($request->loai == 'check_in') {
            // Kiểm tra đã check-in chưa
            if ($chamCongHomNay && $chamCongHomNay->gio_vao) {
                return response()->json([
                    'success' => false,
                    'message' => '❌ Bạn đã Check-in hôm nay rồi! (lúc ' . $chamCongHomNay->gio_vao->format('H:i:s') . ')'
                ], 400);
            }

            // =============================================
            // ⏰ KIỂM TRA GIỜ CHECK-IN
            // =============================================
            $gioHienTai = $now->format('H:i:s');

            // Khung giờ cho phép check-in
            $checkinTimes = [
                ['start' => '06:00:00', 'end' => '08:30:00'],  // Ca sáng
                ['start' => '12:00:00', 'end' => '13:30:00'],  // Ca chiều
            ];

            $isAllowed = false;
            foreach ($checkinTimes as $timeRange) {
                if ($gioHienTai >= $timeRange['start'] && $gioHienTai <= $timeRange['end']) {
                    $isAllowed = true;
                    break;
                }
            }

            if (!$isAllowed) {
                return response()->json([
                    'success' => false,
                    'message' => '⏰ Không trong giờ Check-in! Vui lòng Check-in từ 6:00-8:30 (sáng) hoặc 12:00-13:30 (chiều).'
                ], 400);
            }

            // Xác định ca làm việc
            $ca = $this->xacDinhCaLamViec($gioHienTai);
            if (!$ca) {
                return response()->json([
                    'success' => false,
                    'message' => '⏰ Không xác định được ca làm việc!'
                ], 400);
            }
        }

        // =============================================
        // KIỂM TRA CHECK-OUT
        // =============================================
        $lyDoVeSom = null;
        $soPhutVeSom = 0;
        $daCoDonDuyet = false;
        $isVeSom = false;
        $soPhutLam = 0;

        if ($request->loai == 'check_out') {
            // Kiểm tra đã check-out chưa
            if ($chamCongHomNay && $chamCongHomNay->gio_ra) {
                return response()->json([
                    'success' => false,
                    'message' => '❌ Bạn đã Check-out hôm nay rồi! (lúc ' . $chamCongHomNay->gio_ra->format('H:i:s') . ')'
                ], 400);
            }

            // Kiểm tra đã check-in chưa
            if (!$chamCongHomNay || !$chamCongHomNay->gio_vao) {
                return response()->json([
                    'success' => false,
                    'message' => '❌ Bạn chưa Check-in hôm nay! Vui lòng Check-in trước.'
                ], 400);
            }

            // =============================================
            // ⏰ KIỂM TRA THỜI GIAN LÀM VIỆC TỐI THIỂU
            // =============================================
            $gioVao = Carbon::parse($chamCongHomNay->gio_vao);
            $soPhutLam = $gioVao->diffInMinutes($now);
            $thoiGianToiThieu = 15;

            if ($soPhutLam < $thoiGianToiThieu) {
                return response()->json([
                    'success' => false,
                    'message' => '⏰ Bạn mới chỉ làm được ' . $soPhutLam . ' phút. Cần làm ít nhất ' . $thoiGianToiThieu . ' phút mới được Check-out!'
                ], 400);
            }

            // =============================================
            // ⏰ KIỂM TRA GIỜ CHECK-OUT
            // =============================================
            $gioHienTai = $now->format('H:i:s');

            // Khung giờ cho phép check-out
            $checkoutTimes = [
                ['start' => '11:00:00', 'end' => '12:30:00'],  // Ca sáng
                ['start' => '16:30:00', 'end' => '18:30:00'],  // Ca chiều
            ];

            $isAllowed = false;
            foreach ($checkoutTimes as $timeRange) {
                if ($gioHienTai >= $timeRange['start'] && $gioHienTai <= $timeRange['end']) {
                    $isAllowed = true;
                    break;
                }
            }

            if (!$isAllowed) {
                return response()->json([
                    'success' => false,
                    'message' => '⏰ Không trong giờ Check-out! Vui lòng Check-out từ 11:00-12:30 (sáng) hoặc 16:30-18:30 (chiều).'
                ], 400);
            }

            // =============================================
            // ✅ KIỂM TRA VỀ SỚM
            // =============================================
            // Lấy ca làm việc từ bản ghi check-in
            $ca = $chamCongHomNay->caLamViec;
            if (!$ca) {
                $ca = ChamCong::xacDinhCaLamViec($chamCongHomNay->gio_vao);
            }

            if (!$ca) {
                $ca = CaLamViec::where('is_default', 1)->first();
            }

            if ($ca) {
                $gioKetThuc = Carbon::parse($ca->gio_ket_thuc);
                $isVeSom = $now->lt($gioKetThuc);

                if ($isVeSom) {
                    $soPhutVeSom = $gioKetThuc->diffInMinutes($now);

                    // Kiểm tra đơn xin về sớm
                    $donVeSom = DonXinVeSom::where('nguoi_dung_id', $user->id)
                        ->where('ngay', $today)
                        ->where('cham_cong_id', $chamCongHomNay->id)
                        ->first();

                    if ($donVeSom && $donVeSom->trang_thai == 'da_duyet') {
                        $daCoDonDuyet = true;
                        $lyDoVeSom = $donVeSom->ly_do;
                        $soPhutVeSom = 0;
                    } elseif ($donVeSom && $donVeSom->trang_thai == 'cho_duyet') {
                        return response()->json([
                            'success' => false,
                            'message' => '⏳ Đơn xin về sớm đang chờ HR duyệt! Vui lòng đợi.',
                            'trang_thai_don' => 'cho_duyet'
                        ], 400);
                    } elseif ($donVeSom && $donVeSom->trang_thai == 'tu_choi') {
                        return response()->json([
                            'success' => false,
                            'message' => '❌ Đơn xin về sớm đã bị từ chối! Lý do: ' . ($donVeSom->ly_do_tu_choi ?? 'Không có lý do'),
                            'trang_thai_don' => 'tu_choi'
                        ], 400);
                    } else {
                        return response()->json([
                            'success' => false,
                            'message' => '⚠️ Bạn đang về sớm ' . $soPhutVeSom . ' phút! Vui lòng tạo đơn xin về sớm trước khi Check-out.',
                            'yeu_cau_tao_don' => true,
                            'so_phut_ve_som' => $soPhutVeSom
                        ], 400);
                    }
                }
            }
        }

        // =============================================
        // XỬ LÝ ẢNH
        // =============================================

        $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $request->image));
        $tempPath = storage_path('app/temp_face_' . time() . '_' . $user->id . '.jpg');
        file_put_contents($tempPath, $imageData);

        try {
            // Kiểm tra ảnh có khuôn mặt không
            if (!$this->faceService->isValidFaceImage($tempPath)) {
                unlink($tempPath);
                return response()->json([
                    'success' => false,
                    'message' => '❌ Không phát hiện khuôn mặt hoặc chất lượng ảnh kém. Vui lòng thử lại.'
                ], 400);
            }

            // Lấy embedding từ ảnh vừa chụp
            $embedding = $this->faceService->getFaceEmbedding($tempPath);
            unlink($tempPath);

            if (!$embedding) {
                return response()->json([
                    'success' => false,
                    'message' => '❌ Không thể trích xuất đặc trưng khuôn mặt.'
                ], 400);
            }

            // Lấy embedding đã đăng ký
            $faceData = FaceData::where('nguoi_dung_id', $user->id)
                ->where('is_active', true)
                ->first();

            if (!$faceData) {
                return response()->json([
                    'success' => false,
                    'message' => '❌ Bạn chưa đăng ký khuôn mặt. Vui lòng liên hệ HR.'
                ], 400);
            }

            $embeddingPath = storage_path('app/public/' . $faceData->embedding_path);
            if (!file_exists($embeddingPath)) {
                return response()->json([
                    'success' => false,
                    'message' => '❌ Dữ liệu khuôn mặt không tồn tại. Vui lòng đăng ký lại.'
                ], 400);
            }

            $savedEmbedding = $this->faceService->loadEmbedding($embeddingPath);
            if (!$savedEmbedding) {
                return response()->json([
                    'success' => false,
                    'message' => '❌ Không thể đọc dữ liệu khuôn mặt.'
                ], 400);
            }

            // So sánh khuôn mặt
            $confidence = $this->faceService->compareFaces($embedding, $savedEmbedding);
            $threshold = 0.5;

            if ($confidence < $threshold) {
                ChamCongFace::create([
                    'nguoi_dung_id' => $user->id,
                    'face_id' => $faceData->face_id,
                    'confidence' => $confidence,
                    'loai' => $request->loai,
                    'trang_thai' => 'that_bai',
                    'ip_address' => null,
                    'device_info' => null,
                    'error_message' => 'Độ tin cậy thấp (' . round($confidence * 100) . '%)',
                ]);

                return response()->json([
                    'success' => false,
                    'message' => '❌ Khuôn mặt không khớp. Vui lòng thử lại.',
                    'confidence' => round($confidence * 100, 2),
                ], 400);
            }

            // Lưu log thành công
            $chamCongFace = ChamCongFace::create([
                'nguoi_dung_id' => $user->id,
                'face_id' => $faceData->face_id,
                'confidence' => $confidence,
                'loai' => $request->loai,
                'trang_thai' => 'thanh_cong',
                'ip_address' => null,
                'device_info' => null,
            ]);

            // Lưu bản ghi chấm công
            $chamCong = ChamCong::firstOrNew([
                'nguoi_dung_id' => $user->id,
                'ngay_cham_cong' => $today,
            ]);

            if ($request->loai == 'check_in') {
                $chamCong->gio_vao = $now;
                $chamCong->ca_lam_viec_id = $ca ? $ca->id : null;
                $chamCong->phuong_thuc_cham_cong = 'face';
                $chamCong->trang_thai = 'dung_gio';
                $chamCong->dia_chi_ip = null;
                $chamCong->ten_wifi = null;
                $chamCong->dia_chi_mac = null;
                $chamCong->ten_thiet_bi = null;
                $chamCong->loai_cham_cong = 'check_in';
                $chamCong->so_gio_lam = 0;
                $chamCong->so_cong = 0;
                $message = '✅ Check-in thành công!';
            } else {
                $chamCong->gio_ra = $now;
                $chamCong->ca_lam_viec_id = $chamCongHomNay ? $chamCongHomNay->ca_lam_viec_id : ($ca ? $ca->id : null);
                $chamCong->phuong_thuc_cham_cong = 'face';
                $chamCong->dia_chi_ip = null;
                $chamCong->ten_wifi = null;
                $chamCong->dia_chi_mac = null;
                $chamCong->ten_thiet_bi = null;
                $chamCong->loai_cham_cong = 'check_out';

                $trangThai = $chamCongHomNay ? $chamCongHomNay->trang_thai : 'dung_gio';

                if ($isVeSom) {
                    $trangThai = 've_som';
                } elseif ($ca && $now->gt(Carbon::parse($ca->gio_ket_thuc))) {
                    $trangThai = 'tang_ca';
                }

                if ($trangThai != 'tang_ca' && ($chamCongHomNay && $chamCongHomNay->trang_thai == 'di_muon')) {
                    $trangThai = 'di_muon';
                }

                if ($isVeSom) {
                    $chamCong->phut_ve_som = $soPhutVeSom;
                    $chamCong->trang_thai = $trangThai;
                    $chamCong->ly_do_ve_som = $lyDoVeSom;
                    $chamCong->da_xac_nhan_ve_som = $isVeSom && !empty($lyDoVeSom);
                } else {
                    $chamCong->trang_thai = $trangThai;
                    $chamCong->phut_ve_som = 0;
                }

                if ($chamCongHomNay && $chamCongHomNay->gio_vao) {
                    $gioVao = Carbon::parse($chamCongHomNay->gio_vao);
                    $soPhutLam = $gioVao->diffInMinutes($now);
                    $chamCong->so_gio_lam = round($soPhutLam / 60, 2);
                    $chamCong->so_cong = round($chamCong->so_gio_lam / 8, 2);
                    if ($chamCong->so_cong > 1) $chamCong->so_cong = 1;
                }

                $message = '✅ Check-out thành công!' .
                    ($isVeSom && !$daCoDonDuyet ? " (về sớm {$soPhutVeSom} phút)" : "") .
                    ($isVeSom && $daCoDonDuyet ? " (đã có đơn về sớm được duyệt)" : "");
            }

            $chamCong->save();

            $chamCongFace->cham_cong_id = $chamCong->id;
            $chamCongFace->save();

            Log::info('Face check-in/out saved', [
                'cham_cong_id' => $chamCong->id,
                'user_id' => $user->id,
                'type' => $request->loai,
                'phuong_thuc' => 'face',
                'so_phut_lam' => $soPhutLam,
            ]);

            return response()->json([
                'success' => true,
                'message' => $message,
                'confidence' => round($confidence * 100, 2),
                'time' => $now->format('H:i:s'),
                'type' => $request->loai,
                'cham_cong_id' => $chamCong->id,
                'so_phut_lam' => $soPhutLam,
            ]);
        } catch (\Exception $e) {
            if (file_exists($tempPath)) {
                unlink($tempPath);
            }

            Log::error('Face authentication error: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'type' => $request->loai,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => '❌ Lỗi hệ thống: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Tạo đơn xin về sớm
     */
    public function taoDonVeSom(Request $request)
    {
        try {
            $user = Auth::user();
            $today = Carbon::today('Asia/Ho_Chi_Minh');

            $request->validate([
                'ly_do' => 'required|string|min:5',
                'gio_ra_du_kien' => 'required',
            ]);

            // Kiểm tra đã check-in chưa
            $chamCong = ChamCong::where('nguoi_dung_id', $user->id)
                ->whereDate('ngay_cham_cong', $today)
                ->first();

            if (!$chamCong || !$chamCong->gio_vao) {
                return response()->json([
                    'success' => false,
                    'message' => '❌ Bạn chưa check-in hôm nay!'
                ], 400);
            }

            if ($chamCong->gio_ra) {
                return response()->json([
                    'success' => false,
                    'message' => '❌ Bạn đã check-out hôm nay rồi!'
                ], 400);
            }

            // Kiểm tra đã có đơn chưa
            $donExist = DonXinVeSom::where('nguoi_dung_id', $user->id)
                ->where('ngay', $today)
                ->where('cham_cong_id', $chamCong->id)
                ->whereIn('trang_thai', ['cho_duyet', 'da_duyet'])
                ->exists();

            if ($donExist) {
                return response()->json([
                    'success' => false,
                    'message' => '❌ Bạn đã có đơn xin về sớm đang chờ duyệt hoặc đã được duyệt!'
                ], 400);
            }

            // Lấy ca làm việc
            $ca = $chamCong->caLamViec;
            if (!$ca) {
                $ca = ChamCong::xacDinhCaLamViec($chamCong->gio_vao);
            }
            if (!$ca) {
                $ca = CaLamViec::where('is_default', 1)->first();
            }

            if (!$ca) {
                return response()->json([
                    'success' => false,
                    'message' => '❌ Không xác định được ca làm việc! Vui lòng liên hệ HR.'
                ], 400);
            }

            // Tính số phút về sớm
            $gioKetThuc = Carbon::parse($ca->gio_ket_thuc);
            $gioRaDuKien = Carbon::parse($request->gio_ra_du_kien);

            if ($gioRaDuKien->gt($gioKetThuc)) {
                return response()->json([
                    'success' => false,
                    'message' => '❌ Giờ ra dự kiến phải trước giờ kết thúc ca (' . $gioKetThuc->format('H:i') . ')!'
                ], 400);
            }

            $soPhutVeSom = $gioRaDuKien->diffInMinutes($gioKetThuc);

            DB::beginTransaction();

            $don = DonXinVeSom::create([
                'nguoi_dung_id' => $user->id,
                'cham_cong_id' => $chamCong->id,
                'ngay' => $today,
                'gio_ra_du_kien' => $request->gio_ra_du_kien,
                'so_phut_ve_som' => $soPhutVeSom,
                'ly_do' => $request->ly_do,
                'trang_thai' => 'cho_duyet',
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => '✅ Đã gửi đơn xin về sớm lên HR duyệt!',
                'data' => [
                    'don_id' => $don->id,
                    'so_phut_ve_som' => $soPhutVeSom,
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Tạo đơn về sớm error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => '❌ Lỗi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Kiểm tra trạng thái đơn xin về sớm
     */
    public function kiemTraDonVeSom(Request $request)
    {
        $user = Auth::user();
        $today = Carbon::today('Asia/Ho_Chi_Minh');

        $don = DonXinVeSom::where('nguoi_dung_id', $user->id)
            ->where('ngay', $today)
            ->orderBy('id', 'desc')
            ->first();

        if (!$don) {
            return response()->json([
                'has_don' => false,
                'message' => 'Chưa có đơn xin về sớm'
            ]);
        }

        return response()->json([
            'has_don' => true,
            'don_id' => $don->id,
            'trang_thai' => $don->trang_thai,
            'trang_thai_text' => $don->trang_thai == 'cho_duyet' ? 'Chờ duyệt' : ($don->trang_thai == 'da_duyet' ? 'Đã duyệt' : 'Từ chối'),
            'so_phut_ve_som' => $don->so_phut_ve_som,
            'ly_do' => $don->ly_do,
            'ly_do_tu_choi' => $don->ly_do_tu_choi,
            'thoi_gian_duyet' => $don->thoi_gian_duyet,
        ]);
    }

    /**
     * Kiểm tra trạng thái chấm công hiện tại
     */
    public function status()
    {
        $user = Auth::user();
        $today = Carbon::today();

        $chamCong = ChamCong::where('nguoi_dung_id', $user->id)
            ->whereDate('ngay_cham_cong', $today)
            ->first();

        $donVeSom = null;
        if ($chamCong && $chamCong->gio_vao && !$chamCong->gio_ra) {
            $donVeSom = DonXinVeSom::where('nguoi_dung_id', $user->id)
                ->where('ngay', $today)
                ->where('trang_thai', 'da_duyet')
                ->first();
        }

        $recentHistory = ChamCongFace::where('nguoi_dung_id', $user->id)
            ->where('trang_thai', 'thanh_cong')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'time' => $item->created_at->format('d/m/Y H:i:s'),
                    'type' => $item->loai == 'check_in' ? '✅ Check-in' : '🚪 Check-out',
                    'status' => $item->trang_thai == 'thanh_cong' ? '✅ Thành công' : '❌ Thất bại',
                    'confidence' => round($item->confidence * 100, 2) . '%',
                ];
            });

        return response()->json([
            'checked_in' => $chamCong && $chamCong->gio_vao,
            'checked_out' => $chamCong && $chamCong->gio_ra,
            'check_in_time' => $chamCong && $chamCong->gio_vao ? Carbon::parse($chamCong->gio_vao)->format('H:i:s') : null,
            'check_out_time' => $chamCong && $chamCong->gio_ra ? Carbon::parse($chamCong->gio_ra)->format('H:i:s') : null,
            'has_face' => FaceData::where('nguoi_dung_id', $user->id)
                ->where('is_active', true)
                ->exists(),
            'has_don_ve_som' => $donVeSom ? true : false,
            'don_ve_som' => $donVeSom,
            'history' => $recentHistory,
        ]);
    }

    /**
     * Kiểm tra trạng thái check-in/out (API)
     */
    public function checkStatus()
    {
        $user = Auth::user();
        $today = Carbon::today();

        $chamCong = ChamCong::where('nguoi_dung_id', $user->id)
            ->whereDate('ngay_cham_cong', $today)
            ->first();

        $checkedIn = $chamCong && $chamCong->gio_vao;
        $checkedOut = $chamCong && $chamCong->gio_ra;

        return response()->json([
            'can_check_in' => !$checkedIn,
            'can_check_out' => $checkedIn && !$checkedOut,
            'checked_in' => $checkedIn,
            'checked_out' => $checkedOut,
        ]);
    }

    /**
     * Lấy lịch sử chấm công khuôn mặt
     */
    public function history()
    {
        $user = Auth::user();

        $history = ChamCongFace::where('nguoi_dung_id', $user->id)
            ->where('trang_thai', 'thanh_cong')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('employee.cham-cong-face.history', [
            'history' => $history,
        ]);
    }

    /**
     * Xóa bản ghi chấm công hôm nay (debug)
     */
    public function resetToday()
    {
        $user = Auth::user();
        $today = Carbon::today();

        $deletedChamCong = ChamCong::where('nguoi_dung_id', $user->id)
            ->whereDate('ngay_cham_cong', $today)
            ->delete();

        $deletedFace = ChamCongFace::where('nguoi_dung_id', $user->id)
            ->whereDate('created_at', $today)
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'Đã reset dữ liệu chấm công hôm nay.',
            'deleted_cham_cong' => $deletedChamCong,
            'deleted_cham_cong_face' => $deletedFace,
        ]);
    }
}
