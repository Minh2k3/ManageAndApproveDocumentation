<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use App\Models\Department;
use App\Models\Approver;
use App\Models\Creator;



class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'department_id' => ['required'],
            'roll_at_department_id' => ['required'],
        ])->validate();

        // Lấy thông tin department
        $department = \DB::table('departments')
            ->where('id', $input['department_id'])
            ->first();
            
        // Xác định role_id dựa trên thuộc tính can_approve
        $role_id = $department->can_approve ? 3 : 2; // 3: Approver, 2: Creator

        $roll_id = $input['roll_at_department_id'];

        // Tạo user mới
        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'department_id' => $input['department_id'],
            'role_id' => $role_id,
            'email_verified_at' => null,
            'remember_token' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Thêm vào bảng approvers hoặc creators
        if ($role_id === 3) {
            Approver::create([
                'user_id' => $user->id,
                'department_id' => $input['department_id'],
                'roll_at_department_id' => $roll_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            Creator::create([
                'user_id' => $user->id,
                'department_id' => $input['department_id'],
                'roll_at_department_id' => $roll_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return $user;
    }
}
