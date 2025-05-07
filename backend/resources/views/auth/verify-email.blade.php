<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác Thực Email</title>
    <link href="https://cdn.jsdelivr.net/npm/@tailwindcss/postcss7-compat@^2.0/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-2xl shadow-md w-96 text-center">
        <h1 class="text-2xl font-semibold text-gray-800 mb-4">Xác Thực Email</h1>
        <p class="text-gray-600 mb-6">Vui lòng kiểm tra hộp thư email của bạn và nhấp vào liên kết xác thực để hoàn tất quá trình đăng ký.</p>
        
        <a href="{{ env('FRONTEND_URL') }}" class="bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600 transition-colors">
            Về Trang Chủ
        </a>
        
        <div class="mt-6">
            <small class="text-gray-500">Nếu bạn không nhận được email, vui lòng kiểm tra thư rác hoặc thử đăng ký lại.</small>
        </div>
    </div>
</body>
</html>
