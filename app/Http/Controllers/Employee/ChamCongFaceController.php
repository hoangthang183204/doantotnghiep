<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\FaceData;
use App\Models\ChamCong;
use App\Models\ChamCongFace;
use App\Models\CaLamViec;
use App\Services\FaceRecognitionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
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

        // Kiểm tra đã check-in chưa
        $today = Carbon::today();
        $chamCongHomNay = ChamCong::where('nguoi_dung_id', $user->id)
            ->whereDate('ngay_cham_cong', $today)
            ->first();

        $checkedIn = $chamCongHomNay && $chamCongHomNay->gio_vao;
        $checkedOut = $chamCongHomNay && $chamCongHomNay->gio_ra;

        // Lấy lịch sử chấm công khuôn mặt (10 bản ghi gần nhất)
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
            'history' => $history,
        ]);
    }

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

        // =============================================
        // KIỂM TRA TRẠNG THÁI CHẤM CÔNG
        // =============================================

        // Lấy bản ghi chấm công hôm nay
        $chamCongHomNay = ChamCong::where('nguoi_dung_id', $user->id)
            ->whereDate('ngay_cham_cong', $today)
            ->first();

        // 1. Kiểm tra Check-in
        if ($request->loai == 'check_in') {
            if ($chamCongHomNay && $chamCongHomNay->gio_vao) {
                return response()->json([
                    'success' => false,
                    'message' => '❌ Bạn đã Check-in hôm nay rồi! (lúc ' . $chamCongHomNay->gio_vao->format('H:i:s') . ')'
                ], 400);
            }
        }

        // 2. Kiểm tra Check-out
        if ($request->loai == 'check_out') {
            if ($chamCongHomNay && $chamCongHomNay->gio_ra) {
                return response()->json([
                    'success' => false,
                    'message' => '❌ Bạn đã Check-out hôm nay rồi! (lúc ' . $chamCongHomNay->gio_ra->format('H:i:s') . ')'
                ], 400);
            }

            if (!$chamCongHomNay || !$chamCongHomNay->gio_vao) {
                return response()->json([
                    'success' => false,
                    'message' => '❌ Bạn chưa Check-in hôm nay! Vui lòng Check-in trước.'
                ], 400);
            }
        }

        // =============================================
        // XỬ LÝ ẢNH
        // =============================================

        // Giải mã ảnh từ base64
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

            // Lấy embedding đã đăng ký của nhân viên
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
                // Lưu log thất bại
                ChamCongFace::create([
                    'nguoi_dung_id' => $user->id,
                    'face_id' => $faceData->face_id,
                    'confidence' => $confidence,
                    'loai' => $request->loai,
                    'trang_thai' => 'that_bai',
                    'ip_address' => $request->ip(),
                    'device_info' => $request->userAgent(),
                    'error_message' => 'Độ tin cậy thấp (' . round($confidence * 100) . '%)',
                ]);

                return response()->json([
                    'success' => false,
                    'message' => '❌ Khuôn mặt không khớp. Vui lòng thử lại.',
                    'confidence' => round($confidence * 100, 2),
                ], 400);
            }

            // =============================================
            // LƯU LOG THÀNH CÔNG VÀO cham_cong_face
            // =============================================

            $chamCongFace = ChamCongFace::create([
                'nguoi_dung_id' => $user->id,
                'face_id' => $faceData->face_id,
                'confidence' => $confidence,
                'loai' => $request->loai,
                'trang_thai' => 'thanh_cong',
                'ip_address' => $request->ip(),
                'device_info' => $request->userAgent(),
            ]);

            // =============================================
            // ✅ LƯU BẢN GHI CHẤM CÔNG VÀO cham_cong
            // =============================================

            // Xác định ca làm việc
            $now = Carbon::now('Asia/Ho_Chi_Minh');
            $gioVaoStr = $now->format('H:i:s');
            
            // Xác định ca dựa vào giờ hiện tại
            $ca = $this->xacDinhCaLamViec($gioVaoStr);
            
            if (!$ca && $request->loai == 'check_in') {
                return response()->json([
                    'success' => false,
                    'message' => '⏰ Không xác định được ca làm việc! Vui lòng check-in đúng giờ: Sáng 6:00-8:30, Chiều 12:00-13:30'
                ], 400);
            }

            // Tìm hoặc tạo bản ghi chấm công
            $chamCong = ChamCong::firstOrNew([
                'nguoi_dung_id' => $user->id,
                'ngay_cham_cong' => $today,
            ]);

            if ($request->loai == 'check_in') {
                // ✅ LƯU CHECK-IN
                $chamCong->gio_vao = $now;
                $chamCong->ca_lam_viec_id = $ca ? $ca->id : null;
                $chamCong->phuong_thuc_cham_cong = 'face';
                $chamCong->trang_thai = 'dung_gio';
                $chamCong->dia_chi_ip = $request->ip();
                $chamCong->ten_wifi = $request->header('X-WiFi-SSID');
                $chamCong->dia_chi_mac = $request->header('X-MAC-Address');
                $chamCong->ten_thiet_bi = $request->userAgent();
                $message = '✅ Check-in thành công!';
            } else {
                // ✅ LƯU CHECK-OUT
                $chamCong->gio_ra = $now;
                $chamCong->ca_lam_viec_id = $chamCongHomNay ? $chamCongHomNay->ca_lam_viec_id : ($ca ? $ca->id : null);
                $chamCong->phuong_thuc_cham_cong = 'face';
                $chamCong->trang_thai = 'dung_gio';
                $chamCong->dia_chi_ip = $request->ip();
                $chamCong->ten_wifi = $request->header('X-WiFi-SSID');
                $chamCong->dia_chi_mac = $request->header('X-MAC-Address');
                $chamCong->ten_thiet_bi = $request->userAgent();
                
                // Tính số giờ làm và số công
                if ($chamCongHomNay && $chamCongHomNay->gio_vao) {
                    $gioVao = Carbon::parse($chamCongHomNay->gio_vao);
                    $soPhutLam = $gioVao->diffInMinutes($now);
                    $chamCong->so_gio_lam = round($soPhutLam / 60, 2);
                    $chamCong->so_cong = round($chamCong->so_gio_lam / 8, 2);
                    if ($chamCong->so_cong > 1) $chamCong->so_cong = 1;
                }
                
                $message = '✅ Check-out thành công!';
            }

            // ✅ LƯU VÀO DATABASE
            $chamCong->save();

            // Cập nhật cham_cong_id cho log face
            $chamCongFace->cham_cong_id = $chamCong->id;
            $chamCongFace->save();

            Log::info('Face check-in/out saved to cham_cong', [
                'cham_cong_id' => $chamCong->id,
                'user_id' => $user->id,
                'type' => $request->loai,
                'time' => now()->format('H:i:s'),
                'phuong_thuc' => 'face'
            ]);

            return response()->json([
                'success' => true,
                'message' => $message,
                'confidence' => round($confidence * 100, 2),
                'time' => now()->format('H:i:s'),
                'type' => $request->loai,
                'cham_cong_id' => $chamCong->id,
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
     * Xác định ca làm việc dựa vào giờ
     */
    private function xacDinhCaLamViec($gioVao)
    {
        // Ca Sáng: 06:00 - 08:30
        if ($gioVao >= '06:00:00' && $gioVao <= '08:30:00') {
            return CaLamViec::where('ma', 'SANG')->first();
        }
        // Ca Chiều: 12:00 - 13:30
        elseif ($gioVao >= '12:00:00' && $gioVao <= '13:30:00') {
            return CaLamViec::where('ma', 'CHIEU')->first();
        }
        // Check-in muộn ca sáng (08:30 - 12:00)
        elseif ($gioVao >= '08:30:00' && $gioVao < '12:00:00') {
            return CaLamViec::where('ma', 'SANG')->first();
        }
        // Check-in muộn ca chiều (13:30 - 17:00)
        elseif ($gioVao >= '13:30:00' && $gioVao < '17:00:00') {
            return CaLamViec::where('ma', 'CHIEU')->first();
        }
        
        return null;
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
            'history' => $recentHistory,
        ]);
    }

    /**
     * Kiểm tra trạng thái check-in/out (dùng cho API)
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
     * Xóa bản ghi chấm công hôm nay (chỉ dùng cho debug/test)
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