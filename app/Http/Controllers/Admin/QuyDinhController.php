<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
class QuyDinhController extends Controller
{
    public function index()
    {
        // Chỉ cần gọi trực tiếp view đã fix cứng
        return view('admin.quy_dinh.index');
    }
    // 1. Hàm hiển thị form sửa
    public function edit()
    {
        // Đọc file json lên để đổ dữ liệu cũ vào form input cho admin thấy
        $settings = \Illuminate\Support\Facades\Storage::json('company_setting.json') ?? [
            'work_start' => '08:30',
            'work_end' => '17:30'
        ];

        return view('admin.quy_dinh.edit', compact('settings'));
    }

    // 2. Hàm xử lý khi ấn nút "Lưu" trên form
    public function update(Request $request)
    {
        $data = [
            'work_start' => $request->input('work_start', '08:30'),
            'work_end' => $request->input('work_end', '17:30'),
        ];

        // Ghi đè dữ liệu mới vào file JSON
        \Illuminate\Support\Facades\Storage::put('company_setting.json', json_encode($data, JSON_PRETTY_PRINT));

        return redirect()->route('employee.quydinh.index')->with('success', 'Cập nhật giờ làm việc thành công!');
    }
}
