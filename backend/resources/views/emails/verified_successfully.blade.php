
{{-- resources/views/emails/verification.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <title>Xác thực tài khoản</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        .btn {
            display: inline-block;
            background-color: #3490dc;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
        .footer {
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #eee;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Tài khoản của bạn xác thực thành công!</h2>
        </div>

        <p>Xin chào {{ $user->name }},</p>

        <p>Chúc mừng tài khoản của bạn đã được phê duyệt! Vui lòng nhấp vào nút bên dưới để đăng nhập vào hệ thống:</p>

        @php
            $loginUrl = config('app.frontend_url', 'http://tminh.id.vn') . '/login';
        @endphp
        
        <div style="text-align: center;">
            <a href="{{ $loginUrl }}" class="btn">Đăng nhập</a>
        </div>
        
        <p>Nếu bạn không thể nhấp vào nút, vui lòng sao chép và dán liên kết sau vào trình duyệt của bạn:</p>
        <p>{{ $loginUrl }}</p>

        <p>Nếu bạn không tạo tài khoản này, vui lòng bỏ qua email này.</p>

        <div class="footer">
            <p>Trân trọng,<br>{{ config('app.name') }}</p>
        </div>
    </div>
</body>
</html>