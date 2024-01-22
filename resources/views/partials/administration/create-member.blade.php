<div id='create-member' class='create-member bg-white rounded-lg px-3 sm:px-10 py-5'>
    <form method="POST" action="/administration/create_member" class="flex flex-col items-center gap-x-4 text-sm">
        {{ csrf_field() }}
        <div class="flex flex-col md:flex-row w-full justify-between md:gap-5 lg:gap-16 max-w-sm md:max-w-none [&>*]:w-full">
            <div class="field">
                <label for="firstName" class="required">First</label>
                <input id="firstName" type="text" name="firstName" value="{{ old('firstName') }}" required autofocus>
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
        <div class="flex flex-col md:flex-row w-full justify-between md:gap-5 lg:gap-16 max-w-sm md:max-w-none [&>*]:w-full">
            <div class="field">
                <label for="username" class="required">Username</label>
                <input id="username" type="text" name="username" value="{{ old('username') }}" required>
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
        <div class="flex flex-col md:flex-row w-full justify-between x/cmd:gap-5 lg:gap-16 max-w-sm md:max-w-none [&>*]:w-full">
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
                        {{ $errors->first('confirmPassword') }}
                    </span>
                @endif
                </div>
        </div>
        <button type="submit"
            class="col-span-2 place-self-center mt-4 bg-red text-white text-base px-5 py-1 rounded-lg" onclick="disableSubmit(this)">Create
            Account</button>
    </form>
</div>
