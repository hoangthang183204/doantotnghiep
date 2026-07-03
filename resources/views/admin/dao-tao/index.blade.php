@extends('layouts.admin')

@section('title', 'Quản lý đào tạo')

@section('content')

<div class="space-y-6">

    {{-- HEADER --}}
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 mb-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                Quản lý đào tạo nhân viên
            </h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1">
                Đăng ký và quản lý các khóa đào tạo của nhân viên
            </p>
        </div>

        <a href="{{ route('admin.dao-tao.create') }}"
            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow transition">

            <svg xmlns="http://www.w3.org/2000/svg"
                class="w-5 h-5 mr-2"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor">

                <path stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 4v16m8-8H4" />
            </svg>

            Đăng ký đào tạo

        </a>

    </div>

    <form method="GET" class="mt-6">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">

            <div class="md:col-span-4">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Tìm theo tên nhân viên, mã nhân viên hoặc tên khóa học..."
                    class="w-full
                        px-3 py-2
                        border border-gray-300 dark:border-gray-600
                        rounded-lg
                        bg-white dark:bg-gray-700
                        text-gray-900 dark:text-white
                        placeholder-gray-400 dark:placeholder-gray-500
                        focus:ring-2 focus:ring-blue-500
                        focus:border-blue-500">
            </div>

            <div class="flex gap-2">

                <button
                    type="submit"
                    class="flex-1 bg-blue-600 hover:bg-blue-700 text-white rounded-lg px-4 py-2">

                    Tìm kiếm

                </button>

                <a href="{{ route('admin.dao-tao.index') }}"
                    class="flex-1 text-center bg-gray-500 hover:bg-gray-600 text-white rounded-lg px-4 py-2">

                    Làm mới

                </a>

            </div>

        </div>
    </form>

</div>
    {{-- TABLE --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">

        <div class="overflow-x-auto">

            <table class="w-full">

                <thead class="bg-gray-50 dark:bg-gray-700">

                    <tr>

                        <th class="px-4 py-3 text-center text-gray-800 dark:text-gray-200">#</th>

                        <th class="px-4 py-3 text-left text-gray-800 dark:text-gray-200">Mã NV</th>

                        <th class="px-4 py-3 text-left text-gray-800 dark:text-gray-200">Nhân viên</th>

                        <th class="px-4 py-3 text-left text-gray-800 dark:text-gray-200">Khóa học</th>

                        <th class="px-4 py-3 text-left text-gray-800 dark:text-gray-200">Đơn vị</th>

                        <th class="px-4 py-3 text-center text-gray-800 dark:text-gray-200">Bắt đầu</th>

                        <th class="px-4 py-3 text-center text-gray-800 dark:text-gray-200">Kết thúc</th>

                        <th class="px-4 py-3 text-center text-gray-800 dark:text-gray-200">Thao tác</th>

                    </tr>

                </thead>

                <tbody>

                @forelse($daoTaos as $item)

                    <tr class="border-t border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/40 transition">

                        <td class="px-4 py-3 text-center text-gray-800 dark:text-gray-200">
                            {{ $loop->iteration + ($daoTaos->currentPage()-1)*$daoTaos->perPage() }}
                        </td>

                        <td class="px-4 py-3 font-medium text-gray-800 dark:text-white">
                            {{ $item->hoSo->ma_nhan_vien }}
                        </td>

                        <td class="px-4 py-3 text-gray-800 dark:text-gray-200">
                            {{ $item->hoSo->ho_ten }}
                        </td>

                        <td class="px-4 py-3 text-gray-800 dark:text-gray-200">
                            {{ $item->ten_khoa_hoc }}
                        </td>

                        <td class="px-4 py-3 text-gray-800 dark:text-gray-200">
                            {{ $item->to_chuc ?? '-' }}
                        </td>

                        <td class="px-4 py-3 text-center text-gray-800 dark:text-gray-200">
                            {{ optional($item->ngay_bat_dau)->format('d/m/Y') }}
                        </td>

                        <td class="px-4 py-3 text-center text-gray-800 dark:text-gray-200">
                            {{ optional($item->ngay_ket_thuc)->format('d/m/Y') ?? '-' }}
                        </td>

                        <td class="px-4 py-3 text-gray-800 dark:text-gray-200">

                            <div class="flex justify-center gap-1.5">

                                {{-- Show --}}
                                <a href="{{ route('admin.dao-tao.show',$item->id) }}"
                                    class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-blue-100 text-blue-600">

                                    <i class="fas fa-eye"></i>

                                </a>

                                {{-- Edit --}}
                                <a href="{{ route('admin.dao-tao.edit',$item->id) }}"
                                    class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-yellow-100 text-yellow-600">

                                    <i class="fas fa-pen"></i>

                                </a>

                                {{-- Delete --}}
                                <form
                                    action="{{ route('admin.dao-tao.destroy',$item->id) }}"
                                    method="POST"
                                    onsubmit="return confirm('Bạn có chắc muốn xóa?')">

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-red-100 text-red-600">

                                        <i class="fas fa-trash"></i>

                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="8" class="text-center py-8 text-gray-500">

                            Chưa có dữ liệu đào tạo.

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>

    {{-- PAGINATION --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4">

        {{ $daoTaos->links() }}

    </div>

</div>

@endsection