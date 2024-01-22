@extends('layouts.auth')

@section('authentication')
    <section class="w-4/5 md:w-3/5 2xl:w-2/5 justify-self-center">
        <h2 class="font-semibold pl-3 text-2xl sm:text-4xl text-brown self-center text-start font-title">Reset Password</h2>
        <form method="POST" action="{{ route('reset.password', $token) }}"
            class="bg-white rounded-lg px-5 sm:px-10 py-5 text-sm flex flex-col items-center">
            {{ csrf_field() }}
            <div class="field w-full">
                <label for="password" class="required">Password</label>
                <input id="password" type="password" name="password" required>
                @if ($errors->has('password'))
                    <span class="error">
                        <i class="bi bi-x-circle text-xxs"></i>
                        {{ $errors->first('password') }}
                    </span>
                @endif
                </div>
            <div class="field w-full">
                <label for="confirmPassword" class="required">Confirm Password</label>
                <input id="confirmPassword" type="password" name="confirmPassword" required>
                @if ($errors->has('confirmPassword'))
                    <span class="error">
                        <i class="bi bi-x-circle text-xxs"></i>
                        {{ $errors->has('confirmPassword') ? $errors->first('confirmPassword') : '' }}
                    </span>
                @endif
                </div>
            <button type="submit"
                class="font-semibold rounded-lg px-10 py-2.5 mt-3 bg-red text-white justify-self-center self-center"  onclick="disableSubmit(this)">
                Reset Password
            </button>
        </form>
    </section>
@endsection
