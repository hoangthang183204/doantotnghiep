<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class HopDongLaoDong extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'hop_dong_lao_dong';

    // ========== CONSTANTS ==========
    // Trạng thái hợp đồng
    const TRANG_THAI_TAO_MOI = 'tao_moi';
    const TRANG_THAI_CHUA_HIEU_LUC = 'chua_hieu_luc';
    const TRANG_THAI_HIEU_LUC = 'hieu_luc';
    const TRANG_THAI_HET_HAN = 'het_han';
    const TRANG_THAI_HUY_BO = 'huy_bo';

    // Trạng thái ký
    const TRANG_THAI_KY_CHO_KY = 'cho_ky';
    const TRANG_THAI_KY_DA_KY = 'da_ky';
    const TRANG_THAI_KY_TU_CHOI = 'tu_choi_ky';

    // Trạng thái duyệt (🔥 MỚI)
    const TRANG_THAI_DUYET_CHO_DUYET = 'cho_duyet';
    const TRANG_THAI_DUYET_DA_DUYET = 'da_duyet';
    const TRANG_THAI_DUYET_TU_CHOI = 'tu_choi';

    // Trạng thái tái ký
    const TRANG_THAI_TAI_KY_CHO = 'cho_tai_ky';
    const TRANG_THAI_TAI_KY_DA = 'da_tai_ky';

    // Loại hợp đồng
    const LOAI_THU_VIEC = 'thu_viec';
    const LOAI_XAC_DINH_THOI_HAN = 'xac_dinh_thoi_han';
    const LOAI_KHONG_XAC_DINH_THOI_HAN = 'khong_xac_dinh_thoi_han';
    const LOAI_MUA_VU = 'mua_vu';

    protected $fillable = [
        'nguoi_dung_id',
        'chuc_vu_id',
        'chuc_vu',
        'so_hop_dong',
        'loai_hop_dong',
        'ngay_bat_dau',
        'ngay_ket_thuc',
        'luong_co_ban',
        'phu_cap_id',
        'phu_cap',
        'hinh_thuc_lam_viec',
        'dia_diem_lam_viec',
        'duong_dan_file',
        'file_dinh_kem',
        'file_hop_dong_da_ky',
        'file_scan_ky',
        'dieu_khoan',
        'trang_thai_hop_dong',
        'trang_thai_ky',
        'trang_thai_duyet',
        'nguoi_duyet_id',
        'thoi_gian_duyet',
        'ly_do_tu_choi',
        'nguoi_ky_id',
        'thoi_gian_ky',
        'ghi_chu',
        'ly_do_huy',
        'nguoi_huy_id',
        'thoi_gian_huy',
        'trang_thai_tai_ky',
        'created_by',
        'deleted_at',
        'thoi_gian_gui',
    ];

    protected $casts = [
        'ngay_bat_dau' => 'date',
        'ngay_ket_thuc' => 'date',
        'thoi_gian_ky' => 'datetime',
        'thoi_gian_duyet' => 'datetime',
        'thoi_gian_huy' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'luong_co_ban' => 'decimal:2',
        'phu_cap' => 'array',
        'thoi_gian_gui' => 'datetime'
    ];

    // ========== RELATIONSHIPS ==========
    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_dung_id');
    }

    public function hoSoNguoiDung()
    {
        return $this->belongsTo(HoSoNguoiDung::class, 'nguoi_dung_id', 'nguoi_dung_id');
    }

    public function hoSo()
    {
        return $this->belongsTo(HoSoNguoiDung::class, 'nguoi_dung_id', 'nguoi_dung_id');
    }

    public function chucVu()
    {
        return $this->belongsTo(ChucVu::class, 'chuc_vu_id');
    }

    public function nguoiKy()
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_ky_id');
    }

    public function nguoiDuyet() // 🔥 MỚI
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_duyet_id');
    }

    public function nguoiHuy()
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_huy_id');
    }

    public function nguoiGuiHopDong()
    {
        return $this->belongsTo(NguoiDung::class, 'created_by');
    }

    public function luong()
    {
        return $this->hasOne(Luong::class, 'hop_dong_lao_dong_id');
    }

    public function phuCap()
    {
        return $this->belongsTo(PhuCap::class, 'phu_cap_id');
    }

    public function phuCapNhanViens()
    {
        return $this->hasMany(PhuCapNhanVien::class, 'nguoi_dung_id', 'nguoi_dung_id');
    }

    // ========== SCOPES ==========
    public function scopeChoDuyet($query)
    {
        return $query->where('trang_thai_duyet', self::TRANG_THAI_DUYET_CHO_DUYET);
    }

    public function scopeDaDuyet($query)
    {
        return $query->where('trang_thai_duyet', self::TRANG_THAI_DUYET_DA_DUYET);
    }

    public function scopeBiTuChoiDuyet($query)
    {
        return $query->where('trang_thai_duyet', self::TRANG_THAI_DUYET_TU_CHOI);
    }

    public function scopeHieuLuc($query)
    {
        return $query->where('trang_thai_hop_dong', self::TRANG_THAI_HIEU_LUC);
    }

    public function scopeHetHan($query)
    {
        return $query->where('trang_thai_hop_dong', self::TRANG_THAI_HET_HAN);
    }

    public function scopeChuaHieuLuc($query)
    {
        return $query->where('trang_thai_hop_dong', self::TRANG_THAI_CHUA_HIEU_LUC);
    }

    public function scopeTaoMoi($query)
    {
        return $query->where('trang_thai_hop_dong', self::TRANG_THAI_TAO_MOI);
    }

    public function scopeCanGuiChoNhanVien($query)
    {
        return $query->where('trang_thai_duyet', self::TRANG_THAI_DUYET_DA_DUYET)
            ->where('trang_thai_hop_dong', self::TRANG_THAI_CHUA_HIEU_LUC)
            ->where('trang_thai_ky', self::TRANG_THAI_KY_CHO_KY);
    }

    public function scopeChoKy($query)
    {
        return $query->where('trang_thai_ky', self::TRANG_THAI_KY_CHO_KY);
    }

    public function scopeDaKy($query)
    {
        return $query->where('trang_thai_ky', self::TRANG_THAI_KY_DA_KY);
    }

    // ========== ACCESSORS ==========
    public function getTenTrangThaiDuyetAttribute()
    {
        $map = [
            self::TRANG_THAI_DUYET_CHO_DUYET => '⏳ Chờ duyệt',
            self::TRANG_THAI_DUYET_DA_DUYET => '✅ Đã duyệt',
            self::TRANG_THAI_DUYET_TU_CHOI => '❌ Từ chối',
        ];
        return $map[$this->trang_thai_duyet] ?? $this->trang_thai_duyet;
    }

    public function getTenTrangThaiHopDongAttribute()
    {
        $map = [
            self::TRANG_THAI_TAO_MOI => '🆕 Tạo mới',
            self::TRANG_THAI_CHUA_HIEU_LUC => '⏳ Chưa hiệu lực',
            self::TRANG_THAI_HIEU_LUC => '✅ Hiệu lực',
            self::TRANG_THAI_HET_HAN => '⏰ Hết hạn',
            self::TRANG_THAI_HUY_BO => '🚫 Hủy bỏ',
        ];
        return $map[$this->trang_thai_hop_dong] ?? $this->trang_thai_hop_dong;
    }

    public function getTenTrangThaiKyAttribute()
    {
        $map = [
            self::TRANG_THAI_KY_CHO_KY => '⏳ Chờ ký',
            self::TRANG_THAI_KY_DA_KY => '✅ Đã ký',
            self::TRANG_THAI_KY_TU_CHOI => '❌ Từ chối ký',
        ];
        return $map[$this->trang_thai_ky] ?? $this->trang_thai_ky;
    }

    public function getTenLoaiHopDongAttribute()
    {
        $map = [
            self::LOAI_THU_VIEC => 'Thử việc',
            self::LOAI_XAC_DINH_THOI_HAN => 'Xác định thời hạn',
            self::LOAI_KHONG_XAC_DINH_THOI_HAN => 'Không xác định',
            self::LOAI_MUA_VU => 'Mùa vụ',
        ];
        return $map[$this->loai_hop_dong] ?? $this->loai_hop_dong;
    }

    public function getNgayBatDauFormatAttribute()
    {
        return $this->ngay_bat_dau ? $this->ngay_bat_dau->format('d/m/Y') : '---';
    }

    public function getNgayKetThucFormatAttribute()
    {
        return $this->ngay_ket_thuc ? $this->ngay_ket_thuc->format('d/m/Y') : '♾️ Vô thời hạn';
    }

    public function getPhuCapIdsAttribute()
    {
        if (empty($this->phu_cap)) {
            return [];
        }
        $ids = json_decode($this->phu_cap, true);
        return is_array($ids) ? $ids : [];
    }

    public function getDanhSachFileAttribute()
    {
        if (empty($this->duong_dan_file)) {
            return [];
        }
        return array_filter(explode(';', $this->duong_dan_file));
    }

    public function getDanhSachFileDaKyAttribute()
    {
        if (empty($this->file_hop_dong_da_ky)) {
            return [];
        }
        return array_filter(explode(';', $this->file_hop_dong_da_ky));
    }

    // ========== HELPER METHODS ==========
    public function canGuiChoNhanVien()
    {
        return $this->trang_thai_duyet === self::TRANG_THAI_DUYET_DA_DUYET
            && $this->trang_thai_hop_dong === self::TRANG_THAI_CHUA_HIEU_LUC
            && $this->trang_thai_ky === self::TRANG_THAI_KY_CHO_KY;
    }

    public function canDuyet()
    {
        return $this->trang_thai_duyet === self::TRANG_THAI_DUYET_CHO_DUYET;
    }

    public function canTaiKy()
    {
        return in_array($this->trang_thai_hop_dong, [self::TRANG_THAI_HET_HAN, self::TRANG_THAI_HIEU_LUC])
            && $this->trang_thai_tai_ky !== self::TRANG_THAI_TAI_KY_DA;
    }

    public function isHetHan()
    {
        return $this->trang_thai_hop_dong === self::TRANG_THAI_HET_HAN;
    }

    public function isHieuLuc()
    {
        return $this->trang_thai_hop_dong === self::TRANG_THAI_HIEU_LUC;
    }

    public function isDaKy()
    {
        return $this->trang_thai_ky === self::TRANG_THAI_KY_DA_KY;
    }

    public function isChoKy()
    {
        return $this->trang_thai_ky === self::TRANG_THAI_KY_CHO_KY;
    }

    public function isDaDuyet()
    {
        return $this->trang_thai_duyet === self::TRANG_THAI_DUYET_DA_DUYET;
    }

    public function isChoDuyet()
    {
        return $this->trang_thai_duyet === self::TRANG_THAI_DUYET_CHO_DUYET;
    }

    // ========== BOOT ==========
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->trang_thai_duyet)) {
                $model->trang_thai_duyet = self::TRANG_THAI_DUYET_CHO_DUYET;
            }
            if (empty($model->trang_thai_hop_dong)) {
                $model->trang_thai_hop_dong = self::TRANG_THAI_TAO_MOI;
            }
            if (empty($model->trang_thai_ky)) {
                $model->trang_thai_ky = self::TRANG_THAI_KY_CHO_KY;
            }
        });

        // Tự động cập nhật trạng thái hết hạn
        static::saving(function ($model) {
            // Nếu có ngày kết thúc và ngày kết thúc < hôm nay
            if ($model->ngay_ket_thuc && Carbon::parse($model->ngay_ket_thuc)->lt(now())) {
                if ($model->trang_thai_hop_dong === self::TRANG_THAI_HIEU_LUC) {
                    $model->trang_thai_hop_dong = self::TRANG_THAI_HET_HAN;
                    if (!$model->trang_thai_tai_ky || $model->trang_thai_tai_ky === self::TRANG_THAI_TAI_KY_CHO) {
                        $model->trang_thai_tai_ky = self::TRANG_THAI_TAI_KY_CHO;
                    }
                }
            }

            // Nếu có ngày bắt đầu và ngày bắt đầu <= hôm nay, và trạng thái là chưa hiệu lực
            if ($model->ngay_bat_dau && Carbon::parse($model->ngay_bat_dau)->lte(now())) {
                if ($model->trang_thai_hop_dong === self::TRANG_THAI_CHUA_HIEU_LUC) {
                    $model->trang_thai_hop_dong = self::TRANG_THAI_HIEU_LUC;
                }
            }
        });
    }
}
