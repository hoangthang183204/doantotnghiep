<?php

namespace App\Services;

use App\Models\HoSo;
use App\Models\MauThongBao;
use App\Models\NguoiDung;
use App\Models\NhatKyThongBao;
use App\Models\PushSubscription;
use App\Notifications\ThongBaoChung;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ThongBaoDaKenhService
{
    public function guiTheoMau(NguoiDung $user, string $ma, array $bien = [], ?array $kenh = null): void
    {
        $mau = MauThongBao::where('ma', $ma)->first();
        $tieuDe = $this->dien($mau->tieu_de ?? 'Thông báo', $bien);
        $noiDung = $this->dien($mau->noi_dung ?? '', $bien);
        $this->gui($user, $tieuDe, $noiDung, $bien['url'] ?? null, $kenh, true);
    }

    public function guiNgoaiHeThong(NguoiDung $user, string $tieuDe, string $noiDung, ?string $url = null): void
    {
        $this->gui($user, $tieuDe, $noiDung, $url, null, false);
    }

    public function gui(
        NguoiDung $user,
        string $tieuDe,
        string $noiDung,
        ?string $url = null,
        ?array $chiKenh = null,
        bool $guiTrongHeThong = true
    ): void {
        $cauHinh = DB::table('cau_hinh_kenh_thong_bao')->first();
        $bat = [
            'email' => (bool) ($cauHinh->email ?? true),
            'zalo' => (bool) ($cauHinh->zalo ?? false),
            'sms' => (bool) ($cauHinh->sms ?? false),
            'push' => (bool) ($cauHinh->push ?? true),
        ];

        if ($guiTrongHeThong && ($chiKenh === null || in_array('in_app', $chiKenh, true))) {
            try {
                $user->notify(new ThongBaoChung($tieuDe, $noiDung, $url));
                $this->ghi($user->id, 'in_app', $tieuDe, $noiDung, 'da_gui', null);
            } catch (\Throwable $e) {
                $this->ghi($user->id, 'in_app', $tieuDe, $noiDung, 'loi', $e->getMessage());
            }
        }

        if ($this->duocGui('email', $bat, $chiKenh)) {
            $this->guiEmail($user, $tieuDe, $noiDung);
        }
        if ($this->duocGui('zalo', $bat, $chiKenh)) {
            $this->guiZalo($user, $noiDung, $cauHinh->zalo_access_token ?? null);
        }
        if ($this->duocGui('sms', $bat, $chiKenh)) {
            $this->guiSms($user, $noiDung, $cauHinh);
        }
        if ($this->duocGui('push', $bat, $chiKenh)) {
            $this->guiPush($user, $tieuDe, $noiDung, $url);
        }
    }

    private function duocGui(string $kenh, array $bat, ?array $chiKenh): bool
    {
        if ($chiKenh !== null && !in_array($kenh, $chiKenh, true)) {
            return false;
        }
        return $bat[$kenh] ?? false;
    }

    private function guiEmail(NguoiDung $user, string $tieuDe, string $noiDung): void
    {
        if (!$user->email) {
            $this->ghi($user->id, 'email', $tieuDe, $noiDung, 'bo_qua', 'Không có email');
            return;
        }
        try {
            Mail::raw($noiDung, function ($message) use ($user, $tieuDe) {
                $message->to($user->email)->subject($tieuDe);
            });
            $this->ghi($user->id, 'email', $tieuDe, $noiDung, 'da_gui', null);
        } catch (\Throwable $e) {
            $this->ghi($user->id, 'email', $tieuDe, $noiDung, 'loi', $e->getMessage());
        }
    }

    private function guiZalo(NguoiDung $user, string $noiDung, ?string $token): void
    {
        $zaloId = $user->zalo_id ?? null;
        if (!$token || !$zaloId) {
            $this->ghi($user->id, 'zalo', 'Zalo OA', $noiDung, 'bo_qua', 'Chưa có access token hoặc zalo_id');
            return;
        }
        try {
            $res = Http::withHeaders(['access_token' => $token])
                ->timeout(8)
                ->post('https://openapi.zalo.me/v3.0/oa/message/cs', [
                    'recipient' => ['user_id' => $zaloId],
                    'message' => ['text' => $noiDung],
                ]);
            $this->ghi($user->id, 'zalo', 'Zalo OA', $noiDung, $res->successful() ? 'da_gui' : 'loi', $res->body());
        } catch (\Throwable $e) {
            $this->ghi($user->id, 'zalo', 'Zalo OA', $noiDung, 'loi', $e->getMessage());
        }
    }

    private function guiSms(NguoiDung $user, string $noiDung, $cauHinh): void
    {
        $hoSo = HoSo::where('nguoi_dung_id', $user->id)->first();
        $sdt = $hoSo->so_dien_thoai ?? null;
        $url = $cauHinh->sms_api_url ?? null;
        if (!$url || !$sdt) {
            $this->ghi($user->id, 'sms', 'SMS', $noiDung, 'bo_qua', 'Chưa cấu hình API SMS hoặc số điện thoại');
            return;
        }
        try {
            $res = Http::timeout(8)->post($url, [
                'api_key' => $cauHinh->sms_api_key ?? '',
                'phone' => $sdt,
                'content' => $noiDung,
            ]);
            $this->ghi($user->id, 'sms', 'SMS', $noiDung, $res->successful() ? 'da_gui' : 'loi', $res->body());
        } catch (\Throwable $e) {
            $this->ghi($user->id, 'sms', 'SMS', $noiDung, 'loi', $e->getMessage());
        }
    }

    private function guiPush(NguoiDung $user, string $tieuDe, string $noiDung, ?string $url): void
    {
        $subs = PushSubscription::where('nguoi_dung_id', $user->id)->get();
        if ($subs->isEmpty()) {
            $this->ghi($user->id, 'push', $tieuDe, $noiDung, 'bo_qua', 'Chưa đăng ký nhận thông báo trên thiết bị');
            return;
        }

        if (!class_exists(\Minishlink\WebPush\WebPush::class)) {
            $this->ghi($user->id, 'push', $tieuDe, $noiDung, 'bo_qua', 'Thiết bị đã đăng ký. Cài minishlink/web-push để gửi khi app đang tắt.');
            return;
        }

        $keys = $this->vapid();
        if (!$keys) {
            $this->ghi($user->id, 'push', $tieuDe, $noiDung, 'bo_qua', 'Chưa có khóa VAPID');
            return;
        }

        try {
            $webPush = new \Minishlink\WebPush\WebPush([
                'VAPID' => [
                    'subject' => config('app.url') ?: 'mailto:hr@localhost',
                    'publicKey' => $keys['publicKey'],
                    'privateKey' => $keys['privateKey'],
                ],
            ]);
            $payload = json_encode(['title' => $tieuDe, 'body' => $noiDung, 'url' => $url ?: '/employee/dashboard']);
            foreach ($subs as $sub) {
                $webPush->queueNotification(
                    \Minishlink\WebPush\Subscription::create([
                        'endpoint' => $sub->endpoint,
                        'publicKey' => $sub->p256dh,
                        'authToken' => $sub->auth,
                    ]),
                    $payload
                );
            }
            foreach ($webPush->flush() as $report) {
                if (!$report->isSuccess()) {
                    Log::warning('Web push: ' . $report->getReason());
                }
            }
            $this->ghi($user->id, 'push', $tieuDe, $noiDung, 'da_gui', null);
        } catch (\Throwable $e) {
            $this->ghi($user->id, 'push', $tieuDe, $noiDung, 'loi', $e->getMessage());
        }
    }

    public function vapidPublicKey(): ?string
    {
        return $this->vapid()['publicKey'] ?? null;
    }

    private function vapid(): ?array
    {
        if (!class_exists(\Minishlink\WebPush\VAPID::class)) {
            return null;
        }
        $path = storage_path('app/vapid.json');
        if (!file_exists($path)) {
            $keys = \Minishlink\WebPush\VAPID::createVapidKeys();
            file_put_contents($path, json_encode($keys));
        }
        $keys = json_decode(file_get_contents($path), true);
        return is_array($keys) ? $keys : null;
    }

    private function dien(string $text, array $bien): string
    {
        foreach ($bien as $khoa => $giaTri) {
            $text = str_replace('{{' . $khoa . '}}', (string) $giaTri, $text);
        }
        return $text;
    }

    private function ghi(?int $userId, string $kenh, string $tieuDe, string $noiDung, string $trangThai, ?string $chiTiet): void
    {
        NhatKyThongBao::create([
            'nguoi_dung_id' => $userId,
            'kenh' => $kenh,
            'tieu_de' => mb_substr($tieuDe, 0, 180),
            'noi_dung' => mb_substr($noiDung, 0, 2000),
            'trang_thai' => $trangThai,
            'chi_tiet' => $chiTiet ? mb_substr($chiTiet, 0, 250) : null,
        ]);
    }
}
