<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // ✅ Thêm SoftDeletes

class ChucVu extends Model
{
    use HasFactory;
    use SoftDeletes; // ✅ Cho phép xóa mềm
    
    protected $table = 'chuc_vu';
    
    protected $fillable = [
        'ten',
        'ma',
        'mo_ta',
        'luong_co_ban',
        'he_so_luong',
        'phong_ban_id',
        'trang_thai'
    ];
    
    protected $casts = [
        'luong_co_ban' => 'decimal:2',
        'he_so_luong' => 'decimal:2',
        'trang_thai' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    
    // Relationships
    public function phong_ban()
    {
        return $this->belongsTo(PhongBan::class, 'phong_ban_id');
    }
    
    public function nguoi_dungs()
    {
        return $this->hasMany(NguoiDung::class, 'chuc_vu_id');
    }
    
    // ✅ Accessor: Lấy lương thực tế
    public function getLuongThucTeAttribute()
    {
        if ($this->luong_co_ban && $this->he_so_luong) {
            return $this->luong_co_ban * $this->he_so_luong;
        }
        return null;
    }
    
    // ✅ Scope: Lọc chức vụ đang hoạt động
    public function scopeActive($query)
    {
        return $query->where('trang_thai', 1);
    }
    
    // ✅ Scope: Lọc chức vụ theo phòng ban
    public function scopeByPhongBan($query, $phongBanId)
    {
        return $query->where('phong_ban_id', $phongBanId);
    }
}