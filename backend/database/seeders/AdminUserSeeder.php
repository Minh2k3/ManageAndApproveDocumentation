<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'Vũ Ngọc Sơn',
            'email' => 'sonvn@tlu.edu.vn',
            'password' => Hash::make('12345678'),
            'email_verified_at' => now(),
            'email_verified' => true,
            'verification_token' => null,
            'verification_token_expiry' => null,
            'verification_resent_count' => 0,
            'last_verification_resent_at' => null,
            'remember_token' => null,
            'role_id' => 1,
            'signature_id' => null,
            'avatar' => null,
            'status' => 'active'
        ]);

        $user = DB::table('users')->where('email', 'sonvn@tlu.edu.vn')->first();

        DB::table('admins')->insert([
            'user_id' => $user->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'name' => 'Trần Minh 2',
            'email' => 'minh@tlu.edu.vn',
            'password' => Hash::make('12345678'),
            'email_verified_at' => now(),
            'email_verified' => true,
            'verification_token' => null,
            'verification_token_expiry' => null,
            'verification_resent_count' => 0,
            'last_verification_resent_at' => null,
            'remember_token' => null,
            'role_id' => 1,
            'signature_id' => null,
            'avatar' => null,
            'status' => 'active'
        ]);

        $user = DB::table('users')->where('email', 'minh@tlu.edu.vn')->first();

        DB::table('admins')->insert([
            'user_id' => $user->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
