<?php
// app/Models/CauHinhChamCong.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CauHinhChamCong extends Model
{
    protected $table = 'cau_hinh_cham_cong';
    
    protected $fillable = [
        'ten',
        'loai',
        'gia_tri',
        'mo_ta',
        'chi_nhanh_id',
        'trang_thai'
    ];
    
    protected $casts = [
        'trang_thai' => 'boolean'
    ];
    
    public function chi_nhanh()
    {
        return $this->belongsTo(ChiNhanhCongTy::class, 'chi_nhanh_id');
    }
    
    /**
     * Kiểm tra IP có được phép không
     */
    public static function isIPAllowed($ip)
    {
        if (!$ip) return false;
        
        return self::where('loai', 'ip')
            ->where('gia_tri', $ip)
            ->where('trang_thai', 1)
            ->exists();
    }
    
    /**
     * Kiểm tra WiFi có được phép không
     */
    public static function isWiFiAllowed($wifi)
    {
        if (!$wifi) return false;
        
        return self::where('loai', 'wifi')
            ->where('gia_tri', $wifi)
            ->where('trang_thai', 1)
            ->exists();
    }
    
    /**
     * Kiểm tra MAC có được phép không
     */
    public static function isMACAllowed($mac)
    {
        if (!$mac) return false;
        
        return self::where('loai', 'mac')
            ->where('gia_tri', $mac)
            ->where('trang_thai', 1)
            ->exists();
    }
    
    /**
     * Lấy danh sách IP được phép
     */
    public static function getIPsAllowed()
    {
        return self::where('loai', 'ip')
            ->where('trang_thai', 1)
            ->pluck('gia_tri')
            ->toArray();
    }
    
    /**
     * Lấy danh sách WiFi được phép
     */
    public static function getWiFisAllowed()
    {
        return self::where('loai', 'wifi')
            ->where('trang_thai', 1)
            ->pluck('gia_tri')
            ->toArray();
    }
    
    /**
     * Lấy danh sách MAC được phép
     */
    public static function getMACsAllowed()
    {
        return self::where('loai', 'mac')
            ->where('trang_thai', 1)
            ->pluck('gia_tri')
            ->toArray();
    }
    
    /**
     * Kiểm tra vị trí có hợp lệ không
     */
    public static function isValidLocation($ip, $wifi = null, $mac = null)
    {
        $ipAllowed = self::isIPAllowed($ip);
        $wifiAllowed = self::isWiFiAllowed($wifi);
        $macAllowed = self::isMACAllowed($mac);
        
        return $ipAllowed || $wifiAllowed || $macAllowed;
    }
}