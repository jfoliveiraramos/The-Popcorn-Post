@php $profileImage = $member->profile_image @endphp
<div id='profile-card' class='profile-card bg-white text-black px-4 sm:px-8 py-4 rounded-xl mb-5'>
    <div class='profile-main flex flex-wrap justify-between gap-3 mb-3 items-start'>
        <div class='profile-main flex flex-wrap sm:flex-nowrap gap-3'>
            <div class="relative">
                <img src='/images/profile/{{ $profileImage->file_name }}'
                    class='profile-image rounded-full w-24 h-24 sm:w-28 sm:h-28 object-cover' alt='Profile Image'>
                @if (Auth::check() && $member->canEditProfile())
                    <a href='/members/{{ $member->username }}/edit'
                        class='absolute bottom-0 end-0 edit-profile-button text-lg bg-red text-white rounded-full px-2 py-1'>
                        <div class="group relative">
                            <i class="bi bi-pencil-square" aria-hidden="true"></i>
                            <span
                                class="hidden top-full z-50 text-white bg-black opacity-90 rounded p-2 text-xs font-main font-thin group-focus:block group-hover:block absolute left-0 text-left  whitespace-nowrap">
                                Edit Profile
                            </span>
                        </div>
                    </a>
                @endif
            </div>
            <div class="flex flex-col justify-between h-max w-fit">
                <div class="mb-4">
                    <div class="flex flex-row items-center gap-1">
                        <p class="font-title font-bold text-xl sm:text-3xl relative whitespace-nowrap">
                            {{ $member->name() }}
                        </p>
                        @if ($member->is_admin)
                            <span class="group relative">
                                <i class="fa-solid fa-user-tie text-blue text-base align-top ml-1"
                                    aria-hidden="true"></i>
                                <span
                                    class="hidden top-full z-50 text-white bg-black opacity-90 rounded p-2 text-xs w-fit font-main font-thin group-focus:block group-hover:block absolute whitespace-nowrap">
                                    Administrator
                                </span>
                            </span>
                        @endif

                        @if ($member->is_blocked)
                            <span class="group relative">
                                <i class="fa-solid fa-lock text-brown text-base align-top ml-1" aria-hidden="true"></i>
                                <span
                                    class="hidden top-full z-50 text-white bg-black opacity-90 rounded p-2 text-xs w-fit font-main font-thin group-focus:block group-hover:block absolute whitespace-nowrap">
                                    Blocked Member
                                </span>
                            </span>
                        @endif
                    </div>

                    <h3 class="leading-3 text-brown font-medium">{{ '@' . $member->username }}</h3>
                </div>
                @if (Auth::check() && Auth::user()->id !== $member->id && !Auth::user()->isFollowing($member))
                    <form method='POST' action='/api/members/{{ $member->username }}/follow'>
                        {{ csrf_field() }}
                        <button type='submit'
                            class="follow-button bg-red text-white text-sm rounded-md px-2 py-1 flex flex-row gap-1">
                            <i class="bi bi-person-fill-add" aria-hidden="true"></i>
                            Follow
                        </button>
                    </form>
                @elseif (Auth::check() && Auth::user()->id !== $member->id && Auth::user()->isFollowing($member))
                    <form method='POST' action='/api/members/{{ $member->username }}/follow'>
                        {{ csrf_field() }}
                        {{ method_field('delete') }}
                        <button type='submit'
                            class="unfollow-button bg-white text-red  border border-red text-sm rounded-md px-2 py-1 flex flex-row gap-1">
                            <i class="bi bi-person-fill-check" aria-hidden="true"></i>
                            Following
                        </button>
                    </form>
                @endif
            </div>
        </div>
        <div class="flex flex-row text-center gap-2 -mr-2">
            @if (Auth::user()->is_admin && !$member->is_admin)
                @if (!$member->is_blocked)
                    <div class="group relative">
                        <button id='promote-member-button'
                            class='promote-member-button bg-gold text-white rounded py-1 px-2 block relative'>
                            <i class="fa-solid fa-user-tie"></i>
                            <span class="sr-only">
                                Promote To Admin
                            </span>
                        </button>
                        <span
                            class="hidden top-full z-50 text-white bg-black opacity-90 rounded p-2 text-xs font-main font-thin group-focus:block group-hover:block absolute left-0 sm:left-auto text-left whitespace-nowrap">Promote
                            To
                            Admin</span>
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
                                        @if ($errors->has('password_match' . $member->id))
                                            <span class="error">
                                                <i class="bi bi-x-circle text-xxs"></i>
                                                {{ $errors->first('password_match' . $member->id) }}
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
                                        @if ($errors->has('confirm_match' . $member->id))
                                            <span class="error">
                                                <i class="bi bi-x-circle text-xxs"></i>
                                                {{ $errors->first('confirm_match' . $member->id) }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex flex-row justify-between">
                                    <button type='submit'
                                        class="bg-red text-white rounded-md px-2 py-1">Confirm</button>
                                    <button type="button"
                                        class="cancel-promote-member bg-white text-red border border-red rounded-md px-2 py-1">Cancel</button>
                                </div>
                            </form>
                            <script>
                                @if (
                                    $errors->has('password' . $member->id) ||
                                        $errors->has('password_match' . $member->id) ||
                                        $errors->has('confirm_password' . $member->id) ||
                                        $errors->has('confirm_match' . $member->id))
                                    promoteMemberDialogId = 'promote-member-dialog-';
                                    promoteMemberDialogId += {{ $member->id }};
                                    document.getElementById(promoteMemberDialogId).showModal();
                                @endif
                            </script>
                        </dialog>
                    </div>

                @endif
                <div class="group relative h-min">
                    <a href='/members/{{ $member->username }}/settings'
                        class='edit-settings-button text-base bg-red text-white rounded-lg px-2 py-1 block'>
                        <i class="bi bi-gear-fill" aria-hidden="true"></i>
                        <span class="sr-only">
                            Edit Settings
                        </span>
                    </a>
                    <span
                        class="hidden top-full z-50 text-white bg-black opacity-90 rounded p-2 text-xs font-main font-thin group-focus:block group-hover:block absolute left-0 sm:right-0 sm:left-auto  text-left whitespace-nowrap ">
                        Edit Settings
                    </span>
                </div>
                @if ($member->is_blocked)
                    <div class="group relative h-min">
                        <button class='unblock-member-button text-base bg-red text-white rounded-lg px-2 py-1'>
                            <i class="bi bi-unlock-fill" aria-hidden="true"></i>
                            <span class="sr-only">
                                Unblock Account
                            </span>
                        </button>
                        <span
                            class="hidden top-full z-50 text-white bg-black opacity-90 rounded p-2 text-xs font-main font-thin group-focus:block group-hover:block absolute left-0 sm:right-0 sm:left-auto  text-left whitespace-nowrap">
                            Unblock Account
                        </span>
                        <dialog
                            class="unblock-member-dialog bg-white rounded-2xl px-10 py-5 w-4/5 lg:w-1/5 text-center">
                            <h2 class="mb-9 "> Are you sure you want to <strong>unblock</strong> this member's account?
                            </h2>
                            <form method='POST' action='/members/{{ $member->username }}/unblock'>
                                {{ method_field('patch') }}
                                {{ csrf_field() }}
                                <div class="flex flex-row justify-between">
                                    <button type='submit'
                                        class="bg-red text-white rounded-md px-2 py-1">Confirm</button>
                                    <button type="button"
                                        class="cancel-unblock-member bg-white text-red border border-red rounded-md px-2 py-1">Cancel</button>
                                </div>
                            </form>
                        </dialog>
                    </div>
                @else
                    <div class="group relative h-min">
                        <button class='block-member-button text-base bg-red text-white rounded-lg px-2 py-1'>
                            <i class="bi bi-lock-fill" aria-hidden="true"></i>
                            <span class="sr-only">
                                Block Account
                            </span>
                        </button>
                        <span
                            class="hidden top-full z-50 text-white bg-black opacity-90 rounded p-2 text-xs font-main font-thin group-focus:block group-hover:block absolute left-0 sm:right-0 sm:left-auto  text-left whitespace-nowrap">
                            Block Account
                        </span>
                        <dialog class="block-member-dialog bg-white rounded-2xl px-10 py-5 w-4/5 lg:w-1/5 text-center">
                            <h2 class="mb-9 "> Are you sure you want to <strong>block</strong> this member's account?
                            </h2>
                            <form method='POST' action='/members/{{ $member->username }}/block'>
                                {{ method_field('patch') }}
                                {{ csrf_field() }}
                                <div class="flex flex-row justify-between">
                                    <button type='submit'
                                        class="bg-red text-white rounded-md px-2 py-1">Confirm</button>
                                    <button type="button"
                                        class="cancel-block-member bg-white text-red border border-red rounded-md px-2 py-1">Cancel</button>
                                </div>
                            </form>
                        </dialog>
                    </div>
                @endif

                <div class="group relative h-min">
                    <button class='delete-member-button text-base bg-red text-white rounded-lg px-2 py-1'
                        id='delete-member-button'>
                        <i class="bi bi-trash-fill" aria-hidden="true"></i>
                        <span class="sr-only">
                            Delete Account
                        </span>
                    </button>
                    <span
                        class="hidden top-full z-50 text-white bg-black opacity-90 rounded p-2 text-xs font-main font-thin group-focus:block group-hover:block absolute right-0 whitespace-nowrap">
                        Delete Account
                    </span>
                    <dialog
                        id='delete-member-dialog'class="delete-member-dialog bg-white rounded-2xl px-10 py-5 w-4/5 sm:w-2/5 2xl:w-1/5 text-center">
                        <h2 class="mb-9"> Are you sure you want to <strong class="text-red">delete</strong> this
                            member's
                            account? </h2>
                        <form method='POST' action='/members/{{ $member->username }}'>
                            {{ method_field('delete') }}
                            {{ csrf_field() }}
                            <div class="flex flex-row justify-between">
                                <button type='submit' class="bg-red text-white rounded-md px-2 py-1">Confirm</button>
                                <button type="button"
                                    class="cancel-delete-member bg-white text-red border border-red rounded-md px-2 py-1"
                                    id='cancel-delete-member'>Cancel</button>
                            </div>
                        </form>
                    </dialog>
                </div>
            @endif
        </div>
    </div>
    <div class="extra-info">
        <h2 class="mb-2 items-center flex flex-row">
            <strong class="font-medium mr-1">Academy Score: </strong>
            <p class="font-semibold text-brown mr-1">{{ $member->academy_score }}
            </p>
            <img src="/images/logo.png" class="w-5 h-5 mr-1" alt="Academy Logo">
        </h2>
        <div class='profile-card-buttons flex flex-wrap gap-5 mb-5'>
            <h3 class="follow flex flex-row gap-1  hover:cursor-pointer">
                <p class="text-brown font-semibold">{{ $member->following->count() }}</p> Following
            </h3>
            <dialog class='follow-dialog py-5 px-2 rounded w-4/6 md:w-2/6 2xl:w-1/6' id ='follow-dialog'>
                @php $followingList = $member->following @endphp
                <div class="flex  flex-col items-center">
                    @if (count($followingList) == 0)
                        <p class="text-brown">Not following anyone</p>
                    @endif
                    <ul class="flex flex-col mb-4 max-h-56 overflow-y-scroll px-3 w-full">
                        @foreach ($followingList as $following)
                            @include('partials.profile.follow-card', ['follow' => $following])
                        @endforeach
                    </ul>
                    <button type="button"
                        class="close-follow-dialog border border-brown rounded px-2 py-1 text-brown"
                        id="close-follow-dialog">Close</button>
            </dialog>
            <h3 class="follow flex flex-row gap-1  hover:cursor-pointer">
                <p class="text-brown font-semibold">{{ $member->followers->count() }}</p> Followers
            </h3>
            <dialog class='follow-dialog py-5 px-2 rounded w-4/6 md:w-2/6 2xl:w-1/6' id ='follow-dialog'>
                @php $followersList = $member->followers @endphp
                <div class="flex  flex-col items-center">
                    @if (count($followersList) == 0)
                        <p class="text-brown">No followers</p>
                    @endif
                    <ul class="flex flex-col mb-4 max-h-56 overflow-y-scroll px-3 w-full">
                        @foreach ($followersList as $follower)
                            @include('partials.profile.follow-card', ['follow' => $follower])
                        @endforeach
                    </ul>
                    <button type="button"
                        class="close-follow-dialog border border-brown rounded px-2 py-1 text-brown"
                        id="close-follow-dialog">Close</button>
                </div>
            </dialog>
        </div>
        <div class='profile-card-text mb-4'>
            <h2 class="mb-2">{{ $member->biography }}</h2>
            <h3 class="text-sm font-light break-all">{{ $member->email }}</h3>
        </div>
    </div>
</div>
