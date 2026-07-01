@extends('layouts.admin')

@section('content')
    <div class="p-6 max-w-7xl mx-auto space-y-6">

        {{-- HEADER --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                    Ứng Viên Trúng Tuyển
                </h1>
                <p class="text-gray-500 dark:text-gray-400 mt-1">
                    Tiếp nhận hồ sơ trúng tuyển và thực hiện cấp phát tài khoản nhân sự mới.
                </p>
            </div>
        </div>

        {{-- THÔNG BÁO FLASH SESSION --}}
        @if(session('success'))
            <div class="p-4 rounded-xl bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-400 border border-green-100 dark:border-green-800 text-sm">
                {!! session('success') !!}
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 rounded-xl bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-400 border border-red-100 dark:border-red-800 text-sm">
                {{ session('error') }}
            </div>
        @endif

        {{-- FILTER --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm p-4">
            <form method="GET" class="flex gap-3 max-w-md">
                {{-- tìm kiếm từ khóa --}}
                <input type="text" name="keyword" value="{{ request('keyword') }}"
                    placeholder="Tìm tên, email, SĐT, mã hồ sơ..."
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white focus:ring-4 focus:ring-blue-100 dark:focus:ring-blue-900/20 outline-none text-sm">

                <button class="px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold transition text-sm shrink-0">
                    Tìm kiếm
                </button>
            </form>
        </div>

        {{-- TABLE DATA --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">

            <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700">
                <h3 class="font-semibold text-gray-900 dark:text-white">
                    Danh sách chờ cấp tài khoản nhân viên
                </h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full table-auto">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-300 text-xs uppercase">
                            <th class="px-5 py-4 text-left">Mã HS</th>
                            <th class="px-5 py-4 text-left">Họ tên</th>
                            <th class="px-5 py-4 text-left">Email</th>
                            <th class="px-5 py-4 text-left">Tin tuyển dụng</th>
                            <th class="px-5 py-4 text-left">Phòng ban</th>
                            <th class="px-5 py-4 text-center">Trạng thái hệ thống</th>
                            <th class="px-5 py-4 text-center">Hành động</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($ungViens as $item)
                            <tr class="hover:bg-blue-50/40 dark:hover:bg-gray-700/40 transition">
                                
                                {{-- Mã hồ sơ --}}
                                <td class="px-5 py-4">
                                    <span class="px-3 py-1 rounded-lg bg-gray-100 dark:bg-gray-700 text-xs font-semibold text-gray-900 dark:text-white">
                                        {{ $item->ma_ho_so }}
                                    </span>
                                </td>

                                {{-- Họ tên --}}
                                <td class="px-5 py-4 font-semibold text-gray-900 dark:text-white">
                                    {{ $item->ho }} {{ $item->ten }}
                                </td>

                                {{-- Email --}}
                                <td class="px-5 py-4 text-gray-600 dark:text-gray-300">{{ $item->email }}</td>

                                {{-- Tin tuyển dụng --}}
                                <td class="px-5 py-4 text-gray-900 dark:text-white font-medium">
                                    {{ $item->tinTuyenDung?->tieu_de }}
                                </td>

                                {{-- Phòng ban --}}
                                <td class="px-5 py-4 text-gray-900 dark:text-white font-medium">
                                    {{ $item->tinTuyenDung?->phongBan?->ten_phong_ban }}
                                </td>

                                {{-- Trạng thái phân loại nhân sự --}}
                                <td class="px-5 py-4 text-center">
                                    @if(isset($item->nguoi_dung_id) && $item->nguoi_dung_id != null)
                                        <span class="inline-block min-w-[120px] whitespace-nowrap px-3 py-1 rounded-full bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 text-xs font-semibold">
                                            Đã có tài khoản
                                        </span>
                                    @else
                                        <span class="inline-block min-w-[120px] whitespace-nowrap px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400 text-xs font-semibold">
                                            Chờ tạo tài khoản
                                        </span>
                                    @endif
                                </td>

                                {{-- Hành động tương tác --}}
                                <td class="px-5 py-4">
                                    <div class="flex justify-center gap-2">
                                        @if(!isset($item->nguoi_dung_id) || $item->nguoi_dung_id == null)
                                            <form action="{{ route('admin.trung-tuyen.convert', $item->id) }}" method="POST"
                                                onsubmit="return confirm('Xác nhận tạo tài khoản nhân viên chính thức cho ứng viên này?')">
                                                @csrf
                                                <button type="submit" 
                                                    class="px-4 py-1.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium transition shadow-sm">
                                                    Tạo Nhân Viên
                                                </button>
                                            </form>
                                        @else
                                            <button disabled 
                                                class="px-4 py-1.5 rounded-xl bg-gray-100 dark:bg-gray-700 text-gray-400 dark:text-gray-500 text-xs font-medium cursor-not-allowed">
                                                Đã tiếp nhận
                                            </button>
                                        @endif
                                    </div>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-10 text-gray-500 dark:text-gray-400">
                                    Không tìm thấy dữ liệu ứng viên trúng tuyển phù hợp
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            <div class="p-4 border-t border-gray-100 dark:border-gray-700">
                {{ $ungViens->links() }}
            </div>

        </div>
    </div>
@endsection