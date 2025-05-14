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
            return redirect(env('FRONTEND_URL') . '/verified_email?status=unknown');
        }

        $user = User::findOrFail($request->route('id'));

        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
            event(new Verified($user));
            return redirect(env('FRONTEND_URL') . '/verified_email?status=verified');
        }

        return redirect(env('FRONTEND_URL') . '/verified_email?status=already_verified');
    }
}
