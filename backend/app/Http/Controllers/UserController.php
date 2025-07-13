<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;

use App\Http\Controllers\CertificateController;

use App\Mail\UserAccountEmail;
use App\Models\User;
use App\Models\Approver;
use App\Models\Creator;
use App\Models\Department;
use App\Models\RollAtDepartment;
use App\Certificate;

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
                'users.created_at as created_at',
                'users.updated_at as updated_at',
                'users.avatar as avatar',
                'users.phone as phone',
                'users.sex as sex',
                'departments.name as department_name',
                'departments.id as department_id',
            )
            ->orderBy('users.updated_at', 'desc')
            ->get();

        $creators = \DB::table('creators')
            ->join('users', 'creators.user_id', '=', 'users.id')
            ->join('departments', 'creators.department_id', '=', 'departments.id')
            ->join('roll_at_departments', 'creators.roll_at_department_id', '=', 'roll_at_departments.id')
            ->select(
                'users.id as id',
                'users.name as name',
                'users.email as email',
                'roll_at_departments.name as roll_at_department_name',
                'roll_at_departments.id as roll_at_department_id',
                \DB::raw("CONCAT(roll_at_departments.name, ' - ', departments.name) as roll"),
                'users.status as status',
                                'users.created_at as created_at',
                'users.updated_at as updated_at',
                'users.avatar as avatar',
                'users.phone as phone',
                'users.sex as sex',
                'departments.name as department_name',
                'departments.id as department_id',
            )
            ->orderBy('users.updated_at', 'desc')
            ->get();

        $active_users = $approvers->merge($creators)->unique('id')->values();
        $active_users = $active_users->sortByDesc('updated_at')->values();
        return response()->json([
            'active_users' => $active_users,
        ]);
    }

    public function activeUser(Request $request) {
        $id = $request['id'];
        $user = User::find($id);
        if ($user && $user['status'] == 'pending') {
            $user['status'] = 'active';
            $user->save();

            // Generate a new certificate for the user
            $request = new \Illuminate\Http\Request();
            $request->merge(['user_id' => $user->id]);
            $certificateController = new CertificateController();
            $certificateResponse = $certificateController->issueCertificate($request);

            Mail::to($user->email)->send(new UserAccountEmail($user, 'verify_ok', ''));
            return response()->json(['message' => 'User activated successfully.']);
        }
        return response()->json(['message' => 'User not found.'], 404);
    }

    public function bannedUser(Request $request) {
        $id = $request['id'];
        $notification = $request['notification'];
        $user = User::find($id);
        if ($user && $user->status == 'active') {
            $user->status = 'banned';
            $user->save();
            Mail::to($user->email)->send(new UserAccountEmail($user, 'banned', $notification));
            return response()->json(['message' => 'User banned successfully.']);
        }
        return response()->json(['message' => 'User not found.'], 404);
    }

    public function unbanUser(Request $request) {
        $id = $request['id'];
        $user = User::find($id);
        if ($user && $user->status == 'banned') {
            $user->status = 'active';
            $user->save();
            Mail::to($user->email)->send(new UserAccountEmail($user, 'unbanned'));
            return response()->json(['message' => 'User unbanned successfully.']);
        }
        return response()->json(['message' => 'User not found.'], 404);
    }

    public function getApproverByUserId($user_id)
    {
        $approver = Approver::with([
            'department:id,name',
            'rollAtDepartment:id,name,level',
        ])
        ->where('user_id', $user_id)
        ->first();
        if (!$approver) {
            return response()->json(['message' => 'Approver not found.'], 404);
        }
        return response()->json([
            'approver' => $approver,
        ]);
    }

    public function getCreatorByUserId($user_id)
    {
        $creator = Creator::with([
            'department:id,name',
            'rollAtDepartment:id,name,level',
        ])
        ->where('user_id', $user_id)
        ->first();
        if (!$creator) {
            return response()->json(['message' => 'Creator not found.'], 404);
        }
        return response()->json([
            'creator' => $creator,
        ]);
    }

    public function deleteUser($id)
    {
        $user = User::find($id);

        $role_id = $user->role_id;
        if ($role_id == 2) {
            $creator = Creator::where('user_id', $id)->first();
            if ($creator) {
                $creator->delete();
            }
        } else if ($role_id == 3) {
            $approver = Approver::where('user_id', $id)->first();
            if ($approver) {
                $approver->delete();
            }
        }

        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        // Delete the user's certificates
        Certificate::where('user_id', $user->id)->delete();

        // Delete the user
        $user->delete();

        return response()->json(['message' => 'User deleted successfully.']);
    }

}
