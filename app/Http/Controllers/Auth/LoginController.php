<?php
 
namespace App\Http\Controllers\Auth;

use App\Models\Member;
use Illuminate\Http\Request;

use App\Mail\PasswordRecovery;
use App\Models\PasswordResetToken;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        } else {
            return view('auth.login', [
                    'title' => 'Login - The Popcorn Post'
                ]);
        }
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
 
        if (Auth::attempt($credentials, $request->remember)) {

            if (Auth::user()->is_deleted) {
                Auth::logout();
                abort(403, 'This action is unauthorized');
            }

            $request->session()->regenerate();
 
            return redirect()->route('home')
                ->withSuccess('You have successfully logged in!');
        }
 
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')
            ->withSuccess('You have logged out successfully!');
    } 

    public function recoverPassword(Request $request)
    {

        $request->validate([
            'recovery_email' => ['required', 'email'],
        ]);

        $member = Member::where('email', $request->recovery_email)->first();

        if (!$member) {
            return back()->withErrors([
                'recovery_email' => 'The email provided for password reset does not match our records.',
            ]);
        }

        $token = uniqid(time() . $member->id, true);
        PasswordResetToken::create([
            'email' => $request->recovery_email,
            'token' => $token,
        ]);

        Mail::to($request->recovery_email)->send(new PasswordRecovery($member->name(), $token));
        return redirect()->route('login')->withSuccess('A password reset link has been sent to your email address!');
    }

    public function showPasswordResetForm($token)
    {
        try {
            $passwordResetToken = PasswordResetToken::where('token', $token)->firstOrFail();
        } catch (\Throwable) {
            return redirect()->route('login')->withErrors([
                'password_reset' => 'This password reset link does not match our records.',
            ])->onlyInput('token');
        }

        $time =  $passwordResetToken->created_at;
        $time = strtotime('+30 minutes', strtotime($time));
       
        if (time() > $time) {
            return redirect()->route('login')->withErrors([
                'password_reset' => 'This password reset link has expired.',
            ])->onlyInput('token');
        }

        if ($passwordResetToken->used) {
            return redirect()->route('login')->withErrors([
                'password_reset' => 'This password reset link has already been used.',
            ])->onlyInput('token');
        }

        return view('auth.reset_password', [
            'token' => $token,
            'title' => 'Reset Password'
        ]);
    }

    public function resetPassword(Request $request, $token)
    {
        try {
            $passwordResetToken = PasswordResetToken::where('token', $token)->firstOrFail();
        } catch (\Throwable) {
            return redirect()->route('login')->withErrors([
                'password_reset' => 'This password reset link does not match our records.',
            ])->onlyInput('token');
        }

        $request->validate([
            'password' => 'required|min:8|string',
            'confirmPassword' => 'required|same:password'
        ]);

        $password = $request->password;

        if (!preg_match('/[a-z]/', $password)) {
            return redirect()->back()->withInput()->withErrors([
                'password' => 'Password must contain at least one lowercase letter.'
            ]);
        }

        if (!preg_match('/[A-Z]/', $password)) {
            return redirect()->back()->withInput()->withErrors([
                'password' => 'Password must contain at least one uppercase letter.'
            ]);
        }

        if (!preg_match('/[0-9]/', $password)) {
            return redirect()->back()->withInput()->withErrors([
                'password' => 'Password must contain at least one digit.'
            ]);
        }

        if (!preg_match('/[\'^£$%&*()}{@#~!?><>,|=_+¬-]/', $password)) {
            return redirect()->back()->withInput()->withErrors([
                'password' => 'Password must contain at least one special character.'
            ]);
        }

        $member = Member::where('email', $passwordResetToken->email)->first();

        if (!$member) {
            return redirect()->route('login')->withErrors([
                'password_reset' => 'This password reset link does not match our records.',
            ])->onlyInput('token');
        }

        $member->password = $request->password;
        $member->save();

        $passwordResetToken->used = true;
        $passwordResetToken->save();

        return redirect()->route('login')->withSuccess('Your password has been reset successfully!');

    }
}
