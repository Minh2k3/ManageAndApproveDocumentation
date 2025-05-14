<?php

namespace App\Http\Controllers;

use App\Models\Approver;
use Illuminate\Http\Request;

class ApproverController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $approvers = \DB::table('approvers')
            ->join('users', 'approvers.user_id', '=', 'users.id')
            ->select(
                'approvers.id as value',
                'users.name as label',
            )
            ->get();
        
        return response()->json([
            'approvers' => $approvers,
        ])->setStatusCode(200, 'Approver retrieved successfully.');
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
    public function show(Approver $approver)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Approver $approver)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Approver $approver)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Approver $approver)
    {
        //
    }

    public function getApproverWithRoll()
    {
        $approvers = \DB::table('approvers')
            ->join('users', 'approvers.user_id', '=', 'users.id')
            ->join('roll_at_departments', 'approvers.roll_at_department_id', '=', 'roll_at_departments.id')
            ->select(
                'approvers.id as value',
                \DB::raw("CONCAT(users.name, ' - ', roll_at_departments.name) as label"),
                'approvers.department_id',
            )
            ->where('users.status', '=', 'active')
            ->get();
        return response()->json([
            'approvers' => $approvers,
        ])->setStatusCode(200, 'Approver retrieved successfully.');
    }
}
