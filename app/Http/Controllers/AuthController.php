<?php

namespace App\Http\Controllers;

use Log;
use Exception;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\ConfirmationEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Response;

class AuthController extends Controller
{
    /**
     * Register a new user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function signup(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'status' => 'invited',
            // 'confirmation_token' => Str::random(40),
        ]);

        // $confirmationUrl = url("/email/verify/{$user->id}/{$user->confirmation_token}");

        // $token = $user->createToken('AppName')->accessToken;

        Mail::to($user->email)->send(new ConfirmationEmail($user->id,$user->confirmation_token));

        // Dispatch the Registered event
        event(new Registered($user));

        // $user->sendEmailVerificationNotification();

        return response()->json(['message' => 'Confirmation email sent.'], 201)->header('Content-Type', 'application/json');
    }

    /**
     * Log in the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $credentials = request(['email', 'password']);

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $user = $request->user();

        if ($user->status !== 'active') {
            // User is not active
            return response('Invalid email or password', 401);
        }

        $token = $user->createToken('AppName')->accessToken;

        return response()->json(['token' => $token]);
    }


    public function confirmEmail($token)
    {
        $user = User::where('confirmation_token', $token)->first();

        if (!$user) {
            // Handle invalid token
            return response()->json(['message' => 'Invalid confirmation token'], 400);
        }

        // Update the user's status to 'active'
        $user->update(['status' => 'active', 'confirmation_token' => null]);

        // return Response::json([
        //     'status' => 'success',
        //     'message' => 'Email confirmed successfully',
        // ]);

        // Return Blade view for email confirmation success
        // return view('emails.confirmation-success');

        // // Flash success message to the session
        // session()->flash('success', 'Your account has been successfully confirmed.');

        // // Redirect to a specific page (e.g., home)
        // return redirect('http://localhost:3000/');

        // Return the Blade view for email confirmation success
        return view('confirmation-success');
    }


    public function __construct()
    {
        $this->middleware('cors', ['only' => ['signup']]);
    }
}
