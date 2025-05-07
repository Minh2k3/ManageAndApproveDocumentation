<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Department;
use App\Models\Role;

class RegisterController extends Controller
{
    public function getFormOptions(): JsonResponse
    {
        $departments = \DB::table('departments')
            ->select(
                'id as value',
                 'name as label'
            )
            ->get();

        $roles = \DB::table('roles')
            ->select(
                'id as value',
                'name as label'
            )
            ->get();

        return response()
                    ->json([
                        'departments' => $departments,
                        'roles' => $roles,
                    ])
                    ->header('Access-Control-Allow-Origin', 'http://localhost:5173')
                    ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
                    ->header('Access-Control-Allow-Headers', 'Content-Type, X-CSRF-TOKEN')
                    ->header('Access-Control-Allow-Credentials', 'true');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'department_id' => 'required|integer',
            'role_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'department_id' => $request->department_id,
            'role_id' => $request->role_id,
        ]);

        return response()->json(['message' => 'User registered successfully.']);
    }
}
