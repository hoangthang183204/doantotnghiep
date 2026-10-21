<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json([
                'data' => [],
                'unread_count' => 0,
            ]);
        }

        $notifications = $user->notifications()
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get()
            ->map(function ($notification) {
                $data = $notification->data;
                if (is_string($data)) {
                    $data = json_decode($data, true);
                }

                return [
                    'id' => $notification->id,
                    'data' => $this->chuanHoa($data ?? []),
                    'read_at' => $notification->read_at,
                    'created_at' => $notification->created_at,
                ];
            });

        return response()->json([
            'data' => $notifications,
            'unread_count' => $user->unreadNotifications()->count(),
        ]);
    }

    public function unreadCount(Request $request): JsonResponse
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['count' => 0]);
        }

        return response()->json([
            'count' => $user->unreadNotifications()->count(),
        ]);
    }

    public function markAsRead($id, Request $request): JsonResponse
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Chưa đăng nhập'], 401);
        }

        $notification = $user->notifications()->findOrFail($id);
        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    public function markAllAsRead(Request $request): JsonResponse
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Chưa đăng nhập'], 401);
        }

        $user->unreadNotifications()->update(['read_at' => now()]);

        return response()->json(['success' => true]);
    }

    public function destroy($id, Request $request): JsonResponse
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Chưa đăng nhập'], 401);
        }

        $notification = $user->notifications()->findOrFail($id);
        $notification->delete();

        return response()->json(['success' => true]);
    }

    private function chuanHoa(array $data): array
    {
        if (isset($data['data']) && is_array($data['data']) && empty($data['title'])) {
            $data = array_merge($data['data'], $data);
        }

        $data['title'] = $data['title'] ?? 'Thông báo';
        $data['message'] = $data['message'] ?? ($data['noi_dung'] ?? '');
        $data['url'] = $data['url'] ?? ($data['link'] ?? '#');
        $data['icon'] = $data['icon'] ?? 'bell';
        $data['color'] = $data['color'] ?? 'info';

        $nhan = [
            'ma_don_nghi' => 'Mã đơn',
            'nhan_vien' => 'Nhân viên',
            'phong_ban' => 'Phòng ban',
            'loai_nghi' => 'Loại nghỉ',
            'so_ngay_nghi' => 'Số ngày',
            'tu_ngay' => 'Từ ngày',
            'den_ngay' => 'Đến ngày',
            'ly_do' => 'Lý do',
        ];
        $chiTiet = [];
        foreach ($nhan as $khoa => $ten) {
            if (!empty($data[$khoa]) && $data[$khoa] !== 'N/A') {
                $chiTiet[] = ['nhan' => $ten, 'gia_tri' => (string) $data[$khoa]];
            }
        }
        $data['chi_tiet'] = $chiTiet;

        return $data;
    }
}
