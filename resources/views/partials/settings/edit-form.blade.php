<div id='edit-settings' class='edit-settings'>
    <form method='POST' action='/members/{{ $member->username }}/settings'
        class="bg-white rounded-xl shadow-std p-5 flex flex-col">
        {{ method_field('patch') }}
        {{ csrf_field() }}
        <div class='edit-name flex flex-col lg:flex-row lg:flex-wrap items-center gap-3'>
            <div class="field w-2/3 lg:w-auto">
                <label for='first_name'>First Name</label>
                <input type='text' name='first_name' id='first_name'
                    value='{{ old('first_name', $member->first_name) }}'>
                @if ($errors->has('first_name'))
                    <span class="error">
                        <i class="bi bi-x-circle text-xxs"></i>
                        {{ $errors->first('first_name') }}
                    </span>
                @endif
            </div>
            <div class="field w-2/3 lg:w-auto">
                <label for='last_name'>Last Name</label>
                <input type='text' name='last_name' id='last_name'
                    value='{{ old('last_name', $member->last_name) }}'>
                @if ($errors->has('last_name'))
                    <span class="error">
                        <i class="bi bi-x-circle text-xxs"></i>
                        {{ $errors->first('last_name') }}
                    </span>
                @endif
            </div>
        </div>
        <div class='edit-username-email flex flex-col lg:flex-row lg:flex-wrap items-center gap-3'>
            <div class="field w-2/3 lg:w-auto">
                <label for='username'>Username</label>
                <input type='text' name='username' id='username' value='{{ old('username', $member->username) }}'>
                @if ($errors->has('username'))
                    <span class="error">
                        <i class="bi bi-x-circle text-xxs"></i>
                        {{ $errors->first('username') }}
                    </span>
                @endif
            </div>
            <div class="field w-2/3 lg:w-auto">
                <label for='email'>Email</label>
                <input type='text' name='email' id='email' value='{{ old('email', $member->email) }}'>
                @if ($errors->has('email'))
                    <span class="error">
                        <i class="bi bi-x-circle text-xxs"></i>
                        {{ $errors->first('email') }}
                    </span>
                @endif
            </div>
        </div>
        <div class='edit-password flex flex-col lg:flex-row lg:flex-wrap items-center gap-3'>
            <div class="field w-2/3 lg:w-auto">

                <label for='old_password'>Old Password</label>
                <input type='password' name='old_password' id='old_password'>
                @if ($errors->has('old_password'))
                    <span class="error">
                        <i class="bi bi-x-circle text-xxs"></i>
                        {{ $errors->first('old_password') }}
                    </span>
                @endif
            </div>

            <div class="field w-2/3 lg:w-auto">
                <label for='new_password'>New Password</label>
                <input type='password' name='new_password' id='new_password'>
                @if ($errors->has('new_password'))
                    <span class="error">
                        <i class="bi bi-x-circle text-xxs"></i>
                        {{ $errors->first('new_password') }}
                    </span>
                @endif
            </div>

            <div class="field w-2/3 lg:w-auto">
                <label for='confirm_new_password'>Confirm New Password</label>
                <input type='password' name='confirm_new_password' id='confirm_new_password'>
                @if ($errors->has('confirm_new_password'))
                    <span class="error">
                        <i class="bi bi-x-circle text-xxs"></i>
                        {{ $errors->first('confirm_new_password') }}
                    </span>
                @endif
            </div>
        </div>
        <button type='submit' class="self-center bg-red text-white rounded py-2 px-3 mt-5">Save Changes</button>

    </form>
</div>
