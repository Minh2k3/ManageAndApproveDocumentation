<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
}
