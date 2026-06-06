<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class QuyDinhController extends Controller
{
    public function index()
    {
        // Chỉ cần gọi trực tiếp view đã fix cứng
        return view('admin.quy_dinh.index');
    }
}