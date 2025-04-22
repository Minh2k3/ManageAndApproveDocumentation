<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function create() {
        $users_department = DB::table('departments')
        ->select("id as value", "name as label")
        ->get();

        $users_role = DB::table('roles')
        ->select("id as value", "name as label")
        ->get();

        return response()->json([
            'users_department' => $users_department,
            'users_role' => $users_role,
        ]);
    }
}
