<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function show($identifier)
    {
        $user = User::where('id', $identifier)
                    ->orWhere('email', $identifier)
                    ->first();

        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        return response()->json($user);
    }

    public function index() {
        
    }

    public function getUsers() {
        $approvers = \DB::table('approvers')
            ->join('users', 'approvers.user_id', '=', 'users.id')
            ->join('departments', 'approvers.department_id', '=', 'departments.id')
            ->join('roll_at_departments', 'approvers.roll_at_department_id', '=', 'roll_at_departments.id')
            ->select(
                'users.id as id',
                'users.name as name', 
                \DB::raw("CONCAT(roll_at_departments.name, ' - ', departments.name) as roll"),
                'users.email as email',
                'users.status as status',
            )
            ->get();

        $creators = \DB::table('creators')
            ->join('users', 'creators.user_id', '=', 'users.id')
            ->join('departments', 'creators.department_id', '=', 'departments.id')
            ->join('roll_at_departments', 'creators.roll_at_department_id', '=', 'roll_at_departments.id')
            ->select(
                'users.id as id',
                'users.name as name',
                'users.email as email',
                \DB::raw("CONCAT(roll_at_departments.name, ' - ', departments.name) as roll"),
                'users.status as status',
            )
            ->get();

        $activeUsers = $approvers->merge($creators)->unique('id')->values();
        return response()->json([
            'active_users' => $activeUsers,
        ]);
    }

    public function activeUser(Request $request) {
        $id = $request['id'];
        $user = User::find($id);
        if ($user) {
            $user->status = 'active';
            $user->save();
            return response()->json(['message' => 'User activated successfully.']);
        }
        return response()->json(['message' => 'User not found.'], 404);
    }

    public function bannedUser(Request $request) {
        $id = $request['id'];
        $user = User::find($id);
        if ($user) {
            $user->status = 'banned';
            $user->save();
            return response()->json(['message' => 'User banned successfully.']);
        }
        return response()->json(['message' => 'User not found.'], 404);
    }

    public function unbanUser(Request $request) {
        $id = $request['id'];
        $user = User::find($id);
        if ($user) {
            $user->status = 'active';
            $user->save();
            return response()->json(['message' => 'User unbanned successfully.']);
        }
        return response()->json(['message' => 'User not found.'], 404);
    }
}
