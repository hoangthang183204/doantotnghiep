{{-- resources/views/employee/tang-ca/edit.blade.php --}}
@extends('layouts.employee')

@section('title', 'Chỉnh sửa kiến nghị tăng ca')

@section('content')
    <div class="space-y-6">

        {{-- HEADER --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                    <i class="fas fa-edit mr-3 text-yellow-600"></i>
                    Chỉnh sửa kiến nghị tăng ca
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Cập nhật kiến nghị tăng ca đã gửi</p>
            </div>
            <a href="{{ route('employee.tang-ca.index') }}"
                class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition">
                ← Quay lại
            </a>
        </div>

        {{-- THÔNG BÁO --}}
        @if (session('success'))
            <div
                class="bg-green-50 dark:bg-green-900/20 border-l-4 border-green-500 text-green-700 dark:text-green-400 px-4 py-3 rounded-lg shadow-sm flex justify-between items-center">
                <span>{{ session('success') }}</span>
                <button onclick="this.parentElement.remove()" class="text-green-700 dark:text-green-400">×</button>
            </div>
        @endif
        @if (session('error'))
            <div
                class="bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 text-red-700 dark:text-red-400 px-4 py-3 rounded-lg shadow-sm">
                {{ session('error') }}
            </div>
        @endif
        @if ($errors->any())
            <div
                class="bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 text-red-700 dark:text-red-400 px-4 py-3 rounded-lg shadow-sm">
                {{ $errors->first() }}
            </div>
        @endif

        {{-- FORM --}}
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <form action="{{ route('employee.tang-ca.update', $tangCa->id) }}" method="POST" id="kienNghiForm">
                @csrf
                @method('PUT')
                <div class="p-6 space-y-4">

                    {{-- Lý do đề nghị --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Lý do đề nghị <span class="text-red-500">*</span>
                        </label>
                        <textarea name="ly_do_tang_ca" id="lyDo" rows="6"
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none"
                            required>{{ old('ly_do_tang_ca', $tangCa->ly_do_tang_ca) }}</textarea>
                        <div class="flex justify-between mt-2">
                            <span class="text-xs text-gray-400">Tối thiểu 10 ký tự</span>
                            <span id="lyDoCount" class="text-xs text-gray-400">{{ strlen($tangCa->ly_do_tang_ca) }}/500</span>
                        </div>
                        @error('ly_do_tang_ca')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Thông tin trạng thái --}}
                    <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
                        <p class="text-sm text-yellow-700 dark:text-yellow-400">
                            <i class="fas fa-info-circle mr-2"></i>
                            Kiến nghị đang ở trạng thái: <strong>🟡 Chờ xử lý</strong>. Sau khi chỉnh sửa, kiến nghị sẽ được gửi lại.
                        </p>
                    </div>

                    {{-- Nút --}}
                    <div class="flex gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button type="submit"
                            class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition flex items-center gap-2">
                            <i class="fas fa-save"></i>
                            Cập nhật kiến nghị
                        </button>
                        <a href="{{ route('employee.tang-ca.index') }}"
                            class="px-6 py-2.5 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition">
                            Hủy
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('lyDo').addEventListener('input', function() {
        document.getElementById('lyDoCount').textContent = this.value.length + '/500';
    });

    document.getElementById('kienNghiForm').addEventListener('submit', function(e) {
        const lyDo = document.getElementById('lyDo').value.trim();
        if (lyDo.length < 10) {
            e.preventDefault();
            alert('⚠️ Lý do đề nghị phải có ít nhất 10 ký tự!');
            return false;
        }
    });
});
</script>
@endpush
@endsection