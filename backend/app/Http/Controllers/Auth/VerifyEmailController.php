<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Auth\Events\Verified;
use App\Models\User;

class VerifyEmailController
{
    public function __invoke(Request $request)
    {
        if (!URL::hasValidSignature($request)) {
            return response()->json(['message' => 'Invalid or expired verification link.'], 403);
        }

        $user = User::findOrFail($request->route('id'));

        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
            event(new Verified($user));
            return redirect(env('FRONTEND_URL') . '/login?status=verified');
        }

        return redirect(env('FRONTEND_URL') . '/login?status=already_verified');
    }
}
