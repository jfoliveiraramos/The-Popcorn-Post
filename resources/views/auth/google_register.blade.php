@extends('layouts.auth')

@section('authentication')
    <section class="w-4/5 md:w-3/5 2xl:w-2/6 justify-self-center flex flex-col">
        <h2 class="font-semibold pl-3 text-2xl sm:text-4xl text-brown self-start text-start font-title">Register with Google Account
        </h2>
        <form method="POST" action="{{ route('register.google') }}"
            class="flex bg-white rounded-lg py-5 flex-col gap-x-4 text-sm items-center">
            {{ csrf_field() }}
            <input hidden name="email" value="{{ $email }}">
            <input hidden name="google_id" value="{{ $google_id }}">
            <div class="overflow-y-scroll md:overflow-auto px-2 sm:px-10 h-44 lg:h-full w-full gap-x-4">
                <div class="flex flex-row flex-wrap md:flex-nowrap justify-between gap-x-4">
                    <div class="field w-full">
                        <label for="firstName" class="required">First Name</label>
                        <input id="firstName" type="text" name="firstName"
                            value="{{ old('firstName') ? old('firstName') : $firstName }}" required autofocus>
                        @if ($errors->has('firstName'))
                            <span class="error">
                                <i class="bi bi-x-circle text-xxs"></i>
                                {{ $errors->first('firstName') }}
                            </span>
                        @endif
                        </div>
                    <div class="field w-full">
                        <label for="lastName" class="required">Last Name</label>
                        <input id="lastName" type="text" name="lastName"
                            value="{{ old('lastName') ? old('lastName') : $lastName }}" required>
                        @if ($errors->has('lastName'))
                            <span class="error">
                                <i class="bi bi-x-circle text-xxs"></i>
                                {{ $errors->first('lastName') }}
                            </span>
                        @endif
                        </div>
                </div>
                <div class="field col-span-2">
                    <label for="username" class="required">Username</label>
                    <input id="username" type="text" name="username" value="{{ old('username') }}" required autofocus>
                    @if ($errors->has('username'))
                        <span class="error">
                            <i class="bi bi-x-circle text-xxs"></i>
                            {{ $errors->first('username') }}
                        </span>
                    @endif
                    </div>
                <div class="field col-span-2">
                    <label for="password" class="required">Password</label>
                    <input id="password" type="password" name="password" required>
                    @if ($errors->has('password'))
                        <span class="error">
                            <i class="bi bi-x-circle text-xxs"></i>
                            {{ $errors->first('password') }}
                        </span>
                    @endif
                    </div>
                <div class="field col-span-2">
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
            <button type="submit"
                class="col-span-2 font-semibold rounded-lg px-10 py-2.5 mt-3 bg-red text-white justify-self-center self-center">
                Submit
            </button>
        </form>
    </section>
@endsection
