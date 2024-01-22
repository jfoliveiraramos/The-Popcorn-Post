<li
    class='admin-member bg-white rounded-xl text-black shadow-std mb-5 p-5 flex flex-row flex-wrap align-top justify-between gap-4'>
    @php $profileImage = $member->profile_image @endphp
    <div class='member-info'>
        <div class="flex flex-row flex-wrap gap-2 mb-3">
            <a href="/members/{{ $member->username }}">
                <img src='/images/profile/{{ $profileImage->file_name }}'
                    class='profile-image w-14 h-14 rounded-full object-cover' alt='Profile Image'>
            </a>
            <div>
                <h2 class="font-title font-bold text-xl relative whitespace-nowrap">
                    <a href="/members/{{ $member->username }}">{{ $member->name() }}</a>
                    @if ($member->is_admin)
                        <span class="group absolute">
                            <i class="fa-solid fa-user-tie text-blue text-base align-top ml-2" aria-hidden="true"></i>
                            <span
                                class="hidden top-full z-50 text-white bg-black opacity-90 rounded p-2 text-xs w-fit font-main font-thin group-focus:block group-hover:block absolute whitespace-nowrap">
                                Administrator
                            </span>
                        </span>
                    @endif
                    @if ($member->is_blocked)
                        <span class="group absolute">
                            <i class="fa-solid fa-lock text-brown text-base align-top ml-2" aria-hidden="true"></i>
                            <span
                                class="hidden top-full z-50 text-white bg-black opacity-90 rounded p-2 text-xs w-fit font-main font-thin group-focus:block group-hover:block absolute whitespace-nowrap">
                                Blocked Member
                            </span>
                        </span>
                    @endif
                </h2>
                <h3 class="leading-3 text-brown mt-0">
                    <a href="/members/{{ $member->username }}">{{ '@' . $member->username }}</a>
                </h3>
            </div>
        </div>
        <h2 class="mb-2 items-center flex flex-row">
            <strong class="font-medium mr-1">Academy Score: </strong>
            <p class="font-semibold text-brown mr-1">{{ $member->academy_score }}
            </p>
        </h2>
        <h3 class=" break-all">{{ $member->email }}</h3>
    </div>

    @if (!$member->is_admin)
        <div class='manage-member-actions flex flex-row flex-wrap h-min gap-2'>
            @if (!$member->is_blocked && !$member->is_admin)
            
                <div class="group relative">
                    <button
                        class='promote-member-button bg-gold text-white rounded py-1 px-2 block relative'>
                        <i class="fa-solid fa-user-tie" aria-hidden="true"></i>
                        <span class="sr-only">Promote To Admin</span>
                    </button>
                    <span
                        class="hidden top-full z-50 text-white bg-black opacity-90 rounded p-2 text-xs font-main font-thin group-focus:block group-hover:block absolute left-0 sm:left-auto text-left whitespace-nowrap">Promote
                        To
                        Admin
                    </span>
                    
                    <dialog id="promote-member-dialog-{{ $member->id }}"
                        class="promote-member-dialog bg-white rounded-2xl px-10 py-5 w-4/5 sm:3/5 md:w-2/5 xl:w-1/5 text-center">
                        <h2 class="mb-5 font-title font-semibold text-xl">Promote <strong
                                class="text-gold">{{ $member->name() }}</strong> to Administrator</h2>
                        <form method='POST' action='/members/{{ $member->username }}/promote'>
                            {{ method_field('patch') }}
                            {{ csrf_field() }}
                            <input type="hidden" name="is_admin" value="true">
                            <div class='confirm-password flex flex-col items-center gap-3 mb-3'>
                                <div class="field w-3/4 lg:w-auto">
                                    <label for='password.{{ $member->id }}'>Password</label>
                                    <input type='password' id='password.{{ $member->id }}' name='password'>
                                    @if ($errors->has('password' . $member->id))
                                        <span class="error">
                                            <i class="bi bi-x-circle text-xxs"></i>
                                            {{ $errors->first('password' . $member->id) }}
                                        </span>
                                    @endif
                                </div>
                                <div class="field w-3/4 lg:w-auto">
                                    <label for='confirm_password.{{ $member->id }}'>Confirm Password</label>
                                    <input type='password' id='confirm_password.{{ $member->id }}'
                                        name='confirm_password'>
                                    @if ($errors->has('confirm_password' . $member->id))
                                        <span class="error">
                                            <i class="bi bi-x-circle text-xxs"></i>
                                            {{ $errors->first('confirm_password' . $member->id) }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="flex flex-row justify-between">
                                <button type='submit' class="bg-red text-white rounded-md px-2 py-1">Confirm</button>
                                <button type="button"
                                    class="cancel-promote-member bg-white text-red border border-red rounded-md px-2 py-1">Cancel</button>
                            </div>
                        </form>
                    </dialog>
                </div>
            @endif
            <div class="group relative">
                <a href='/members/{{ $member->username }}/edit'
                    class='edit-profile-button bg-red text-white rounded py-1 px-2 block'>
                    <i class="bi bi-pen-fill" aria-hidden="true"></i>
                    <span
                        class="hidden top-full z-50 text-white bg-black opacity-90 rounded p-2 text-xs font-main font-thin group-focus:block group-hover:block absolute left-0 md:right-0 md:left-auto text-left whitespace-nowrap">Edit
                        Profile</span>
                </a>
            </div>
            <div class="group relative">
                <a href='/members/{{ $member->username }}/settings'
                    class='edit-settings-button bg-red text-white rounded py-1 px-2 block'>
                    <i class="bi bi-gear-fill" aria-hidden="true"></i>
                    <span
                        class="hidden top-full z-50 text-white bg-black opacity-90 rounded p-2 text-xs font-main font-thin group-focus:block group-hover:block absolute left-0 md:right-0 md:left-auto text-left whitespace-nowrap">Edit
                        Settings</span>
                </a>
            </div>
            @if ($member->is_blocked)
                <div class="group relative">
                    <button class='unblock-member-button bg-red text-white rounded py-1 px-2 '>
                        <i class="bi bi-unlock-fill" aria-hidden="true"></i>
                        <span class="sr-only">Unblock Account</span>
                    </button>
                    <span
                        class="hidden top-full z-50 text-white bg-black opacity-90 rounded p-2 text-xs font-main font-thin group-focus:block group-hover:block absolute left-0 md:right-0 md:left-auto text-left whitespace-nowrap">Unblock
                        Account</span>
                    <dialog
                        class="unblock-member-dialog bg-white rounded-2xl px-10 py-5 w-4/5 sm:3/5 md:w-2/5 xl:w-1/5 text-center">
                        <h2 class="mb-9 "> Are you sure you want to <strong>unblock</strong> this member's account?
                        </h2>
                        <form method='POST' action='/members/{{ $member->username }}/unblock'>
                            {{ method_field('patch') }}
                            {{ csrf_field() }}
                            <div class="flex flex-row justify-between">
                                <button type='submit' class="bg-red text-white rounded-md px-2 py-1">Confirm</button>
                                <button type="button"
                                    class="cancel-unblock-member bg-white text-red border border-red rounded-md px-2 py-1">Cancel</button>
                            </div>
                        </form>
                    </dialog>
                </div>
            @else
                <div class="group relative">
                    <button class='block-member-button bg-red text-white rounded py-1 px-2 '>
                        <i class="bi bi-lock-fill" aria-hidden="true"></i>
                        <span class="sr-only">Block Account</span>
                    </button>
                    <span
                        class="hidden top-full z-50 text-white bg-black opacity-90 rounded p-2 text-xs font-main font-thin group-focus:block group-hover:block absolute right-0 md:left-auto text-left whitespace-nowrap">Block
                        Account</span>
                    <dialog
                        class="block-member-dialog bg-white rounded-2xl px-10 py-5 w-4/5 sm:3/5 md:w-2/5 xl:w-1/5 text-center">
                        <h2 class="mb-9 "> Are you sure you want to <strong>block</strong> this member's account? </h2>
                        <form method='POST' action='/members/{{ $member->username }}/block'>
                            {{ method_field('patch') }}
                            {{ csrf_field() }}
                            <div class="flex flex-row justify-between">
                                <button type='submit' class="bg-red text-white rounded-md px-2 py-1">Confirm</button>
                                <button type="button"
                                    class="cancel-block-member bg-white text-red border border-red rounded-md px-2 py-1">Cancel</button>
                            </div>
                        </form>
                    </dialog>
                </div>
            @endif
            <div class="group relative">
                <button class='delete-member-button bg-red text-white rounded py-1 px-2 '>
                    <i class="bi bi-trash3-fill" aria-hidden="true"></i>
                    <span class="sr-only">Delete Account</span>
                </button>
                <span
                    class="hidden top-full z-50 text-white bg-black opacity-90 rounded p-2 text-xs font-main font-thin group-focus:block group-hover:block absolute right-0 md:left-auto text-left whitespace-nowrap">Delete
                    Account
                </span>
                <dialog
                    class="delete-member-dialog bg-white rounded-2xl px-10 py-5 w-4/5 sm:3/5 md:w-2/5 xl:w-1/5 text-center">
                    <h2 class="mb-9"> Are you sure you want to <strong class="text-red">delete</strong> this member's
                        account?
                    </h2>
                    <form method='POST' action='/members/{{ $member->username }}'>
                        {{ method_field('delete') }}
                        {{ csrf_field() }}
                        <div class="flex flex-row justify-between">
                            <button type='submit' class="bg-red text-white rounded-md px-2 py-1">Confirm</button>
                            <button type="button"
                                class="cancel-delete-member bg-white text-red border border-red rounded-md px-2 py-1">Cancel</button>
                        </div>
                    </form>
                </dialog>
            </div>

        </div>
    @endif
</li>
