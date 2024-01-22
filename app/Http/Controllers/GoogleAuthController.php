<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Request;

class GoogleAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            $authingMember = Socialite::driver('google')->stateless()->user();
        } catch (Exception) {
            return redirect()->route('login');
        }
        $member = Member::where('google_id', $authingMember->id)->first();

        if($member){
            if ($member->is_deleted)
            {
                abort(403, 'This action is unauthorized');
            }
            Auth::login($member);
            return redirect()->route('home');
        }else{

            $member = Member::where('email', $authingMember->email)->first();

            if($member){

                session([
                    'google_auth_data' => [
                        'email' => $authingMember->email,
                        'google_id' => $authingMember->id,
                    ]
                ]);

                return redirect()->route('link.google');
            }

            if (array_key_exists('given_name', $authingMember->user)) {
                $firstName = $authingMember->user['given_name'];   
            } else {
                $firstName = null;
            }

            if ( array_key_exists('family_name', $authingMember->user)) {
                $lastName = $authingMember->user['family_name'];   
            } else {
                $lastName = null;
            }

            session([
                'google_auth_data' => [
                    'firstName' => $firstName,
                    'lastName' => $lastName,
                    'email' => $authingMember->email,
                    'google_id' => $authingMember->id,
                ]
            ]);

            return redirect()->route('register.google');      
        }
    }

    public function showRegistrationForm()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }

        $googleAuthData = session('google_auth_data');

        if ($googleAuthData == null) {
            return redirect()->route('login');
        }

        return view('auth.google_register', [
            'firstName' => $googleAuthData['firstName'],
            'lastName' => $googleAuthData['lastName'],
            'email' => $googleAuthData['email'],
            'google_id' => $googleAuthData['google_id'],
            'title' => 'Register with Google - The Popcorn Post'
        ]);
    }

    public function register(Request $request) {

        
        $request->validate([
            'username' => 'required|string|max:250|unique:member',
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
            'google_id' => $request->google_id,
            'password' => Hash::make($request->password)
        ]);

        session()->forget('google_auth_data');

        $credentials = $request->only('email', 'password');
        Auth::attempt($credentials);
        $request->session()->regenerate();
        
        return redirect()->route('home');
    }

    public function showLinkForm(){

        if (Auth::check()) {
            return redirect()->route('home');
        }

        if (session('google_auth_data') == null) {
            return redirect()->route('login');
        }

        $google_id = session('google_auth_data')['google_id'];
        $email = session('google_auth_data')['email'];

        if ($google_id == null || $email == null) {
            return redirect()->route('login');
        }

        return view('auth.link_google', [
            'google_id' => $google_id,
            'email' => $email,
            'title' => 'Link Google Account - The Popcorn Post'
        ]);
    }

    public function link(Request $request) {

        $request->validate([
            'email' => 'required|email',
            'google_id' => 'required',
            'password' => 'required'
        ]);


        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect()->back()->withInput()->withErrors([
                'password' => 'Incorrect password.'
            ]);
        }

        $member = Member::where('email', $request->email)->first();
        $member->google_id = $request->google_id;
        $member->save();

        session()->forget('google_auth_data');

        return redirect()->route('home');
    }
}
