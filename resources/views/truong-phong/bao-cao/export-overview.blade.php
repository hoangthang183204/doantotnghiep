{{-- resources/views/truong-phong/bao-cao/export-overview.blade.php --}}

<table>
    <thead>
        <tr>
            <th colspan="4" style="text-align: center; font-size: 16px; font-weight: bold;">
                BÁO CÁO TỔNG QUAN PHÒNG BAN
            </th>
        </tr>
        <tr>
            <th colspan="4" style="text-align: center; font-size: 12px; font-style: italic;">
                Phòng: {{ $data['phongBan']->ten_phong_ban }} | Tháng: {{ $data['thang'] }}/{{ $data['nam'] }}
            </th>
        </tr>
        <tr></tr>
        <tr>
            <th style="font-weight: bold; background-color: #1F4E79; color: #FFFFFF; text-align: center;">Chỉ tiêu</th>
            <th style="font-weight: bold; background-color: #1F4E79; color: #FFFFFF; text-align: center;">Số lượng</th>
            <th style="font-weight: bold; background-color: #1F4E79; color: #FFFFFF; text-align: center;">Chỉ tiêu</th>
            <th style="font-weight: bold; background-color: #1F4E79; color: #FFFFFF; text-align: center;">Số lượng</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Tổng nhân viên</td>
            <td>{{ $data['tongNhanVien'] }}</td>
            <td>Đi muộn</td>
            <td>{{ $data['soNgayDiMuon'] }}</td>
        </tr>
        <tr>
            <td>Nam</td>
            <td>{{ $data['nhanVienNam'] }}</td>
            <td>Về sớm</td>
            <td>{{ $data['soNgayVeSom'] }}</td>
        </tr>
        <tr>
            <td>Nữ</td>
            <td>{{ $data['nhanVienNu'] }}</td>
            <td>Tăng ca</td>
            <td>{{ $data['soNgayTangCa'] }}</td>
        </tr>
        <tr>
            <td>Tỷ lệ chấm công</td>
            <td>{{ $data['tyLeChamCong'] }}%</td>
            <td>Tổng ngày công</td>
            <td>{{ $data['soNgayLam'] }}</td>
        </tr>
        <tr></tr>
        <tr>
            <th style="font-weight: bold; background-color: #1F4E79; color: #FFFFFF; text-align: center;">Đơn nghỉ phép</th>
            <th style="font-weight: bold; background-color: #1F4E79; color: #FFFFFF; text-align: center;">Số lượng</th>
            <th style="font-weight: bold; background-color: #1F4E79; color: #FFFFFF; text-align: center;">Tăng ca</th>
            <th style="font-weight: bold; background-color: #1F4E79; color: #FFFFFF; text-align: center;">Số lượng</th>
        </tr>
        <tr>
            <td>Tổng đơn</td>
            <td>{{ $data['tongDonNghi'] }}</td>
            <td>Tổng</td>
            <td>{{ $data['tongTangCa'] }}</td>
        </tr>
        <tr>
            <td>Chờ duyệt</td>
            <td>{{ $data['donNghiChoDuyet'] }}</td>
            <td>Chờ duyệt</td>
            <td>{{ $data['tangCaChoDuyet'] }}</td>
        </tr>
        <tr>
            <td>Đã duyệt</td>
            <td>{{ $data['donNghiDaDuyet'] }}</td>
            <td>Đã duyệt</td>
            <td>{{ $data['tangCaDaDuyet'] }}</td>
        </tr>
        <tr>
            <td>Từ chối</td>
            <td>{{ $data['donNghiTuChoi'] }}</td>
            <td></td>
            <td></td>
        </tr>
        <tr></tr>
        <tr>
            <th style="font-weight: bold; background-color: #1F4E79; color: #FFFFFF; text-align: center;">Chỉnh công</th>
            <th style="font-weight: bold; background-color: #1F4E79; color: #FFFFFF; text-align: center;">Số lượng</th>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>Tổng</td>
            <td>{{ $data['tongChinhCong'] }}</td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>Chờ duyệt</td>
            <td>{{ $data['chinhCongChoDuyet'] }}</td>
            <td></td>
            <td></td>
        </tr>
    </tbody>
</table>