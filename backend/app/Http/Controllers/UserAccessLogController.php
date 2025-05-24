<?php

namespace App\Http\Controllers;

use App\Models\UserAccessLog;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class UserAccessLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(UserAccessLog $userAccessLog)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UserAccessLog $userAccessLog)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UserAccessLog $userAccessLog)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserAccessLog $userAccessLog)
    {
        //
    }

    public function dailyAccess()
    {
        $days = collect();
        for ($i = 6; $i >= 0; $i--) {
            $days->push(Carbon::today()->subDays($i)->format('Y-m-d'));
        }

        // ✅ Bước 2: Truy vấn đếm lượt truy cập theo ngày
        $accessCounts = DB::table('user_access_logs')
            ->selectRaw('DATE(access_time) as access_date, COUNT(*) as total')
            ->where('access_time', '>=', Carbon::today()->subDays(6))
            ->groupBy('access_date')
            ->pluck('total', 'access_date');

        // ✅ Bước 3: Gộp dữ liệu để đảm bảo đủ 7 ngày (ngày không có thì gán 0)
        $statistics = $days->mapWithKeys(function ($day) use ($accessCounts) {
            return [$day => $accessCounts[$day] ?? 0];
        });

        return response()->json([
            'daily_access' => $statistics
        ]);
    }
}
