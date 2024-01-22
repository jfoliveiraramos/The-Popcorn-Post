@extends('layouts.auth')

@section('authentication')
    <section class="w-4/5 md:w-3/5 xl:w-2/6 justify-self-center">
        <h2 class="font-semibold pl-3 text-3xl text-brown self-center text-start font-title">Link Google Account</h2>
        <form method="POST" action="{{ route('link.google') }}"
            class="bg-white rounded-lg px-10 py-5 text-sm flex flex-col">
            {{ csrf_field() }}
            <input hidden name="email" value="{{ $email }}">
            <input hidden name="google_id" value="{{ $google_id }}">
            <h3 class="font-medium text-lg text-brown text-start self-start font-title mb-5">
                You already have an account with this email.<br> 
                Please enter your password to link your Google account.
            </h3>

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
            <button type="submit"
                class="col-span-2 font-semibold rounded-lg px-10 py-2.5 mt-3 bg-red text-white justify-self-center self-center">
                Submit
            </button>
        </form>
    </section>
@endsection