<li id='list-appeal'
    class='admin-appeal bg-white rounded-xl text-black shadow-std mb-5 p-5 flex flex-row flex-wrap align-top justify-between gap-4'>
    <div class="appeal-info">
        @php $member = \App\Models\Member::where('id', $appeal->submitter_id)->first(); @endphp

        <div class="member-info flex flex-row flex-wrap gap-2 mb-3">
            <a href="/members/{{ $member->username }}">
                <img src='/images/profile/{{  $member->profile_image->file_name }}'
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

        <h2 class="font-title font-bold text-xl mt-5">Appeal:</h2>
        <h3 class="font-title text-lg">"{{ $appeal->body }}"</h3>
        <h3 class="font-title text-m mt-5">On {{ $appeal->date() }} at {{ $appeal->time() }}</h3>
    </div>

    <div class='manage-appeal-actions flex flex-row gap-2 items-top'>

        <button type="button" class="accept-appeal-button h-fit">
            <div class="group relative">
                <i class="text-green text-3xl bi bi-check-square-fill rounded" aria-hidden="true"></i>
                <span
                    class="hidden top-full z-50 text-white bg-black opacity-90 rounded p-2 text-xs font-main font-thin group-focus:block group-hover:block absolute right-0 text-right">
                    Accept Appeal
                </span>
            </div>
        </button>
        <dialog class="accept-appeal-dialog bg-white rounded-2xl px-10 py-5 w-4/5 sm:w-3/5 md:w-2/5 xl:w-1/5 text-center">
            <form action="{{ route('handle.appeal', $appeal->id) }}" class="h-fit" method="POST">
                {{ method_field('delete') }}
                {{ csrf_field() }}
                <input name="handle" value="accept" hidden>
                <h2 class="mb-9"> Are you sure you want to <strong>unblock</strong> {{ $appeal->submitter->name() }}? </h2>
                <div class="flex flex-row justify-between mt-8 w-full">
                    <button type='submit' class="bg-red text-white rounded-md px-2 py-1">Confirm</button>
                    <button type="button"
                        class="cancel-accept-appeal bg-white text-red border border-red rounded-md px-2 py-1">Cancel</button>
                </div>
            </form>
        </dialog>

        <form action="{{ route('handle.appeal', $appeal->id) }}" class="h-fit" method="POST">
            {{ method_field('delete') }}
            {{ csrf_field() }}
            <input name="handle" value="reject" hidden>
            <button type="submit">
                <div class="group relative">
                    <i class="text-red text-3xl bi bi-x-square-fill rounded" aria-hidden="true"></i>
                    <span
                        class="hidden top-full z-50 text-white bg-black opacity-90 rounded p-2 text-xs font-main font-thin group-focus:block group-hover:block absolute right-0 text-right">
                        Reject Appeal
                    </span>
                </div>
            </button>
            <form>
    </div>
</li>
