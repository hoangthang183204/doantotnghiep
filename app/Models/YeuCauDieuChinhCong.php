<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class YeuCauDieuChinhCong extends Model
{
    protected $table = 'yeu_cau_dieu_chinh_cong';

    protected $fillable = [
        'nguoi_dung_id',
        'ngay',
        'gio_vao',
        'gio_ra',
        'ly_do',
        'tep_dinh_kem',
        'trang_thai',
        'duyet_boi',
        'duyet_vao',
        'ghi_chu_duyet'
    ];

    protected $casts = [
        'ngay' => 'date',
        'gio_vao' => 'datetime',
        'gio_ra' => 'datetime',
        'duyet_vao' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Định nghĩa các trạng thái
    const TRANG_THAI_CHO_DUYET = 'cho_duyet';
    const TRANG_THAI_DA_DUYET = 'da_duyet';
    const TRANG_THAI_TU_CHOI = 'tu_choi';

    /**
     * Quan hệ với model NguoiDung (người gửi yêu cầu)
     */
    public function nguoiDung(): BelongsTo
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_dung_id');
    }

    /**
     * Quan hệ với model NguoiDung (người duyệt)
     */
    public function nguoiDuyet(): BelongsTo
    {
        return $this->belongsTo(NguoiDung::class, 'duyet_boi');
    }

    /**
     * Accessor cho trạng thái hiển thị
     */
    public function getTrangThaiTextAttribute(): string
    {
        return match ($this->trang_thai) {
            self::TRANG_THAI_CHO_DUYET => 'Chờ duyệt',
            self::TRANG_THAI_DA_DUYET => 'Đã duyệt',
            self::TRANG_THAI_TU_CHOI => 'Từ chối',
            default => 'Không xác định'
        };
    }

    /**
     * Accessor cho màu badge trạng thái
     */
    public function getTrangThaiBadgeClassAttribute(): string
    {
        return match ($this->trang_thai) {
            self::TRANG_THAI_CHO_DUYET => 'bg-yellow-100 text-yellow-700',
            self::TRANG_THAI_DA_DUYET => 'bg-green-100 text-green-700',
            self::TRANG_THAI_TU_CHOI => 'bg-red-100 text-red-700',
            default => 'bg-gray-100 text-gray-700'
        };
    }

    /**
     * Accessor cho icon trạng thái
     */
    public function getTrangThaiIconAttribute(): string
    {
        return match ($this->trang_thai) {
            self::TRANG_THAI_CHO_DUYET => '🟡',
            self::TRANG_THAI_DA_DUYET => '🟢',
            self::TRANG_THAI_TU_CHOI => '🔴',
            default => '⚪'
        };
    }

    /**
     * Kiểm tra xem yêu cầu có thể sửa được không
     */
    public function canEdit(): bool
    {
        return $this->trang_thai === self::TRANG_THAI_CHO_DUYET;
    }

    /**
     * Kiểm tra xem yêu cầu có thể xóa được không
     */
    public function canDelete(): bool
    {
        return $this->trang_thai === self::TRANG_THAI_CHO_DUYET;
    }

    /**
     * Scope để lọc theo trạng thái
     */
    public function scopeTrangThai($query, string $trangThai)
    {
        return $query->where('trang_thai', $trangThai);
    }

    /**
     * Scope để lọc yêu cầu chờ duyệt
     */
    public function scopeChoDuyet($query)
    {
        return $query->where('trang_thai', self::TRANG_THAI_CHO_DUYET);
    }

    /**
     * Scope để lọc yêu cầu đã duyệt
     */
    public function scopeDaDuyet($query)
    {
        return $query->where('trang_thai', self::TRANG_THAI_DA_DUYET);
    }

    /**
     * Scope để lọc yêu cầu từ chối
     */
    public function scopeTuChoi($query)
    {
        return $query->where('trang_thai', self::TRANG_THAI_TU_CHOI);
    }

    /**
     * Scope để lọc theo người dùng
     */
    public function scopeNguoiDung($query, int $nguoiDungId)
    {
        return $query->where('nguoi_dung_id', $nguoiDungId);
    }

    /**
     * Scope để lọc theo khoảng thời gian
     */
    public function scopeTrongKhoang($query, $tuNgay, $denNgay)
    {
        return $query->whereBetween('ngay', [$tuNgay, $denNgay]);
    }

    /**
     * Định dạng ngày hiển thị
     */
    public function getNgayFormatAttribute(): string
    {
        return $this->ngay ? $this->ngay->format('d/m/Y') : '';
    }

    /**
     * Định dạng giờ vào hiển thị
     */
    public function getGioVaoFormatAttribute(): ?string
    {
        return $this->gio_vao ? Carbon::parse($this->gio_vao)->format('H:i') : null;
    }

    /**
     * Định dạng giờ ra hiển thị
     */
    public function getGioRaFormatAttribute(): ?string
    {
        return $this->gio_ra ? Carbon::parse($this->gio_ra)->format('H:i') : null;
    }

    /**
     * Định dạng thời gian duyệt
     */
    public function getDuyetVaoFormatAttribute(): ?string
    {
        return $this->duyet_vao ? $this->duyet_vao->format('d/m/Y H:i') : null;
    }

    /**
     * Định dạng thời gian tạo
     */
    public function getCreatedAtFormatAttribute(): string
    {
        return $this->created_at->format('d/m/Y H:i');
    }

    /**
     * Lấy tên file đính kèm
     */
    public function getTenFileAttribute(): ?string
    {
        return $this->tep_dinh_kem ? basename($this->tep_dinh_kem) : null;
    }

    /**
     * Kiểm tra có file đính kèm không
     */
    public function hasFile(): bool
    {
        return !empty($this->tep_dinh_kem);
    }

    /**
     * Lấy URL download file
     */
    public function getFileUrlAttribute(): ?string
    {
        if (!$this->hasFile()) {
            return null;
        }

        return route('admin.yeu-cau-dieu-chinh-cong.download', $this->id);
    }
}