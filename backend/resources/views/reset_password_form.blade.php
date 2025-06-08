
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex justify-content-center align-items-center vh-100 bg-light">
    <div class="text-center">
        @if (request()->get('status') === 'verified')
            <h1 class="text-success">Tạo mật khẩu mới!</h1>
            <p>Vui lòng nhập mật khẩu mới của bạn để hoàn tất quá trình đặt lại mật khẩu.</p>
            <form action="{{ route('password_reset.reset') }}" method="POST" class="mt-4">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control" value="{{ request()->get('email') }}" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Mật khẩu mới</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Xác nhận mật khẩu</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Đặt lại mật khẩu</button>
            </form>
        @elseif (request()->get('status') === 'not_found')
            <h1 class="text-danger">Liên kết không tồn tại</h1>
            <p>Vui lòng kiểm tra và thử lại.</p>
            <a href="http://localhost:5173/login" class="btn btn-primary mt-4">Đăng nhập</a>
        @else
            <h1 class="text-danger">Invalid Status</h1>
            <p>Có lỗi xảy ra. Vui lòng thử lại.</p>
            <a href="http://localhost:5173" class="btn btn-primary mt-4">Trang chủ</a>
        @endif
    </div>
</body>
</html>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const emailInput = document.getElementById('email');
        if (emailInput) {
            emailInput.focus();
        }
    });
</script>
