@extends('layouts.auth')

@section('authentication')
    <section class="w-4/5 md:w-3/5 xl:w-2/6 justify-self-center flex flex-col">
        <h2 class="font-semibold pl-3 text-2xl sm:text-4xl text-brown self-start text-start font-title">Login
        </h2>
        <form method="POST" action="{{ route('login') }}"
            class="bg-white rounded-lg px-5 sm:px-10 py-5 flex flex-col justify-center text-sm">
            {{ csrf_field() }}
            <div class="field">
                <label for="email" class="required">E-mail</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
                @if ($errors->has('email'))
                    <span class="error">
                        <i class="bi bi-x-circle text-xxs"></i>
                        {{ $errors->first('email') }}
                    </span>
                @endif
            </div>
            <div class="field">
                <label for="password" class="required">Password</label>
                <input id="password" type="password" name="password" required>
                <p class="text-xs self-start mt-1 pl-1">Forgot your password? Click <button type="button"
                        id="recover_button" class="text-red">here</button></p>
                @if ($errors->has('password'))
                    <span class="error">
                        <i class="bi bi-x-circle text-xxs"></i>
                        {{ $errors->first('password') }}
                    </span>
                @endif
                </div>
            <label class="text-xs my-2">
                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
            </label>
            <div class="flex flex-col justify-between sm:mt-4">
                <button type="submit"
                    class="font-semibold rounded-lg px-10 py-2.5 mb-3 inline-block bg-red text-white w-fit self-center">
                    Sign in
                </button>
                <p class="text-black font-normal text-center">Don't have an account?
                    <a class="text-red font-semibold text-center" href="{{ route('register') }}">Register</a>
                </p>
                @if (session('success'))
                    <p class="font-title text-blue text-center text-sm italic mt-2 ">
                        {{ session('success') }}
                    </p>
                @endif
                @if ($errors->has('password_reset'))
                    <p class="font-title text-red text-center text-sm italic mt-2">
                        <i class="bi bi-x-circle text-xxs"></i>
                        {{ $errors->first('password_reset') }}
                    </p>
                @endif
            </div>
        </form>
        <dialog id="recover_dialog" class="bg-white rounded-lg px-8 py-6 w-3/4 sm:w-2/4 lg:w-1/4">
            <form method="POST" action="{{ route('recover.password') }}">
                {{ csrf_field() }}
                <h2 class="font-title font-bold text-xl text-brown mb-5"> Recover your password </h2>
                <div class="field">
                    <label for="recovery_email" class="required">Enter your email</label>
                    <input id="recovery_email" type="recovery_email" name="recovery_email" required>
                    @if ($errors->has('recovery_email'))
                    <span class="error" id="recovery_error">
                        <i class="bi bi-x-circle text-xxs"></i>
                        {{ $errors->first('recovery_email') }}
                    </span>
                @endif
                </div>
                <div class="flex flex-row justify-between text-base mt-5">
                    <button type="button" id="cancel_button" class="bg-white border border-red rounded text-red px-3 py-1">
                        Cancel </button>
                    <button type="submit" id="submit_button" class="bg-red rounded text-white px-3 py-1"
                        onclick="disableSubmit(this)"> Submit </button>
                </div>
            </form>
        </dialog>
        <a href="{{ route('login.google') }}"
            class="flex flex-row bg-gold text-white font w-fit self-center py-2 px-4 mt-2 rounded items-center text-sm text-gray-600 hover:text-gray-900 text-center focus:text-gray-900">
            <i class="bi bi-google mr-2" aria-hidden="true"></i>
            Login with<strong class="indent-1">Google</strong>
        </a>
    </section>
@endsection
