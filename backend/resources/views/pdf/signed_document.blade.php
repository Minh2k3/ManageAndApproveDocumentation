<!DOCTYPE html>
<html>
<head>
    <title>{{ $document_title }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 14px;
            color: #333;
        }
        .container {
            max-width: 800px;
            margin: auto;
            padding: 20px;
            position: relative;
        }
        .watermark {
            position: absolute;
            font-size: 80px; /* Dòng này để tùy chỉnh kích cỡ watermark */
            font-weight: bold;
            color: rgba(99, 204, 213, 0.32);
            pointer-events: none;
            user-select: none;
            z-index: 0;
            white-space: nowrap;
            transform: rotate(-45deg);
        }
        .watermark-1 { top: 10px; left: 5%; } /* Góc trên trái */
        .watermark-2 { top: 10%; right: 5%; } /* Góc trên phải */
        .watermark-3 { top: 50%; left: 50%; transform: translate(-50%, -50%) rotate(-45deg); } /* Chính giữa */
        .watermark-4 { bottom: 20px; left: 5%; } /* Góc dưới trái */
        .watermark-5 { bottom: 20px; right: 0px; } /* Góc dưới phải */
        .header {
            text-align: center;
            margin-bottom: 20px;
            position: relative;
            z-index: 1;
        }
        .logos {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 20px; /* Khoảng cách giữa các logo */
            margin-bottom: 15px;
        }
        .logos img {
            max-width: 60px;
            height: auto;
        }
        .system-title {
            font-size: 16px;
            font-weight: bold;
            text-align: center;
        }
        .document-title {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 10px;
            position: relative;
            z-index: 1;
        }
        .description {
            text-align: center;
            font-size: 12px;
            color: #666;
            margin-bottom: 20px;
            position: relative;
            z-index: 1;
        }
        .proposer, .approvers, .footer {
            margin-bottom: 20px;
            position: relative;
            z-index: 1;
        }
        .approvers table {
            width: 100%;
            border-collapse: collapse;
        }
        .approvers th {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: center;
        }
        .approvers td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }
        .approvers td:nth-child(3), 
        .approvers td:nth-child(4) {
            text-align: center;
        }
        .approvers img {
            max-width: 80px;
            height: auto;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #666;
        }
        .note {
            text-align: center;
            font-size: 11px;
            color: #888;
            margin-top: 15px;
            position: relative;
            z-index: 1;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="watermark watermark-3">Himakevolution</div>
        <div class="header">
            <div class="logos">
                <img src="{{ $logo_tlu }}" alt="Logo TLU">
                <img src="{{ $logo_dtn }}" alt="Logo DTN">
                <img src="{{ $logo_hsv }}" alt="Logo HSV">
            </div>
            <div class="system-title">Hệ thống quản lý và phê duyệt văn bản</div>
        </div>
        <div class="document-title">{{ $document_title }}</div>
        <div class="description">
            {{ $description }} | Ngày tạo: {{ $creation_date }}
        </div>
        <div class="proposer">
            <p><strong>Người đề xuất:</strong> {{ $proposer_name }}</p>
            <p><strong>Đơn vị trực thuộc:</strong> {{ $proposer_department }}</p>
        </div>
        <div class="approvers">
            <h4>Danh sách người phê duyệt</h4>
            <table>
                <thead>
                    <tr>
                        <th>Tên</th>
                        <th>Đơn vị</th>
                        <th>Chữ ký ảnh</th>
                        <th>Thời điểm ký</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($approvers as $approver)
                        <tr>
                            <td>{{ $approver['name'] }}</td>
                            <td>{{ $approver['department'] }}</td>
                            <td>
                                @if(!empty($approver['signature']))
                                    <img src="{{ $approver['signature'] }}" alt="Chữ ký">
                                @else
                                    Không có chữ ký ảnh
                                @endif
                            </td>
                            <td>{{ $approver['signed_at'] ?? 'Chưa phê duyệt' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="footer">
            Thời gian tạo file: {{ $generated_at }}
        </div>
        <div class="note">
            <em>Tài liệu này nhằm mục đích xác thực người phê duyệt đã ký trên hệ thống</em>
        </div>
    </div>
</body>
</html>