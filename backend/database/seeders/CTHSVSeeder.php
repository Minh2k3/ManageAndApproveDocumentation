<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CTHSVSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'Chủ tịch tạm thời',
            'email' => 'cthsv@tlu.edu.vn',
            'password' => Hash::make('12345678'),
            'email_verified_at' => now(),
            'email_verified' => true,
            'verification_token' => null,
            'verification_token_expiry' => null,
            'verification_resent_count' => 0,
            'last_verification_resent_at' => null,
            'remember_token' => null,
            'role_id' => 3,
            'signature_id' => null,
            'avatar' => null,
            'status' => 'active'
        ]);

        $user = DB::table('users')->where('email', 'cthsv@tlu.edu.vn')->first();

        DB::table('approvers')->insert([
            'user_id' => $user->id,
            'department_id' => 6,
            'roll_at_department_id' => 15,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
