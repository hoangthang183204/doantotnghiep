<table>
    <thead>
        <tr>
            <th>Nhân viên</th>
            <th>Mã NV</th>
            <th>Phòng ban</th>
            <th>Loại</th>
            <th>Tiêu đề</th>
            <th>Ngày</th>
            <th>Số tiền</th>
            <th>Người ký</th>
        </tr>
    </thead>

    <tbody>
        @foreach($data as $item)
            <tr>
                <td>{{ $item->hoSo?->ho_ten }}</td>
                <td>{{ $item->hoSo?->ma_nhan_vien }}</td>
                <td>{{ $item->hoSo?->nguoi_dung?->phongBan?->ten_phong_ban }}</td>
                <td>{{ $item->loai }}</td>
                <td>{{ $item->ten }}</td>
                <td>{{ $item->ngay->format('d/m/Y') }}</td>
                <td>{{ $item->so_tien }}</td>
                <td>{{ $item->nguoiKy?->ten_dang_nhap }}</td>
            </tr>
        @endforeach
    </tbody>
</table>