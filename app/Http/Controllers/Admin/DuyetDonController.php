<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TinTuyenDung;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Schema;

class DuyetDonController extends Controller
{
    public function index(Request $request)
    {
        $items = TinTuyenDung::orderBy('created_at', 'desc')->paginate(15);
        // history: những tin đã được xử lý (không đang tuyển)
        $history = TinTuyenDung::where('trang_thai', '!=', 'dang_tuyen')
            ->orderBy('updated_at', 'desc')
            ->paginate(10, ['*'], 'history_page');

        return view('admin.duyetdon.tuyendung.index', compact('items', 'history'));
    }

    public function show($id)
    {
        $item = TinTuyenDung::findOrFail($id);
        return view('admin.duyetdon.tuyendung.show', compact('item'));
    }

    public function duyet(Request $request, $id)
    {
        $item = TinTuyenDung::findOrFail($id);
        // Use enum values defined in migration: nhap, dang_tuyen, tam_dung, ket_thuc
        // Set to 'ket_thuc' to indicate approved/finished
        $item->trang_thai = 'ket_thuc';
        $item->save();
        return Redirect::route('admin.duyetdon.tuyendung.index')->with('success', 'Đã duyệt đơn tuyển dụng.');
    }

    public function tuChoi(Request $request, $id)
    {
        $item = TinTuyenDung::findOrFail($id);
        $item->trang_thai = 'tam_dung';
        // only set ghi_chu if column exists
        if (Schema::hasColumn($item->getTable(), 'ghi_chu')) {
            $item->ghi_chu = $request->input('ghi_chu');
        }
        $item->save();
        return Redirect::route('admin.duyetdon.tuyendung.index')->with('success', 'Đã từ chối đơn tuyển dụng.');
    }
}
