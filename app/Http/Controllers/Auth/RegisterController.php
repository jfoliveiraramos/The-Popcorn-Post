<?php

namespace App\Http\Controllers\Auth;

use App\Models\Member;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }

        return view('auth.register', [
            'title' => 'Register - The Popcorn Post'
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'firstName' => 'required|alpha|max:250',
            'lastName' => 'required|alpha|max:250',
            'username' => 'required|string|max:250|unique:member',
            'email' => 'required|email|max:250|unique:member',
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

        Member::create([
            'first_name' => $request->firstName,
            'last_name' => $request->lastName,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        $credentials = $request->only('email', 'password');
        Auth::attempt($credentials);
        $request->session()->regenerate();
        return redirect()->route('home')
            ->withSuccess('You have successfully registered & logged in!');
    }
}
