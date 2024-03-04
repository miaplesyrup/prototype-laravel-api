<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Session;
use Illuminate\Foundation\Auth\VerifiesEmails;

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    use VerifiesEmails;

    /**
     * Where to redirect users after verification.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    public function verify(Request $request)
    {
        $user = User::findOrFail($request->route('id'));

        if (!$user->hasVerifiedEmail() && $user->confirmation_token === $request->route('token')) {
            $user->markEmailAsVerified();
            $user->update(['status' => 'active']);

            //Add a session flash message
            Session::flash('success', 'Your account has been verified!');

            // event(new Verified($user));

            // Redirect to the React frontend login page

            return redirect('http://localhost:3000/');
        }

        // Return a JSON response indicating the verification status
        // return response()->json(['status' => 'success', 'message' => 'Your account has been verified!']);

        // return redirect('http://localhost:3000/')->with('verified', true);

        // Return a JSON response for an invalid verification link (optional)
        return response()->json(['status' => 'error', 'message' => 'Invalid verification link.'], 400);
        }
}
