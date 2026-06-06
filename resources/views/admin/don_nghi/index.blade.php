@extends('layouts.admin') 

@section('content')
<div class="p-4 sm:p-6 lg:p-8 w-full min-h-[80vh] flex flex-col text-gray-900 dark:text-gray-100 transition-colors duration-200">
    
    <div class="sm:flex sm:items-center mb-6">
        <div class="sm:flex-auto">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Quản lý đơn xin nghỉ phép</h1>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">Danh sách kiểm duyệt đơn xin nghỉ phép của nhân viên.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 border-l-[6px] border-l-orange-400 p-6 flex flex-col items-center justify-center transition-colors">
            <span class="text-2xl font-bold text-gray-800 dark:text-white">{{ $countChoDuyet }}</span>
            <span class="text-gray-500 dark:text-gray-300 mt-1 text-sm font-medium">Chờ duyệt</span>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 border-l-[6px] border-l-green-500 p-6 flex flex-col items-center justify-center transition-colors">
            <span class="text-2xl font-bold text-gray-800 dark:text-white">{{ $countDaDuyet }}</span>
            <span class="text-gray-500 dark:text-gray-300 mt-1 text-sm font-medium">Đã duyệt</span>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 border-l-[6px] border-l-red-500 p-6 flex flex-col items-center justify-center transition-colors">
            <span class="text-2xl font-bold text-gray-800 dark:text-white">{{ $countTuChoi }}</span>
            <span class="text-gray-500 dark:text-gray-300 mt-1 text-sm font-medium">Từ chối</span>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 text-sm text-green-800 dark:text-green-100 rounded-lg bg-green-50 dark:bg-green-900/50 border border-green-200 dark:border-green-800/50 flex items-center transition-colors">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
            <span class="font-medium">Thành công!&nbsp;</span> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-4 text-sm text-red-800 dark:text-red-100 rounded-lg bg-red-50 dark:bg-red-900/50 border border-red-200 dark:border-red-800/50 flex items-center transition-colors">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
            <span class="font-medium">Lỗi!&nbsp;</span> {{ session('error') }}
        </div>
    @endif

    <div class="bg-white dark:bg-gray-800 shadow-sm ring-1 ring-gray-200 dark:ring-gray-700 sm:rounded-lg overflow-hidden flex-1 transition-colors">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900/50">
                    <tr>
                        <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 dark:text-white">Mã Đơn</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">Mã NV</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">Từ ngày</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">Đến ngày</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">Lý do</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">Trạng thái</th>
                        <th scope="col" class="px-3 py-3.5 text-center text-sm font-semibold text-gray-900 dark:text-white">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
                    @forelse($danhSachDon as $don)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 dark:text-white">{{ $don->ma_don_nghi }}</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-100">{{ $don->nguoi_dung_id }}</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-100">{{ \Carbon\Carbon::parse($don->ngay_bat_dau)->format('d/m/Y') }}</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-100">{{ \Carbon\Carbon::parse($don->ngay_ket_thuc)->format('d/m/Y') }}</td>
                        <td class="px-3 py-4 text-sm text-gray-500 dark:text-gray-100 max-w-xs truncate" title="{{ $don->ly_do }}">{{ $don->ly_do }}</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm">
                            @if($don->trang_thai == 'cho_duyet')
                                <span class="inline-flex items-center rounded-md bg-yellow-50 dark:bg-yellow-900/30 px-2.5 py-1 text-xs font-medium text-yellow-800 dark:text-yellow-400 ring-1 ring-inset ring-yellow-600/20 dark:ring-yellow-500/30">Chờ duyệt</span>
                            @elseif($don->trang_thai == 'da_duyet')
                                <span class="inline-flex items-center rounded-md bg-green-50 dark:bg-green-900/30 px-2.5 py-1 text-xs font-medium text-green-700 dark:text-green-400 ring-1 ring-inset ring-green-600/20 dark:ring-green-500/30">Đã duyệt</span>
                            @else
                                <span class="inline-flex items-center rounded-md bg-red-50 dark:bg-red-900/30 px-2.5 py-1 text-xs font-medium text-red-700 dark:text-red-400 ring-1 ring-inset ring-red-600/10 dark:ring-red-500/30">Từ chối</span>
                            @endif
                        </td>
                        <td class="whitespace-nowrap px-3 py-4 text-center text-sm font-medium">
                            @if($don->trang_thai == 'cho_duyet')
                                <div class="flex justify-center gap-2">
                                    <form action="{{ route('admin.don_nghi.duyet', $don->id) }}" method="POST" class="inline-block m-0">
                                        @csrf
                                        <input type="hidden" name="trang_thai" value="da_duyet">
                                        <button type="submit" class="text-white bg-green-600 hover:bg-green-700 focus:ring-4 focus:ring-green-300 dark:focus:ring-green-800 font-medium rounded-lg text-xs px-3 py-1.5 transition-all" onclick="return confirm('Bạn có chắc muốn DUYỆT đơn này?')">Duyệt</button>
                                    </form>

                                    <form action="{{ route('admin.don_nghi.duyet', $don->id) }}" method="POST" class="inline-block m-0">
                                        @csrf
                                        <input type="hidden" name="trang_thai" value="tu_choi">
                                        <button type="submit" class="text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-xs px-3 py-1.5 transition-all" onclick="return confirm('Bạn có chắc muốn TỪ CHỐI đơn này?')">Từ chối</button>
                                    </form>
                                </div>
                            @else
                                <div class="flex flex-col items-center justify-center gap-1">
                                    <span class="text-gray-400 dark:text-gray-400 text-xs italic">Đã xử lý</span>
                                    
                                    <form action="{{ route('admin.don_nghi.duyet', $don->id) }}" method="POST" class="inline-block m-0">
                                        @csrf
                                        <input type="hidden" name="trang_thai" value="cho_duyet"> 
                                        <button type="submit" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 focus:outline-none text-xs flex items-center transition-colors" onclick="return confirm('Hoàn tác đơn này về trạng thái chờ duyệt?')">
                                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path></svg>
                                            Hoàn tác
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-10 text-center text-sm text-gray-500 dark:text-gray-100">Chưa có đơn xin nghỉ nào trong hệ thống.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            
            @if($danhSachDon->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
                    {{ $danhSachDon->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection