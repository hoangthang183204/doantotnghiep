{{-- resources/views/admin/hop-dong-lao-dong/pdf_template.blade.php --}}
<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta charset="UTF-8">
    <title>Hợp đồng lao động</title>
    <style>
        @page {
            margin: 50px 55px 50px 55px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #1a1a1a;
        }

        .header {
            text-align: center;
            margin-bottom: 35px;
            border-bottom: 2px solid #1a1a1a;
            padding-bottom: 25px;
        }

        .header .company-name {
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .header .company-address {
            font-size: 11px;
            color: #555;
            margin-top: 3px;
        }

        .header .title {
            font-size: 22px;
            font-weight: bold;
            margin-top: 25px;
            text-transform: uppercase;
            letter-spacing: 3px;
        }

        .header .sub-title {
            font-size: 14px;
            margin-top: 5px;
            font-weight: normal;
        }

        .header .contract-number {
            font-size: 13px;
            margin-top: 5px;
            font-weight: bold;
        }

        .section {
            margin-bottom: 18px;
        }

        .section-title {
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .section-content {
            padding-left: 25px;
        }

        .section-content p {
            margin-bottom: 6px;
            text-align: justify;
        }

        .section-content ul {
            padding-left: 30px;
            margin-bottom: 6px;
        }

        .section-content ul li {
            margin-bottom: 4px;
            text-align: justify;
        }

        .info-row {
            display: flex;
            margin-bottom: 4px;
        }

        .info-label {
            width: 170px;
            font-weight: 600;
            flex-shrink: 0;
        }

        .info-value {
            flex: 1;
        }

        /* KHU VỰC KÝ - căn đều 2 bên, phù hợp khi xuất PDF bằng Dompdf */
        .signature-area {
            margin-top: 50px;
            width: 100%;
            display: table;
            table-layout: fixed;
            padding: 0;
        }

        .signature-box {
            display: table-cell;
            width: 50%;
            text-align: center;
            vertical-align: top;
            padding: 0 30px;
        }

        .signature-line {
            width: 100%;
            height: 1px;
            margin-top: 50px;
            border-top: 1px solid #1a1a1a;
            padding-top: 8px;
        }

        .signature-label {
            font-size: 11px;
            color: #555;
            line-height: 1.5;
        }

        .text-bold {
            font-weight: bold;
        }

        .mt-10 {
            margin-top: 10px;
        }

        .mt-20 {
            margin-top: 20px;
        }

        .mb-10 {
            margin-bottom: 10px;
        }

        .highlight {
            color: #1a56db;
            font-weight: bold;
        }

        .clause-title {
            font-weight: bold;
            text-decoration: underline;
        }

        .duty-list {
            padding-left: 30px;
            list-style-type: decimal;
        }

        .duty-list li {
            margin-bottom: 5px;
            text-align: justify;
        }

        .benefit-list {
            padding-left: 30px;
            list-style-type: disc;
        }

        .benefit-list li {
            margin-bottom: 5px;
            text-align: justify;
        }

        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 10px;
            color: #888;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }

        .text-center {
            text-align: center;
        }

        .text-justify {
            text-align: justify;
        }

        .company-info-block {
            padding-left: 10px;
        }

        .employee-info-block {
            padding-left: 10px;
        }

        .divider {
            border-top: 2px solid #1a1a1a;
            margin: 25px 0;
        }

        .intro-text {
            text-align: center;
            margin-bottom: 20px;
            font-size: 13px;
            font-weight: bold;
        }

        .clause-number {
            font-weight: bold;
        }
    </style>
</head>

<body>

    {{-- HEADER --}}
    <div class="header">
        <div class="company-name">{{ $company['ten'] }}</div>
        <div class="company-address">
            {{ $company['dia_chi'] }}<br>
            Điện thoại: {{ $company['dien_thoai'] }} | MST: {{ $company['ma_so_thue'] }}
        </div>
        <div class="title">HỢP ĐỒNG LAO ĐỘNG</div>
        <div class="sub-title">Số: {{ $hopDong->so_hop_dong }}/HDLĐ</div>
        <div class="contract-number">Ngày {{ $ngayBatDau->format('d') }} tháng {{ $ngayBatDau->format('m') }} năm
            {{ $ngayBatDau->format('Y') }}</div>
    </div>

    {{-- THÔNG TIN CÔNG TY --}}
    <div class="section">
        <div class="section-title">BÊN A: {{ $company['ten'] }}</div>
        <div class="section-content company-info-block">
            <div class="info-row">
                <span class="info-label">Đại diện:</span>
                <span class="info-value">{{ $company['nguoi_dai_dien'] }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Chức vụ:</span>
                <span class="info-value">{{ $company['chuc_vu_dai_dien'] }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Địa chỉ:</span>
                <span class="info-value">{{ $company['dia_chi'] }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Điện thoại:</span>
                <span class="info-value">{{ $company['dien_thoai'] }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Mã số thuế:</span>
                <span class="info-value">{{ $company['ma_so_thue'] }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Số tài khoản:</span>
                <span class="info-value">{{ $company['tai_khoan'] }}</span>
            </div>
        </div>
    </div>

    {{-- THÔNG TIN NHÂN VIÊN --}}
    <div class="section">
        <div class="section-title">BÊN B: {{ $hoSo ? $hoSo->ho . ' ' . $hoSo->ten : 'N/A' }}</div>
        <div class="section-content employee-info-block">
            <div class="info-row">
                <span class="info-label">Họ và tên:</span>
                <span class="info-value">{{ $hoSo ? $hoSo->ho . ' ' . $hoSo->ten : 'N/A' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Sinh năm:</span>
                <span
                    class="info-value">{{ $hoSo && $hoSo->ngay_sinh ? \Carbon\Carbon::parse($hoSo->ngay_sinh)->format('d/m/Y') : 'N/A' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Quốc tịch:</span>
                <span class="info-value">Việt Nam</span>
            </div>
            <div class="info-row">
                <span class="info-label">Nghề nghiệp:</span>
                <span class="info-value">{{ $tenChucVu }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Địa chỉ thường trú:</span>
                <span class="info-value">{{ $hoSo ? $hoSo->dia_chi_thuong_tru ?? 'N/A' : 'N/A' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Số CMTND/CCCD:</span>
                <span class="info-value">{{ $hoSo ? $hoSo->cmnd_cccd ?? 'N/A' : 'N/A' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Số sổ lao động:</span>
                <span class="info-value">................................................</span>
            </div>
        </div>
    </div>

    <div class="divider"></div>

    {{-- NỘI DUNG CHÍNH --}}
    <div class="intro-text">
        <p>Cùng thỏa thuận ký kết Hợp đồng lao động (HĐLĐ) và cam kết làm đúng những điều khoản sau đây:</p>
    </div>

    {{-- ĐIỀU 1 --}}
    <div class="section">
        <div class="section-title">Điều 1: Điều khoản chung</div>
        <div class="section-content">
            <div class="info-row">
                <span class="info-label">1. Loại HĐLĐ:</span>
                <span class="info-value">{{ $loaiHopDongText }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">2. Thời hạn HĐLĐ:</span>
                <span class="info-value">{{ $thoiHan }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">3. Thời điểm từ:</span>
                <span class="info-value">ngày {{ $ngayBatDau->format('d') }} tháng {{ $ngayBatDau->format('m') }} năm
                    {{ $ngayBatDau->format('Y') }} đến ngày {{ $ngayKetThuc ? $ngayKetThuc->format('d') : '...' }}
                    tháng {{ $ngayKetThuc ? $ngayKetThuc->format('m') : '...' }} năm
                    {{ $ngayKetThuc ? $ngayKetThuc->format('Y') : '...' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">4. Địa điểm làm việc:</span>
                <span class="info-value">{{ $diaChiLamViec }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">5. Bộ phận công tác:</span>
                <span class="info-value">{{ $tenPhongBan }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Chức danh chuyên môn:</span>
                <span class="info-value">{{ $tenChucVu }}</span>
            </div>
            <div class="info-row" style="margin-top: 8px;">
                <span class="info-label">6. Nhiệm vụ công việc:</span>
            </div>
            <div style="padding-left: 30px; margin-top: 5px;">
                <ul class="duty-list">
                    <li>Thực hiện công việc theo đúng chức danh chuyên môn của mình dưới sự quản lý, điều hành của Ban
                        Giám đốc (và các cá nhân được bổ nhiệm hoặc ủy quyền phụ trách).</li>
                    <li>Phối hợp cùng với các bộ phận, phòng ban khác trong Công ty để phát huy tối đa hiệu quả công
                        việc.</li>
                    <li>Hoàn thành những công việc khác tùy thuộc theo yêu cầu kinh doanh của Công ty và theo quyết định
                        của Ban Giám đốc (và các cá nhân được bổ nhiệm hoặc ủy quyền phụ trách).</li>
                </ul>
            </div>
        </div>
    </div>

    {{-- ĐIỀU 2 --}}
    <div class="section">
        <div class="section-title">Điều 2: Chế độ làm việc</div>
        <div class="section-content">
            <div class="info-row">
                <span class="info-label">1. Thời gian làm việc:</span>
                <span class="info-value">Từ ngày {{ $ngayBatDau->format('d/m/Y') }}</span>
            </div>
            <div style="padding-left: 30px; margin-top: 5px;">
                <p>2. Từ ngày thứ 2 đến sáng ngày thứ 6</p>
                <ul style="padding-left: 25px;">
                    <li>Buổi sáng: 8h00 - 12h00</li>
                    <li>Buổi chiều: 13h30 - 17h30</li>
                </ul>
                <p class="mt-10">3. Do tính chất công việc, nhu cầu kinh doanh hay nhu cầu của tổ chức/bộ phận, Công ty
                    có thể cho áp dụng thời gian làm việc linh hoạt. Những nhân viên được áp dụng thời gian làm việc
                    linh hoạt có thể không tuân thủ lịch làm việc cố định bình thường mà làm theo ca kíp, nhưng vẫn phải
                    đảm bảo đủ số giờ làm việc theo quy định.</p>
                <p class="mt-10">4. Thiết bị và công cụ làm việc sẽ được Công ty cấp phát tùy theo nhu cầu của công
                    việc.</p>
                <p>Điều kiện an toàn và vệ sinh lao động tại nơi làm việc theo quy định của pháp luật hiện hành.</p>
            </div>
        </div>
    </div>

    {{-- ĐIỀU 3 --}}
    <div class="section">
        <div class="section-title">Điều 3: Nghĩa vụ và quyền lợi của người lao động</div>
        <div class="section-content">
            <p><span class="clause-title">1. Nghĩa vụ</span></p>
            <ul class="duty-list" style="padding-left: 30px; list-style-type: lower-alpha;">
                <li>Thực hiện công việc với sự tận tâm, tận lực và mẫn cán, đảm bảo hoàn thành công việc với hiệu quả
                    cao nhất theo sự phân công, điều hành (bằng văn bản hoặc bằng miệng) của Ban Giám đốc trong Công ty
                    (và các cá nhân được Ban Giám đốc bổ nhiệm hoặc ủy quyền phụ trách).</li>
                <li>Hoàn thành công việc được giao và sẵn sàng chấp nhận mọi sự điều động khi có yêu cầu.</li>
                <li>Nắm rõ và chấp hành nghiêm túc kỷ luật lao động, an toàn lao động, vệ sinh lao động, PCCC, văn hóa
                    công ty, nội quy lao động và các chủ trương, chính sách của Công ty.</li>
                <li>Bồi thường vi phạm và vật chất theo quy chế, nội quy của Công ty và pháp luật Nhà nước quy định.
                </li>
                <li>Tham dự đầy đủ, nhiệt tình các buổi huấn luyện, đào tạo, hội thảo do Bộ phận hoặc Công ty tổ chức.
                </li>
                <li>Thực hiện đúng cam kết trong HĐLĐ và các thỏa thuận bằng văn bản khác với Công ty.</li>
                <li>Đóng các loại bảo hiểm, các khoản thuế... đầy đủ theo quy định của pháp luật.</li>
                <li>Chế độ đào tạo: Theo quy định của Công ty và yêu cầu công việc. Trong trường hợp CBNV được cử đi đào
                    tạo thì nhân viên phải hoàn thành khoá học đúng thời hạn, phải cam kết sẽ phục vụ lâu dài cho Công
                    ty sau khi kết thúc khoá học và được hưởng nguyên lương, các quyền lợi khác được hưởng như người đi
                    làm.</li>
                <li>Nếu sau khi kết thúc khóa đào tạo mà nhân viên không tiếp tục hợp tác với Công ty thì nhân viên phải
                    hoàn trả lại 100% phí đào tạo và các khoản chế độ đã được nhận trong thời gian đào tạo.</li>
            </ul>

            <p class="mt-10"><span class="clause-title">2. Quyền lợi</span></p>
            <div style="padding-left: 20px;">
                <p><span class="text-bold">a) Tiền lương và phụ cấp:</span></p>
                <ul class="benefit-list">
                    <li>Mức lương chính: <span
                            class="highlight">{{ number_format($hopDong->luong_co_ban, 0, ',', '.') }} VNĐ</span></li>
                    @if ($phuCapDisplay > 0)
                        <li>Phụ cấp: <span class="highlight">{{ number_format($phuCapDisplay, 0, ',', '.') }}
                                VNĐ</span></li>
                        <li>Chi tiết phụ cấp: {{ $phuCapText }}</li>
                    @else
                        <li>Phụ cấp: Không có</li>
                    @endif
                    <li>Phụ cấp hiệu suất công việc: Theo đánh giá của quản lý.</li>
                    <li>Lương hiệu quả: Theo quy định của phòng ban, công ty.</li>
                    <li>Công tác phí: Tùy từng vị trí, người lao động được hưởng theo quy định của công ty.</li>
                    <li>Hình thức trả lương: Lương thời gian.</li>
                </ul>
                <p class="mt-10"><span class="text-bold">b) Các quyền lợi khác:</span></p>
                <ul class="benefit-list">
                    <li>Khen thưởng: Người lao động được khuyến khích bằng vật chất và tinh thần khi có thành tích trong
                        công tác hoặc theo quy định của công ty.</li>
                    <li>Chế độ nâng lương: Theo quy định của Nhà nước và quy chế tiền lương của Công ty. Người lao động
                        hoàn thành tốt nhiệm vụ được giao, không vi phạm kỷ luật và/hoặc không trong thời gian xử lý kỷ
                        luật lao động và đủ điều kiện về thời gian theo quy chế lương thì được xét nâng lương.</li>
                    <li>Chế độ nghỉ: Theo quy định chung của Nhà nước
                        <ul style="padding-left: 25px; list-style-type: circle;">
                            <li>Nghỉ hàng tuần: 1,5 ngày (Chiều Thứ 7 và ngày Chủ nhật).</li>
                            <li>Nghỉ hàng năm: Những nhân viên được ký Hợp đồng chính thức và có thâm niên công tác 12
                                tháng thì sẽ được nghỉ phép năm có hưởng lương (01 ngày phép/01 tháng, 12 ngày phép/01
                                năm). Nhân viên có thâm niên làm việc dưới 12 tháng thì thời gian nghỉ hằng năm được
                                tính theo tỷ lệ tương ứng với số thời gian làm việc.</li>
                            <li>Nghỉ ngày Lễ: Các ngày nghỉ Lễ pháp định. Các ngày nghỉ lễ nếu trùng với ngày Chủ nhật
                                thì sẽ được nghỉ bù vào ngày trước hoặc ngày kế tiếp tùy theo tình hình cụ thể mà Ban
                                lãnh đạo Công ty sẽ chỉ đạo trực tiếp.</li>
                        </ul>
                    </li>
                    <li>Chế độ Bảo hiểm xã hội theo quy định của nhà nước.</li>
                    <li>Các chế độ được hưởng: Người lao động được hưởng các chế độ ngừng việc, trợ cấp thôi việc hoặc
                        bồi thường theo quy định của Pháp luật hiện hành.</li>
                    <li>Thỏa thuận khác: Công ty được quyền chấm dứt HĐLĐ trước thời hạn đối với Người lao động có kết
                        quả đánh giá hiệu suất công việc dưới mức quy định trong 03 tháng liên tục.</li>
                </ul>
            </div>
        </div>
    </div>

    {{-- ĐIỀU 4 --}}
    <div class="section">
        <div class="section-title">Điều 4: Nghĩa vụ và quyền hạn của người sử dụng lao động</div>
        <div class="section-content">
            <p><span class="clause-title">1. Nghĩa vụ</span></p>
            <ul class="duty-list" style="padding-left: 30px; list-style-type: lower-alpha;">
                <li>Thực hiện đầy đủ những điều kiện cần thiết đã cam kết trong Hợp đồng lao động để người lao động đạt
                    hiệu quả công việc cao. Bảo đảm việc làm cho người lao động theo Hợp đồng đã ký.</li>
                <li>Thanh toán đầy đủ, đúng thời hạn các chế độ và quyền lợi cho người lao động theo Hợp đồng lao động.
                </li>
            </ul>
            <p class="mt-10"><span class="clause-title">2. Quyền hạn</span></p>
            <ul class="duty-list" style="padding-left: 30px; list-style-type: lower-alpha;">
                <li>Điều hành người lao động hoàn thành công việc theo Hợp đồng (bố trí, điều chuyển công việc cho người
                    lao động theo đúng chức năng chuyên môn).</li>
                <li>Có quyền chuyển tạm thời lao động, ngừng việc, thay đổi, tạm thời chấm dứt Hợp đồng lao động và áp
                    dụng các biện pháp kỷ luật theo quy định của Pháp luật hiện hành và theo nội quy của Công ty trong
                    thời gian hợp đồng còn giá trị.</li>
                <li>Tam hoãn, chấm dứt Hợp đồng, kỷ luật người lao động theo đúng quy định của Pháp luật, và nội quy lao
                    động của Công ty.</li>
                <li>Có quyền đòi bồi thường, khiếu nại với cơ quan liên đới để bảo vệ quyền lợi của mình nếu người lao
                    động vi phạm Pháp luật hay các điều khoản của hợp đồng này.</li>
            </ul>
        </div>
    </div>

    {{-- ĐIỀU 5 --}}
    <div class="section">
        <div class="section-title">Điều 5: Đơn phương chấm dứt hợp đồng</div>
        <div class="section-content">
            <p><span class="clause-title">1. Người sử dụng lao động</span></p>
            <div style="padding-left: 20px;">
                <p class="text-justify">a) Theo quy định tại điều 38 Bộ luật Lao động thì người sử dụng lao động có
                    quyền đơn phương chấm dứt hợp đồng lao động trong những trường hợp sau đây:</p>
                <ul class="duty-list" style="padding-left: 30px; list-style-type: lower-alpha;">
                    <li>Người lao động thường xuyên không hoàn thành công việc theo hợp đồng.</li>
                    <li>Người lao động bị xử lý kỷ luật sa thải theo quy định tại điều 85 của Bộ luật Lao động.</li>
                    <li>Người lao động làm theo hợp đồng lao động không xác định thời hạn ốm đau đã điều trị 12 tháng
                        liền, người lao động làm theo hợp đồng lao động xác định thời hạn ốm đau đã điều trị 06 tháng
                        liền và người lao động làm theo hợp đồng lao động dưới 01 năm ốm đau đã điều trị quá nửa thời
                        hạn hợp đồng, mà khả năng lao động chưa hồi phục. Khi sức khoẻ của người lao động bình phục, thì
                        được xem xét để giao kết tiếp hợp đồng lao động.</li>
                    <li>Do thiên tai, hòa hoạn, hoặc những lý do bất khả kháng khác mà người sử dụng lao động đã tìm mọi
                        biện pháp khắc phục nhưng vẫn buộc phải thu hẹp sản xuất, giảm chỗ làm việc.</li>
                    <li>Doanh nghiệp, cơ quan, tổ chức chấm dứt hoạt động.</li>
                    <li>Người lao động vi phạm kỷ luật mức sa thải.</li>
                    <li>Người lao động có hành vi gây thiệt hại nghiêm trọng về tài sản và lợi ích của Công ty.</li>
                    <li>Người lao động đang thi hành kỷ luật mức chuyển công tác mà tái phạm.</li>
                    <li>Người lao động tự ý bỏ việc 5 ngày/1 tháng và 20 ngày/1 năm.</li>
                    <li>Người lao động vi phạm Pháp luật Nhà nước.</li>
                </ul>
                <p class="mt-10 text-justify">Trong thời hạn 07 ngày, kể từ ngày chấm dứt Hợp đồng lao động, hai bên có
                    trách nhiệm thanh toán đầy đủ các khoản có liên quan đến quyền lợi của mỗi bên, trường hợp đặc biệt,
                    có thể kéo dài nhưng không quá 30 ngày.</p>
                <p class="text-justify">Trong trường hợp doanh nghiệp bị phá sản thì các khoản có liên quan đến quyền
                    lợi của người lao động được thanh toán theo quy định của Luật Phá sản doanh nghiệp.</p>
            </div>

            <p class="mt-10"><span class="clause-title">2. Người lao động</span></p>
            <div style="padding-left: 20px;">
                <p class="text-justify">a) Khi người lao động đơn phương chấm dứt Hợp đồng lao động trước thời hạn phải
                    tuân thủ theo điều 37 Bộ luật Lao động và phải dựa trên các căn cứ sau:</p>
                <ul class="duty-list" style="padding-left: 30px; list-style-type: lower-alpha;">
                    <li>Không được bố trí theo đúng công việc, địa điểm làm việc hoặc không được bảo đảm các điều kiện
                        làm việc đã thỏa thuận trong hợp đồng.</li>
                    <li>Không được trả công đầy đủ hoặc trả công không đúng thời hạn đã thỏa thuận trong hợp đồng.</li>
                    <li>Bị ngược đãi, bị cưỡng bức lao động.</li>
                    <li>Bản thân hoặc gia đình thật sự có hoàn cảnh khó khăn không thể tiếp tục thực hiện hợp đồng.</li>
                    <li>Được bầu làm nhiệm vụ chuyên trách ở các cơ quan dân cử hoặc được bổ nhiệm giữ chức vụ trong bộ
                        máy Nhà nước.</li>
                    <li>Người lao động nữ có thai phải nghỉ việc theo chỉ định của thầy thuốc.</li>
                    <li>Người lao động bị ốm đau, tai nạn đã điều trị 03 tháng liền mà khả năng lao động chưa được hồi
                        phục.</li>
                </ul>
                <p class="mt-10 text-justify">Ngoài những căn cứ trên, người lao động còn phải đảm bảo thời hạn báo
                    trước như sau:</p>
                <ul class="duty-list" style="padding-left: 30px; list-style-type: lower-alpha;">
                    <li>Đối với các trường hợp quy định tại các điểm a, b và g: ít nhất 03 ngày;</li>
                    <li>Đối với các trường hợp quy định tại điểm d và điểm đ: ít nhất 30 ngày;</li>
                    <li>Đối với trường hợp quy định tại điểm e: theo thời hạn quy định tại Điều 112 của BLLĐ</li>
                    <li>Đối với các lý do khác, người lao động phải đảm bảo thông báo trước
                        <ul style="padding-left: 25px; list-style-type: circle;">
                            <li>Ít nhất 45 ngày đối với hợp đồng lao động không xác định thời hạn.</li>
                            <li>Ít nhất 30 ngày đối với hợp đồng lao động xác định thời hạn từ 01 - 03 năm.</li>
                            <li>Ít nhất 03 ngày đối với hợp đồng lao động theo mùa vụ, theo một công việc nhất định mà
                                thời hạn dưới 01 năm.</li>
                        </ul>
                    </li>
                </ul>
                <p class="mt-10 text-justify">Người lao động có ý định thôi việc vì các lý do khác thì phải thông báo
                    bằng văn bản cho đại diện của Công ty là Phòng Hành chính Nhân sự biết trước ít nhất là 15 ngày.</p>
            </div>
        </div>
    </div>

    {{-- ĐIỀU 6 --}}
    <div class="section">
        <div class="section-title">Điều 6: Những thỏa thuận khác</div>
        <div class="section-content">
            <p class="text-justify">Trong quá trình thực hiện hợp đồng nếu một bên có nhu cầu thay đổi nội dung trong
                hợp đồng phải báo cho bên kia trước ít nhất 03 ngày và ký kết bản Phụ lục hợp đồng theo quy định của
                Pháp luật. Trong thời gian tiến hành thỏa thuận hai bên vẫn tuân theo hợp đồng lao động đã ký kết.</p>
            <p class="mt-10 text-justify">Người lao động đọc kỹ, hiểu rõ và cam kết thực hiện các điều khoản và quy
                định ghi tại Hợp đồng lao động.</p>
        </div>
    </div>

    {{-- ĐIỀU 7 --}}
    <div class="section">
        <div class="section-title">Điều 7: Điều khoản thi hành</div>
        <div class="section-content">
            <p class="text-justify">Những vấn đề về lao động không ghi trong Hợp đồng lao động này thì áp dụng theo quy
                định của Thỏa ước tập thể, nội quy lao động và Pháp luật lao động.</p>
            <p class="mt-10 text-justify">Khi hai bên ký kết Phụ lục hợp đồng lao động thì nội dung của Phụ lục hợp
                đồng lao động cũng có giá trị như các nội dung của bản hợp đồng này.</p>
            <p class="mt-10 text-justify">Hợp đồng này được lập thành 02 (hai) bản có giá trị như nhau, Hành chính nhân
                sự giữ 01 (một) bản, Người lao động giữ 01 (một) bản và có hiệu lực kể từ ngày
                {{ $ngayBatDau->format('d') }} tháng {{ $ngayBatDau->format('m') }} năm
                {{ $ngayBatDau->format('Y') }}.</p>
            <p class="text-justify">Hợp đồng được lập tại: {{ $company['dia_chi'] }}</p>
        </div>
    </div>

    {{-- KHU VỰC CHỮ KÝ: 2 bên bằng nhau, căn thẳng và có khoảng cách lề hợp lý --}}
    <div class="signature-area">
        <div class="signature-box">
            <div class="signature-line"></div>
            <div class="signature-label">
                <strong>NGƯỜI LAO ĐỘNG</strong><br>
                (Ký, ghi rõ họ tên)
            </div>
            <div style="height: 35px;"></div>
        </div>

        <div class="signature-box">
            <div class="signature-line"></div>
            <div class="signature-label">
                <strong>NGƯỜI SỬ DỤNG LAO ĐỘNG</strong><br>
                (Ký, ghi rõ họ tên)
            </div>
            <div style="margin-top: 35px;">
                <span style="font-weight: bold;">{{ $company['nguoi_dai_dien'] }}</span>
            </div>
        </div>
    </div>

    {{-- FOOTER --}}
    <div class="footer">
        Hợp đồng được tạo tự động từ hệ thống HR Flow - Ngày {{ $ngayHienTai }}
    </div>

</body>

</html>