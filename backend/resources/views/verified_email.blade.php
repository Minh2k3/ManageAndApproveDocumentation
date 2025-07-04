
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
            <h1 class="text-success">Xác thực email thành công!</h1>
            <p>Bạn đã hoàn tất xác thực tài khoản của mình. Giờ thì hãy trải nghiệm hệ thống của chúng tôi nhé.</p>
            <a href="http://tminh.id.vn/login" class="btn btn-primary mt-4">Đăng nhập</a>
        @elseif (request()->get('status') === 'already_verified')
            <h1 class="text-warning">Bạn đã xác thực email trước đó</h1>
            <p>Tài khoản của bạn đã được xác thực từ trước. Hãy đăng nhập để sử dụng.</p>
            <a href="http://tminh.id.vn/login" class="btn btn-primary mt-4">Đăng nhập</a>
        @elseif (request()->get('status') === 'expired') 
            <h1 class="text-danger">Liên kết xác thực đã hết hạn</h1>
            <p>Liên kết xác thực tài khoản của bạn đã quá hạn. Vui lòng nhập lại email để nhận liên kết mới.</p>
            <form action="{{ route('verification.resend') }}" method="POST" class="resend-form">
                @csrf
                <div class="mb-3 row d-flex justify-content-between align-items-center">
                    {{--  <label for="email" class="form-label col-5">Email đăng ký</label>  --}}
                    <div class="input-group col-6">
                        <span class="input-group-text ">@</span>
                        <input type="email" class=" form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="Nhập email đã đăng ký" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 resend-btn">
                    <i class="fas fa-paper-plane me-2"></i> Gửi lại email xác thực
                </button>
            </form>
        @elseif (request()->get('status') === 'not_found')
            <h1 class="text-danger">Không tìm thấy tài khoản của bạn</h1>
            <p>Email của bạn không kết nối tới tài khoản nào. Vui lòng kiểm tra và thử lại.</p>
            <a href="http://tminh.id.vn/login" class="btn btn-primary mt-4">Đăng nhập</a>
        @else
            <h1 class="text-danger">Invalid Status</h1>
            <p>Có lỗi xảy ra. Vui lòng thử lại.</p>
            <a href="http://tminh.id.vn" class="btn btn-primary mt-4">Trang chủ</a>
        @endif

    </div>
</body>
</html>
