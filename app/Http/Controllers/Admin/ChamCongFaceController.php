<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NguoiDung;
use App\Models\FaceData;
use App\Models\ChamCongFace;
use App\Services\FaceRecognitionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ChamCongFaceController extends Controller
{
    protected $faceService;

    public function __construct(FaceRecognitionService $faceService)
    {
        $this->faceService = $faceService;
    }

    public function index()
    {
        $faceData = FaceData::with('nguoiDung.hoSo')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.cham-cong-face.index', compact('faceData'));
    }

    public function create()
    {
        $nhanViens = NguoiDung::whereHas('hoSo')
            ->where('trang_thai', 1)
            ->whereDoesntHave('faceData')
            ->get();

        return view('admin.cham-cong-face.create', compact('nhanViens'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nguoi_dung_id' => 'required|exists:nguoi_dung,id',
            'face_image' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $nhanVien = NguoiDung::findOrFail($request->nguoi_dung_id);

        try {
            // Lưu ảnh tạm
            $imagePath = $request->file('face_image')->store('temp_face', 'public');
            $fullImagePath = storage_path('app/public/' . $imagePath);

            Log::info('Image saved to: ' . $fullImagePath);
            Log::info('File exists: ' . (file_exists($fullImagePath) ? 'Yes' : 'No'));
            Log::info('File size: ' . (file_exists($fullImagePath) ? filesize($fullImagePath) : 'N/A'));

            // Kiểm tra file tồn tại
            if (!file_exists($fullImagePath)) {
                Storage::disk('public')->delete($imagePath);
                return back()->with('error', '❌ Không thể lưu file ảnh.');
            }

            // 🔥 TEST TRỰC TIẾP BẰNG PYTHON
            Log::info('Testing face detection directly...');
            try {
                $detectResult = $this->faceService->detectFace($fullImagePath);
                Log::info('Direct detect result: ' . json_encode($detectResult));
                $faceCount = $detectResult['face_count'] ?? 0;
                Log::info('Direct face count: ' . $faceCount);
            } catch (Exception $e) {
                Log::error('Direct detect failed: ' . $e->getMessage());
                Storage::disk('public')->delete($imagePath);
                return back()->with('error', '❌ Lỗi xử lý ảnh: ' . $e->getMessage());
            }

            // Kiểm tra ảnh có khuôn mặt không
            if (!$this->faceService->isValidFaceImage($fullImagePath)) {
                Storage::disk('public')->delete($imagePath);
                return back()->with('error', '❌ Ảnh không chứa khuôn mặt hoặc chất lượng kém. Vui lòng chọn ảnh khác.');
            }

            // Trích xuất embedding
            $embedding = $this->faceService->getFaceEmbedding($fullImagePath);
            if (!$embedding) {
                Storage::disk('public')->delete($imagePath);
                return back()->with('error', '❌ Không thể trích xuất đặc trưng khuôn mặt. Vui lòng thử lại.');
            }

            Log::info('Embedding extracted, length: ' . count($embedding));

            // Lưu embedding
            $faceId = 'face_' . Str::random(16);
            $embeddingPath = 'face_encodings/' . $faceId . '.npy';
            $fullEmbeddingPath = storage_path('app/public/' . $embeddingPath);

            $dir = dirname($fullEmbeddingPath);
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }

            $this->faceService->saveEmbedding($embedding, $fullEmbeddingPath);

            // Di chuyển ảnh từ temp sang face_images
            $newImagePath = 'face_images/' . $faceId . '.jpg';
            $newFullPath = storage_path('app/public/' . $newImagePath);
            rename($fullImagePath, $newFullPath);

            // Lưu vào database
            FaceData::create([
                'nguoi_dung_id' => $nhanVien->id,
                'embedding_path' => $embeddingPath,
                'image_path' => $newImagePath,
                'face_id' => $faceId,
                'metadata' => [
                    'created_by' => auth()->id(),
                    'created_at' => now(),
                ],
                'is_active' => true,
            ]);

            $hoTen = ($nhanVien->hoSo->ho ?? '') . ' ' . ($nhanVien->hoSo->ten ?? $nhanVien->ten_dang_nhap);

            return redirect()->route('admin.cham-cong-face.index')
                ->with('success', '✅ Đã đăng ký khuôn mặt cho nhân viên ' . $hoTen);

        } catch (Exception $e) {
            Log::error('Store face error: ' . $e->getMessage());
            Log::error('Trace: ' . $e->getTraceAsString());
            
            if (isset($imagePath) && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
            return back()->with('error', '❌ Lỗi hệ thống: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $faceData = FaceData::findOrFail($id);

        if (Storage::disk('public')->exists($faceData->embedding_path)) {
            Storage::disk('public')->delete($faceData->embedding_path);
        }
        if ($faceData->image_path && Storage::disk('public')->exists($faceData->image_path)) {
            Storage::disk('public')->delete($faceData->image_path);
        }

        $faceData->delete();

        return redirect()->route('admin.cham-cong-face.index')
            ->with('success', '✅ Đã xóa dữ liệu khuôn mặt');
    }

    public function thongKe(Request $request)
    {
        $query = ChamCongFace::with('nguoiDung.hoSo')
            ->orderBy('created_at', 'desc');

        if ($request->nguoi_dung_id) {
            $query->where('nguoi_dung_id', $request->nguoi_dung_id);
        }

        if ($request->trang_thai) {
            $query->where('trang_thai', $request->trang_thai);
        }

        $logs = $query->paginate(20);

        $thongKe = [
            'thanh_cong' => ChamCongFace::where('trang_thai', 'thanh_cong')->count(),
            'that_bai' => ChamCongFace::where('trang_thai', 'that_bai')->count(),
            'check_in' => ChamCongFace::where('loai', 'check_in')->count(),
            'check_out' => ChamCongFace::where('loai', 'check_out')->count(),
        ];

        return view('admin.cham-cong-face.thong-ke', compact('logs', 'thongKe'));
    }
}