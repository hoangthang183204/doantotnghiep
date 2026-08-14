<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Khoản thưởng gán cho nhân viên.
 *
 *  - hinh_thuc = 'dinh_ky' : lặp lại mỗi tháng trong khoảng [ngay_bat_dau, ngay_ket_thuc]
 *  - hinh_thuc = 'mot_lan' : chỉ áp dụng đúng kỳ lương thang/nam
 */
class ThuongNhanVien extends Model
{
    use HasFactory;

    protected $table = 'thuong_nhan_vien';

    protected $fillable = [
        'nguoi_dung_id',
        'loai_thuong_id',
        'hinh_thuc',
        'cach_tinh',
        'gia_tri',
        'chiu_thue',
        'thang',
        'nam',
        'ngay_bat_dau',
        'ngay_ket_thuc',
        'ly_do',
        'trang_thai',
        'nguoi_tao_id',
    ];

    protected $casts = [
        'gia_tri'       => 'decimal:2',
        'chiu_thue'     => 'boolean',
        'thang'         => 'integer',
        'nam'           => 'integer',
        'ngay_bat_dau'  => 'date',
        'ngay_ket_thuc' => 'date',
    ];

    public static array $hinhThucLabels = [
        'dinh_ky' => 'Định kỳ hàng tháng',
        'mot_lan' => 'Áp dụng 1 lần',
    ];

    public static array $trangThaiLabels = [
        'hieu_luc' => 'Hiệu lực',
        'tam_dung' => 'Tạm dừng',
        'huy'      => 'Đã huỷ',
    ];

    // =====================================================================
    // Relationships
    // =====================================================================

    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_dung_id');
    }

    public function loaiThuong()
    {
        return $this->belongsTo(LoaiThuong::class, 'loai_thuong_id');
    }

    public function nguoiTao()
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_tao_id');
    }

    // =====================================================================
    // Scopes
    // =====================================================================

    public function scopeHieuLuc($query)
    {
        return $query->where('trang_thai', 'hieu_luc');
    }

    /**
     * Các khoản thưởng áp dụng cho kỳ lương tháng/năm:
     *  - thưởng 1 lần đúng tháng/năm đó, HOẶC
     *  - thưởng định kỳ còn hiệu lực trong tháng đó.
     */
    public function scopeApDungChoKy($query, int $thang, int $nam)
    {
        $dauThang  = Carbon::create($nam, $thang, 1)->startOfMonth();
        $cuoiThang = (clone $dauThang)->endOfMonth();

        return $query->where(function ($q) use ($thang, $nam, $dauThang, $cuoiThang) {
            $q->where(function ($mot) use ($thang, $nam) {
                $mot->where('hinh_thuc', 'mot_lan')
                    ->where('thang', $thang)
                    ->where('nam', $nam);
            })->orWhere(function ($dk) use ($dauThang, $cuoiThang) {
                $dk->where('hinh_thuc', 'dinh_ky')
                    ->where(function ($bd) use ($cuoiThang) {
                        $bd->whereNull('ngay_bat_dau')
                            ->orWhereDate('ngay_bat_dau', '<=', $cuoiThang->toDateString());
                    })
                    ->where(function ($kt) use ($dauThang) {
                        $kt->whereNull('ngay_ket_thuc')
                            ->orWhereDate('ngay_ket_thuc', '>=', $dauThang->toDateString());
                    });
            });
        });
    }

    // =====================================================================
    // Accessors
    // =====================================================================

    public function getHinhThucTextAttribute(): string
    {
        return self::$hinhThucLabels[$this->hinh_thuc] ?? $this->hinh_thuc;
    }

    public function getHinhThucBadgeAttribute(): string
    {
        return $this->hinh_thuc === 'dinh_ky'
            ? 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-300'
            : 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300';
    }

    public function getTrangThaiTextAttribute(): string
    {
        return self::$trangThaiLabels[$this->trang_thai] ?? $this->trang_thai;
    }

    public function getTrangThaiBadgeAttribute(): string
    {
        return match ($this->trang_thai) {
            'hieu_luc' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300',
            'tam_dung' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300',
            default    => 'bg-gray-100 text-gray-700 dark:bg-slate-700 dark:text-slate-300',
        };
    }

    /** "300.000 đ" hoặc "10% lương CB" */
    public function getGiaTriTextAttribute(): string
    {
        return $this->cach_tinh === 'phan_tram_luong_cb'
            ? rtrim(rtrim(number_format((float) $this->gia_tri, 2), '0'), '.') . '% lương CB'
            : number_format((float) $this->gia_tri) . ' đ';
    }

    /** Mô tả phạm vi áp dụng để hiển thị trên danh sách */
    public function getPhamViTextAttribute(): string
    {
        if ($this->hinh_thuc === 'mot_lan') {
            return 'Kỳ lương ' . $this->thang . '/' . $this->nam;
        }

        $tu  = $this->ngay_bat_dau ? $this->ngay_bat_dau->format('m/Y') : 'không giới hạn';
        $den = $this->ngay_ket_thuc ? $this->ngay_ket_thuc->format('m/Y') : 'không thời hạn';

        return 'Hàng tháng: ' . $tu . ' → ' . $den;
    }

    /** Thưởng có chịu thuế TNCN không (ưu tiên cấu hình riêng, mặc định theo loại thưởng) */
    public function chiuThueThucTe(): bool
    {
        if ($this->chiu_thue !== null) {
            return (bool) $this->chiu_thue;
        }

        return (bool) ($this->loaiThuong->chiu_thue ?? true);
    }

    /** Quy đổi khoản thưởng ra tiền theo lương cơ bản của kỳ lương */
    public function tinhSoTien(float $luongCoBan): float
    {
        if ($this->cach_tinh === 'phan_tram_luong_cb') {
            return round($luongCoBan * (float) $this->gia_tri / 100, 2);
        }

        return round((float) $this->gia_tri, 2);
    }
}
