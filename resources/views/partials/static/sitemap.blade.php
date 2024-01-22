<div class="container font-title mx-auto mt-8">

    <div class="bg-white p-4 rounded-lg mb-7">
        <h3 class="text-xl text-black font-bold mb-2">News Related</h3>
        <ul class="list-disc pl-8">
            <li><a href="http://localhost:8000/search?type=tags">Tags</a></li>
            <li><a href="{{ route('home') }}">News</a></li>
            @auth
            <li><a href="{{ route('create.article') }}">Create News</a></li>
            <li><button id="sitemap-propose-topic">Propose Topic</a></li>
            @endauth
            @guest
            <li><a href="{{ route('login') }}">Create News</a></li>
            <li><a href="{{ route('login') }}">Propose Topic</a></li>
            @endguest
        </ul>
    </div>

    <div class="bg-white p-4 rounded-lg mb-7">
        <h3 class="text-xl text-black font-bold mb-2">Search</h3>
        <ul class="list-disc pl-8">
            <li><a href="{{ route('search') }}">Search</a></li>
        </ul>
    </div>

    @Auth
    <div class="bg-white p-4 rounded-lg mb-7">
        <h3 class="text-xl text-black font-bold mb-2">Members and Profiles</h3>
        <ul class="list-disc pl-8">
            <li><a href="/members/{{ Auth::user()->username }}/settings">Account Settings</a></li>
            <li><a href="/members/{{ Auth::user()->username }}">Member Profile</a></li>
        </ul>
    </div>
    @endauth

    @guest
    <div class="bg-white p-4 rounded-lg mb-7">
        <h3 class="text-xl text-black font-bold mb-2">Member and Profiles</h3>
        <ul class="list-disc pl-8">
            <li><a href="{{ route('login') }}">Account Settings</a></li>
            <li><a href="{{ route('login') }}">Member Profile</a></li>
        </ul>
    </div>
    @endguest

    <div class="bg-white p-4 rounded-lg mb-7">
        <h3 class="text-xl text-black font-bold mb-2">Authentication</h3>
        <ul class="list-disc pl-8">
            <li><a href="{{ route('login') }}">Log in</a></li>
            <li><a href="{{ route('register') }}">Sign Up</a></li>
        </ul>
    </div>

    <div class="bg-white p-4 rounded-lg mb-7">
        <h3 class="text-xl text-black font-bold mb-2">General Information</h3>
        <ul class="list-disc pl-8">
            <li><a href="{{ route('about') }}">About Us</a></li>
            <li><a href="{{ route('contact') }}">Contact Us</a></li>
            <li><a href="{{ route('sitemap') }}">Sitemap</a></li>
        </ul>
    </div>
    
    @auth
    @if (Auth::user()->is_admin)
    <div class="bg-white p-4 rounded-lg mb-7">
        <h3 class="text-xl text-black font-bold mb-2">Administration</h3>
        <ul class="list-disc pl-8">
            <li><a href="/administration">Administration</a></li>
            <li><a href="/administration/create_member">Create Member</a></li>
        </ul>
    </div>
    @endif
    @endauth
</div>
