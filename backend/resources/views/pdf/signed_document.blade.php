<!DOCTYPE html>
<html>
<head>
    <title>{{ $document_title }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            margin: 40px;
            font-size: 14px;
            color: #333;
        }
        .container {
            max-width: 800px;
            margin: auto;
            padding: 20px;
            border: 2px solid #000;
        }
        .header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        .header img {
            max-width: 100px;
            margin-right: 20px;
        }
        .header .system-title {
            font-size: 18px;
            font-weight: bold;
        }
        .document-title {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .description {
            text-align: center;
            font-size: 12px;
            color: #666;
            margin-bottom: 20px;
        }
        .proposer, .approvers, .footer {
            margin-bottom: 20px;
        }
        .approvers table {
            width: 100%;
            border-collapse: collapse;
        }
        .approvers th, .approvers td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
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
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ $logo }}" alt="Logo">
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
                        <th>Chữ ký</th>
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
                                    Không có chữ ký
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="footer">
            Thời gian tạo file: {{ $generated_at }}
        </div>
    </div>
</body>
</html>