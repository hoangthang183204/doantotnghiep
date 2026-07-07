@extends('layouts.admin')

@section('title', 'Quản lý chứng chỉ')

@section('content')

<div class="space-y-6">

    {{-- HEADER --}}
    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm p-6">

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

            <div>

                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                    Quản lý chứng chỉ nhân viên
                </h1>

                <p class="text-gray-500 dark:text-gray-400 mt-1">
                    Danh sách các chứng chỉ được cấp sau quá trình đào tạo.
                </p>

            </div>

        </div>

        {{-- Search --}}
        <form method="GET" class="mt-6">

            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">

                <div class="md:col-span-4">

                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Tìm mã nhân viên, tên nhân viên hoặc chứng chỉ..."
                        class="w-full rounded-lg border border-gray-300 dark:border-gray-600
                        bg-white dark:bg-gray-700
                        text-gray-900 dark:text-white
                        px-4 py-2
                        focus:ring-2 focus:ring-blue-500">

                </div>

                <div class="flex gap-2">

                    <button
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white rounded-lg px-4 py-2">

                        Tìm kiếm

                    </button>

                    <a
                        href="{{ route('admin.chung-chi.index') }}"
                        class="flex-1 text-center bg-gray-500 hover:bg-gray-600 text-white rounded-lg px-4 py-2">

                        Làm mới

                    </a>

                </div>

            </div>

        </form>

    </div>

    {{-- TABLE --}}
    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm overflow-hidden">

        <div class="overflow-x-auto">

            <table class="w-full">

                <thead class="bg-gray-50 dark:bg-gray-700">

                <tr>

                    <th class="px-4 py-3 text-center text-gray-800 dark:text-gray-200">#</th>

                    <th class="px-4 py-3 text-left text-gray-800 dark:text-gray-200">Mã NV</th>

                    <th class="px-4 py-3 text-left text-gray-800 dark:text-gray-200">Nhân viên</th>

                    <th class="px-4 py-3 text-left text-gray-800 dark:text-gray-200">Chứng chỉ</th>

                    <th class="px-4 py-3 text-left text-gray-800 dark:text-gray-200">Đơn vị cấp</th>

                    <th class="px-4 py-3 text-center text-gray-800 dark:text-gray-200">Năm cấp</th>

                    <th class="px-4 py-3 text-center text-gray-800 dark:text-gray-200">Trạng thái</th>

                    <th class="px-4 py-3 text-center text-gray-800 dark:text-gray-200">Thao tác</th>

                </tr>

                </thead>

                <tbody>

                @forelse($chungChis as $item)

                    <tr class="border-t border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/40 transition">

                        <td class="px-4 py-3 text-center text-gray-800 dark:text-gray-200">

                            {{ $loop->iteration + ($chungChis->currentPage()-1)*$chungChis->perPage() }}

                        </td>

                        <td class="px-4 py-3 font-medium text-gray-800 dark:text-white">

                            {{ $item->hoSo->ma_nhan_vien }}

                        </td>

                        <td class="px-4 py-3 text-gray-700 dark:text-gray-300">

                            {{ $item->hoSo->ho_ten }}

                        </td>

                        <td class="px-4 py-3 text-gray-700 dark:text-gray-300">

                            {{ $item->ten_chung_chi }}

                        </td>

                        <td class="px-4 py-3 text-gray-700 dark:text-gray-300">

                            {{ $item->to_chuc_cap }}

                        </td>

                        <td class="px-4 py-3 text-center text-gray-700 dark:text-gray-300">

                            {{ $item->nam_cap }}

                        </td>

                        <td class="px-4 py-3 text-center">

                            <span class="px-3 py-1 rounded-full text-sm {{ $item->mau_trang_thai }}">

                                {{ $item->trang_thai_hien_thi }}

                            </span>

                        </td>

                        <td class="px-4 py-3">

                            <div class="flex justify-center gap-1.5">

                                <a href="{{ route('admin.chung-chi.show',$item->id) }}"
                                   class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-blue-100 text-blue-600">

                                    <i class="fas fa-eye"></i>

                                </a>

                                <a href="{{ route('admin.chung-chi.edit',$item->id) }}"
                                   class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-yellow-100 text-yellow-600">

                                    <i class="fas fa-pen"></i>

                                </a>

                                <form
                                    action="{{ route('admin.chung-chi.destroy',$item->id) }}"
                                    method="POST"
                                    onsubmit="return confirm('Xóa chứng chỉ này?')">

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

                        <td colspan="8"
                            class="text-center py-10 text-gray-500 dark:text-gray-400">

                            Chưa có chứng chỉ nào.

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>

    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm p-4">

        {{ $chungChis->links() }}

    </div>

</div>

@endsection