<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\VerifiesEmails;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\OTPVerificationController;
use App\Mail\VerificationCodeMail;
use Illuminate\Support\Facades\Mail;

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
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

   

 public function verify($token)
    {
        $user = User::where('verification_token', $token)->first();

        if (!$user) {
            return redirect('/')->with('error', 'Invalid or expired token.');
        }

        $user->is_verified = true;
        $user->email_verified_at = now();
        $user->verification_token = null;
        $user->save();

        auth()->login($user);

        return redirect('/')->with('success', 'Email verified successfully! You are now logged in.');
    }

public function sendVerificationCode(Request $request)
{
    $userEmail = $request->email;

    // 6 digit random code
    $code = rand(100000, 999999);

    // ডাটাবেসে সংরক্ষণ করতে চাইলে
    \DB::table('verification_codes')->updateOrInsert(
        ['email' => $userEmail],
        ['code' => $code, 'created_at' => now()]
    );

    // মেইল পাঠাও
    Mail::to($userEmail)->send(new VerificationCodeMail($code));

    return response()->json(['message' => 'Verification code sent!']);
}
public function verification_confirmation($code)
{
    $record = \DB::table('verification_codes')->where('code', $code)->first();
    if(!$record) return back()->with('error', 'Invalid code.');

    $user = User::where('email', $record->email)->first();
    if($user) {
        $user->is_verified = true;
        $user->email_verified_at = now();
        $user->save();
        auth()->login($user);
    }

    return redirect('/')->with('success', 'Email verified successfully!');
}




    // public function show(Request $request)
    // {
    //     if ($request->user()->email != null) {
    //         return $request->user()->hasVerifiedEmail()
    //                         ? redirect($this->redirectPath())
    //                         : view('auth.'.get_setting('authentication_layout_select').'.verify_email');
    //     }
    //     else {
    //         $otpController = new OTPVerificationController;
    //         $otpController->send_code($request->user());
    //         return redirect()->route('verification');
    //     }
    // }

    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect($this->redirectPath());
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('resent', true);
    }

    // public function verification_confirmation($code){
    //     $user = User::where('verification_code', $code)->first();
    //     if($user != null){
    //         $user->email_verified_at = Carbon::now();
    //         $user->save();
    //         auth()->login($user, true);
    //         offerUserWelcomeCoupon();
    //         flash(translate('Your email has been verified successfully'))->success();
    //     }
    //     else {
    //         flash(translate('Sorry, we could not verifiy you. Please try again'))->error();
    //     }

    //     if($user->user_type == 'seller') {
    //         return redirect()->route('seller.dashboard');
    //     }

    //     return redirect()->route('dashboard');
    // }
}
