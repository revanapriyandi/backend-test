<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class AuthenticationController extends Controller
{
    public function authenticated()
    {
        $user = User::find(auth()->id());

        if (!$user->hasVerifiedEmail()) {
            $user->sendEmailVerificationNotification();

            return new JsonResponse([
                'message' => 'Your email address is not verified. We have sent you an activation link.'
            ], 403);
        }

        if (!$user->isActive()) {
            return new JsonResponse([
                'message' => 'Your account is not active.'
            ], 403);
        }

        return new JsonResponse([
            'user' => $user,
        ], 200);
    }
}
