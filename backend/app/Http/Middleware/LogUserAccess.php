<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\UserAccessLog;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class LogUserAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        Log::info('Request Debug', [
            'path' => $request->path(),
            'full_url' => $request->fullUrl(),
            'is_root' => $request->is('/'),
            'is_dashboard' => $request->is('dashboard'),
            'method' => $request->method(),
            'is_authenticated' => auth()->check()
        ]);

        $wasAuthenticated = auth()->check();
        
        $response = $next($request);
        
        $isNowAuthenticated = auth()->check();

        $shouldLog = false;
        $isDashboard = $this->isDashboardRoute($request);
        
        // Trường hợp 1: Người dùng vừa đăng nhập thành công
        if ($request->is('api/login') && $request->isMethod('post') && !$wasAuthenticated && $isNowAuthenticated) {
            $shouldLog = true;
            Log::info('Logged successful API login');
        }
        
        // Trường hợp 2: Người dùng chưa đăng nhập nhưng đang truy cập trang dashboard
        elseif (!auth()->check() && $isDashboard) {
            $shouldLog = true;
        }

        // Trường hợp 3: Người dùng đã đăng nhập và thời gian truy cập lần cuối là hơn 5 giờ trước
        // hoặc người dùng đã đăng nhập và thời gian truy cập lần cuối là hơn 5
        $lastAccess = $this->lastAccessByUser(auth()->id());
        $last5Hours = Carbon::now()->subHours(5);
        $compareTime = false;

        if (!$shouldLog && $lastAccess && $lastAccess['access_time']) {
            $compareTime = $last5Hours->greaterThan($lastAccess['access_time']);
            if ($compareTime) {
                $shouldLog = true;
            }
        }
        
        // Ghi log nếu thỏa mãn điều kiện
        if ($shouldLog) {
            UserAccessLog::create([
                'user_id' => auth()->id(), // Sẽ là null nếu chưa đăng nhập
                'ip_address' => $request->ip(),
                'is_authenticated' => auth()->check(),
                'access_time' => Carbon::now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
        return $response;
    }
    
    /**
     * Kiểm tra xem route hiện tại có phải là trang dashboard không
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    private function isDashboardRoute(Request $request)
    {
        $dashboardRoutes = [
            '/',                 // Trang chủ
            'dashboard',         // Nếu bạn có route tên 'dashboard'
            '/dashboard',       // Nếu bạn có route '/dashboard'
            'localhost:5173/dashboard', // Nếu bạn có route 'localhost:5173/dashboard'
            '127.0.0.1:5173/dashboard', // Nếu bạn có route '127.0.0.1:5173/dashboard'
            // Thêm route khác nếu cần
        ];
        
        foreach ($dashboardRoutes as $route) {
            if ($request->is($route)) {
                return true;
            }
        }
        
        return false;
    }

    private function lastAccessByUser($userId)
    {
        try {
            $lastAccess = UserAccessLog::where('user_id', $userId)
                ->orderBy('access_time', 'desc')
                ->first();

            $lastAccessTime = $lastAccess ? Carbon::createFromFormat('H:i:s d/m/Y', $lastAccess->access_time) : null;

            Log::info('Last access fetched for user', [
                'user_id' => $userId,
                'last_access_time' => $lastAccessTime ? $lastAccessTime->toDateTimeString() : null
            ]);

            return $lastAccess ? ['access_time' => $lastAccessTime] : null;
        } catch (\Exception $e) {
            Log::error('Error fetching last access for user', [
                'user_id' => $userId,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }
}