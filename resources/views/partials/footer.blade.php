<footer class="mb-10 font-title">
    <ul class=" bg-white rounded-lg px-5 py-5 shadow text-lg text-black flex flex-col gap-3 mb-1">
        <li><a class="focus:text-brown hover:text-brown" href="{{ route('contact') }}" >Contacts</a></li>
        <li><a class="focus:text-brown hover:text-brown" href="{{ route('about') }}">About Us</a></li>
        <li><a class="focus:text-brown hover:text-brown" href="{{ route('sitemap') }}">Sitemap</a></li>
    </ul>
    <p class="footer-text text-xxs pl-2">{{ config('app.name', 'Laravel') }} © {{now()->year}}. All rights reserved.</p>
</footer>