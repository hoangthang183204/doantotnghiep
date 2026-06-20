<?php
namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
class QuyDinhController extends Controller
{
    public function index()
    {
        // Chỉ cần gọi trực tiếp view đã fix cứng
        return view('employee.quy-dinh.index');
    }
}