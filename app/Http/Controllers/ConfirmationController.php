<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use App\Mail\ConfirmationEmail;
use App\Models\User;

use Illuminate\Support\Facades\Mail;

class ConfirmationController extends Controller
{
    public function confirmEmail($token)
    {
        $user = User::where('confirmation_token', $token)->first();

        if (!$user) {
            return response()->json(['message' => 'Invalid token'], 401);
        }

        $user->update(['status' => 'active', 'confirmation_token' => null]);

        return response()->json(['message' => 'Email confirmed successfully'], 200);
    }
}
