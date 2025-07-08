<?php

namespace App\Http\Controllers;

use App\Models\ApprovalPermission;
use Illuminate\Http\Request;

class ApprovalPermissionController extends Controller
{
    public function index()
    {
        $permissions = ApprovalPermission::all();
        return response()->json($permissions);
    }
}
