<?php

namespace App\Http\Controllers;

use App\Models\TemplateUser;
use Illuminate\Http\Request;

class TemplateUserController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $templateUsers = TemplateUser::where('user_id', $user->id)->get();
        return response()->json($templateUsers);
    }
}
