@extends('layouts.auth')

@section('authentication')
    <section class="w-4/5 md:w-3/5 2xl:w-2/5 justify-self-center flex flex-col row-start-3  row-span-3">
        <h2 class="font-semibold pl-3 text-2xl sm:text-4xl text-brown self-start text-start font-title">Register
        </h2>
        <form method="POST" action="{{ route('register') }}" class="flex bg-white rounded-lg py-5 flex-col gap-x-4 text-sm">
            {{ csrf_field() }}
            <div class="overflow-y-scroll md:overflow-auto px-5 sm:px-10 h-44 md:h-full">
                <div class="flex flex-col md:flex-row [&>*]:w-full justify-between md:gap-5">
                    <div class="field">
                        <label for="firstName" class="required">First Name</label>
                        <input id="firstName" type="text" name="firstName" value="{{ old('firstName') }}" required
                            autofocus>
                        @if ($errors->has('firstName'))
                            <span class="error">
                                <i class="bi bi-x-circle text-xxs"></i>
                                {{ $errors->first('firstName') }}
                            </span>
                        @endif
                    </div>
                    <div class="field">
                        <label for="lastName" class="required">Last Name</label>
                        <input id="lastName" type="text" name="lastName" value="{{ old('lastName') }}" required>
                        @if ($errors->has('lastName'))
                            <span class="error">
                                <i class="bi bi-x-circle text-xxs"></i>
                                {{ $errors->first('lastName') }}
                            </span>
                        @endif
                        </div>
                </div>
                <div class="flex flex-col md:flex-row [&>*]:w-full justify-between md:gap-5">
                    <div class="field">
                        <label for="username" class="required">Username</label>
                        <input id="username" type="text" name="username" value="{{ old('username') }}" required
                            >
                        @if ($errors->has('username'))
                            <span class="error">
                                <i class="bi bi-x-circle text-xxs"></i>
                                {{ $errors->first('username') }}
                            </span>
                        @endif
                        </div>

                    <div class="field">
                        <label for="email" class="required">E-Mail Address</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required>
                        @if ($errors->has('email'))
                            <span class="error">
                                <i class="bi bi-x-circle text-xxs"></i>
                                {{ $errors->first('email') }}
                            </span>
                        @endif
                        </div>
                </div>
                <div class="flex flex-col md:flex-row [&>*]:w-full justify-between md:gap-5">
                    <div class="field">
                        <label for="password" class="required">Password</label>
                        <input id="password" type="password" name="password" required>
                        @if ($errors->has('password'))
                            <span class="error">
                                <i class="bi bi-x-circle text-xxs"></i>
                                {{ $errors->first('password') }}
                            </span>
                        @endif
                        </div>
                    <div class="field">
                        <label for="confirmPassword" class="required">Confirm Password</label>
                        <input id="confirmPassword" type="password" name="confirmPassword" required>
                        @if ($errors->has('confirmPassword'))
                            <span class="error">
                                <i class="bi bi-x-circle text-xxs"></i>
                                {{ $errors->has('confirmPassword') ? $errors->first('confirmPassword') : '' }}
                            </span>
                        @endif
                        </div>
                </div>
            </div>
            <div class="flex flex-col mt-4 col-span-2 justify-around">
                <button type="submit"
                    class="font-semibold rounded-lg px-10 py-2.5 mb-3 inline-block bg-red text-white w-fit self-center">
                    Register
                </button>
                <p class="text-black font-normal text-center">Have an account? 
                    <a class="text-red font-semibold text-center" href="{{ route('login') }}">
                        Sign in
                    </a>
                </p>
            </div>
        </form>
        <a href="{{ route('login.google') }}"
            class="flex flex-row bg-gold text-white font w-fit self-center py-2 px-4 mt-3 rounded items-center text-sm text-gray-600 hover:text-gray-900 text-center focus:text-gray-900">
            <i class="bi bi-google mr-2" aria-hidden="true"></i>
            Login with <strong class="indent-1">Google</strong>
        </a>
    </section>
@endsection
