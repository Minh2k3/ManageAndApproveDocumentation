<?php

namespace App\Http\Controllers;

use App\Models\UserAccessLog;
use Illuminate\Http\Request;
use Carbon\Carbon;

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

    public function getAccessStats()
    {
        // Lấy dữ liệu trong 7 ngày qua
        $stats = UserAccessLog::selectRaw('DATE(access_time) as date, COUNT(*) as visits')
            ->where('access_time', '>=', Carbon::now()->subDays(7)) // Lọc dữ liệu trong 7 ngày qua
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        return response()->json($stats);
    }
}
