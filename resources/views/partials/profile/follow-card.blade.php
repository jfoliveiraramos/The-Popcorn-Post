@php $profileImage = $follow->profile_image @endphp
<li id='list-follows' class='follow-info flex flex-row align-top gap-2 py-2 border-b border-b-brown'>
    <a href="/members/{{ $follow->username }}"><img src='/images/profile/{{ $profileImage->file_name }}'
            class='profile-image w-12 h-12 rounded-full object-cover' alt='{{ $follow->name() }} Profile Image'></a>
    <div class="flex flex-col justify-start">
        <h2 class="font-title font-bold text-xl">
            <a href="/members/{{ $follow->username }}">{{ $follow->name() }}</a>
        </h2>
        <h3 class="leading-3 text-brown">
            &#64;<a href="/members/{{ $follow->username }}">{{ $follow->username }}</a>
        </h3>
    </div>
</li>
