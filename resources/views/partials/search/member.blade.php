<article class="mb-6 shadow-std rounded-xl p-4 text-sm bg-white text-black flex flex-row gap-3">
    <img class="w-14 h-14 rounded-full object-cover" src="/images/profile/{{ $member->profile_image->file_name }}"
        alt="{{ $member->name() }}">
    <div>
        <h2 class="font-title font-semibold text-lg leading-tight flex flex-row gap-1 items-center">
            <a class="focus:underline hover:underline" href="/members/{{ $member->username }}"> {{ $member->name() }}</a>
            @if ($member->is_admin)
                <div class="group relative">
                    <i class="fa-solid fa-user-tie text-blue text-sm align-top ml-1" aria-hidden="true"></i>
                    <span
                        class="hidden top-full z-50 text-white bg-black opacity-90 rounded p-2 text-xs font-main font-thin group-focus:block group-hover:block absolute  whitespace-nowrap">Administrator</span>
                </div>
            @endif
            @if ($member->is_blocked)
                <div class="group relative">
                    <i class="fa-solid fa-lock text-brown text-sm align-top ml-1" aria-hidden="true"></i>
                    <span
                        class="hidden top-full z-50 text-white bg-black opacity-90 rounded p-2 text-xs w-fit whitespace-nowrapfont-main font-thin group-focus:block group-hover:block absolute whitespace-nowrap">
                        Blocked Member</span>
                </div>
            @endif
        </h2>
        <p> {{ '@' . $member->username }} </p>
    </div>
</article>
