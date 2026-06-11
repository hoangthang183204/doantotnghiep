<?php

namespace App\Http\Controllers\Admin;

use App\Models\LoaiNghiPhep;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoaiNghiController extends Controller
{
    public function index(Request $request) // 1. Thêm tham số Request ở đây
{
    // 2. Tạo một Query Builder để chuẩn bị lọc dữ liệu
    $query = LoaiNghiPhep::query();

    // 3. Nếu người dùng có nhập từ khóa tìm kiếm
    if ($request->has('search') && $request->search != '') {
        $searchTerm = $request->search;
        
        // Tiến hành lọc theo Tên hoặc theo Mã loại nghỉ phép
        $query->where(function($q) use ($searchTerm) {
            $q->where('ten', 'LIKE', "%{$searchTerm}%")
              ->orWhere('ma', 'LIKE', "%{$searchTerm}%");
        });
    }

    // 4. Lấy danh sách kết quả sau khi đã lọc (Sắp xếp mới nhất lên đầu)
    $dsLoaiNghi = $query->latest()->get();
    
    // 5. Thống kê nhanh: Nên dùng trực tiếp Model đếm từ DB 
    // Cách này giúp các số liệu ở Card giữ nguyên tổng số hệ thống, không bị nhảy số khi tìm kiếm lẻ
    $tongLoaiNghi = LoaiNghiPhep::count();
    $dangHoatDong = LoaiNghiPhep::where('trang_thai', 1)->count();
    $coLuong      = LoaiNghiPhep::where('co_luong', 1)->count();
    $khongLuong   = LoaiNghiPhep::where('co_luong', 0)->count();

    return view('admin.loai_nghi_phep.index', compact(
        'dsLoaiNghi', 
        'tongLoaiNghi', 
        'dangHoatDong', 
        'coLuong', 
        'khongLuong'
    ));
}

    // 1. Trang giao diện tạo mới
    public function create()
    {
        return view('admin.loai_nghi_phep.create');
    }

    // 2. Xử lý lưu dữ liệu tạo mới xong sẽ redirect về trang danh sách
    public function store(Request $request)
{
    // 1. Kiểm tra dữ liệu (Form gửi lên là 'ten' nên ở đây phải validate 'ten')
    $request->validate([
        'ten'        => 'required|string|max:255',
        'ma'         => 'required|string|max:50|unique:loai_nghi_phep,ma',
        'co_luong'   => 'required|boolean',
        'trang_thai' => 'required|boolean',
    ], [
        'ten.required' => 'Vui lòng nhập tên loại nghỉ phép.',
        'ma.required'  => 'Vui lòng nhập mã loại nghỉ phép.',
        'ma.unique'    => 'Mã loại nghỉ phép này đã tồn tại.',
    ]);

    // 2. Lưu vào database
    LoaiNghiPhep::create($request->all());

    return redirect()->route('admin.loai-nghi-phep.index')
                     ->with('success', 'Thêm mới loại nghỉ phép thành công!');
}

    // 3. Trang xem chi tiết
    public function show($id)
    {
        $loaiNghi = LoaiNghiPhep::findOrFail($id);
        return view('admin.loai_nghi_phep.show', compact('loaiNghi'));
    }

    // 4. Trang giao diện sửa
    public function edit($id)
    {
        $loaiNghi = LoaiNghiPhep::findOrFail($id);
        return view('admin.loai_nghi_phep.edit', compact('loaiNghi'));
    }

    // 5. Xử lý cập nhật xong sẽ redirect về trang danh sách
    public function update(Request $request, $id)
{
    $loaiNghi = LoaiNghiPhep::findOrFail($id);

    // Validate cho hàm cập nhật
    $request->validate([
        'ten'        => 'required|string|max:255',
        'ma'         => 'required|string|max:50|unique:loai_nghi_phep,ma,' . $id,
        'co_luong'   => 'required|boolean',
        'trang_thai' => 'required|boolean',
    ], [
        'ten.required' => 'Vui lòng nhập tên loại nghỉ phép.',
        'ma.required'  => 'Vui lòng nhập mã loại nghỉ phép.',
        'ma.unique'    => 'Mã loại nghỉ phép này đã tồn tại.',
    ]);

    $loaiNghi->update($request->all());

    return redirect()->route('admin.loai-nghi-phep.index')
                     ->with('success', 'Cập nhật loại nghỉ phép thành công!');
}
}