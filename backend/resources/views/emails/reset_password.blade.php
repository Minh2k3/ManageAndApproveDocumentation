<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Đặt lại mật khẩu</title>
</head>
<body>
    <h2>Xin chào {{ $user->name ?? 'Bạn' }},</h2>
    
    <p>Bạn đã yêu cầu đặt lại mật khẩu cho tài khoản của mình.</p>
    
    <p>Vui lòng click vào link dưới đây để đặt lại mật khẩu:</p>
    
    <a href="{{ $resetUrl }}" style="background-color: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">
        Đặt lại mật khẩu
    </a>
    
    <p>Hoặc copy link sau vào trình duyệt:</p>
    <p>{{ $resetUrl }}</p>
    
    <p><strong>Lưu ý:</strong> Link này sẽ hết hạn sau 60 phút.</p>
    
    <p>Nếu bạn không yêu cầu đặt lại mật khẩu, vui lòng bỏ qua email này.</p>
    
    <p>Trân trọng,<br>{{ config('app.name') }}</p>
</body>
</html>