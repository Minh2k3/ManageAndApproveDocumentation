
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
            <h1 class="text-success">Email Verified Successfully!</h1>
            <p>Your email has been successfully verified. You can now access all the features of our platform.</p>
            <a href="http://localhost:5173/login" class="btn btn-primary mt-4">Go to Login</a>
        @elseif (request()->get('status') === 'already_verified')
            <h1 class="text-warning">Email Already Verified</h1>
            <p>Your email has already been verified. You can proceed to login.</p>
            <a href="http://localhost:5173/login" class="btn btn-primary mt-4">Go to Login</a>
        @elseif (request()->get('status') === 'expired') 
            <h1 class="text-danger">Verification Link Expired</h1>
            <p>The verification link has expired. Please request a new verification email.</p>
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
            <h1 class="text-danger">Email Not Found</h1>
            <p>The email address you provided is not associated with any account. Please check and try again.</p>
            <a href="http://localhost:5173/login" class="btn btn-primary mt-4">Go to Login</a>
        @else
            <h1 class="text-danger">Invalid Status</h1>
            <p>Something went wrong. Please try again.</p>
            <a href="http://localhost:5173" class="btn btn-primary mt-4">Go to Dashboard</a>
        @endif

    </div>
</body>
</html>
