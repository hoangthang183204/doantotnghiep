<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LichSuXuLyYeuCauLuong;
use App\Models\NguoiDung;
use Illuminate\Http\Request;

class LichSuXuLyYeuCauLuongController extends Controller
{

    public function index(Request $request)
    {

        $query = LichSuXuLyYeuCauLuong::with([
            'yeuCau.nguoiDung.ho_so',
            'nguoiThucHien.ho_so'
        ]);


        // lọc nhân viên

        if($request->nguoi_dung_id){

            $query->whereHas(
                'yeuCau',
                function($q) use ($request){

                    $q->where(
                        'nguoi_dung_id',
                        $request->nguoi_dung_id
                    );

                }
            );

        }



        // lọc hành động

        if($request->hanh_dong){

            $query->where(
                'hanh_dong',
                $request->hanh_dong
            );

        }



        // lọc ngày bắt đầu

        if($request->tu_ngay){

            $query->whereDate(
                'thoi_gian',
                '>=',
                $request->tu_ngay
            );

        }



        // lọc ngày kết thúc

        if($request->den_ngay){

            $query->whereDate(
                'thoi_gian',
                '<=',
                $request->den_ngay
            );

        }



        $lichSus = $query
            ->orderByDesc('thoi_gian')
            ->paginate(15)
            ->withQueryString();



        $nhanViens = NguoiDung::with('ho_so')
            ->whereHas('ho_so')
            ->get();



        return view(
            'admin.lich-su-xu-ly-yeu-cau-luong.index',
            compact(
                'lichSus',
                'nhanViens'
            )
        );

    }

}