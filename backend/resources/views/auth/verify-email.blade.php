<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác thực tài khoản của bạn</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Arial, sans-serif;
            line-height: 1.6;
            color: #333333;
            background-color: #f5f7fa;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .email-card {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .header {
            background-color: #005baa;
            color: white;
            padding: 20px;
            text-align: center;
        }

        .header img {
            max-height: 60px;
            margin-bottom: 10px;
        }

        .header h2 {
            margin: 0;
            font-weight: 600;
            font-size: 22px;
        }

        .content {
            padding: 30px;
        }

        .welcome {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #005baa;
        }

        p {
            margin-bottom: 20px;
            color: #555;
        }

        .verify-button {
            text-align: center;
            margin: 30px 0;
        }

        .button {
            display: inline-block;
            background-color: #005baa;
            color: white;
            text-decoration: none;
            padding: 12px 30px;
            border-radius: 5px;
            font-weight: 600;
            font-size: 16px;
        }

        .alternative-link {
            margin: 20px 0;
            padding: 15px;
            background-color: #f5f7fa;
            border-radius: 5px;
            word-break: break-all;
        }

        .alternative-link a {
            color: #005baa;
            text-decoration: none;
        }

        .footer {
            text-align: center;
            padding: 20px;
            color: #777;
            font-size: 14px;
            border-top: 1px solid #eee;
        }

        .expire-notice {
            font-size: 13px;
            color: #888;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="email-card">
            <div class="header">
                <img src="https://cdn.haitrieu.com/wp-content/uploads/2021/10/Logo-DH-Thuy-Loi.png" alt="Logo TLU">
                <h2>Xác thực tài khoản của bạn</h2>
            </div>

            <div class="content">
                <div class="welcome">Xin chào</div>

                <p>Cảm ơn bạn đã đăng ký tài khoản trên hệ thống quản lý và phê duyệt văn bản điện tử dành cho các tổ chức sinh viên của Trường Đại học Thủy lợi.</p>

                <p>Để hoàn tất quá trình đăng ký và kích hoạt tài khoản của bạn, vui lòng kiểm tra thông tin email để xác nhận.</p>

            </div>

            <div class="footer">
                <p>&copy; {{ date('Y') }} Trường Đại học Thủy lợi. Tất cả các quyền được bảo lưu.</p>
            </div>
        </div>
    </div>
</body>
</html>