<?php

namespace App\Http\Controllers;

use App\Models\RollAtDepartment;
use Illuminate\Http\Request;

class RollAtDepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rollAtDepartments = RollAtDepartment::select([
            'id',
            'id as value',
            'name',
            'name as label',
            'description',
            'level'
        ])
        ->get();

        return response()->json([
            'roll_at_departments' => $rollAtDepartments,
        ])->setStatusCode(200, 'Roll at departments retrieved successfully.');
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
    public function show(RollAtDepartment $rollAtDepartment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RollAtDepartment $rollAtDepartment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RollAtDepartment $rollAtDepartment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RollAtDepartment $rollAtDepartment)
    {
        //
    }
}
