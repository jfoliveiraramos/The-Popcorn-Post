<ul class="list-unstyled nav text-sm flex flex-row gap-4 mx-2 lg:mx-1">
    <li class="inline">
        <a href="{{ route('home') }}" class="inline-flex flex-row w-max items-center">
            <img class="w-10 h-10 md:w-16 md:h-16 logo" src="{{ url('images/logo.png') }}" alt="Logo">
            <p class="hidden lg:block font-title font-bold text-xl md:text-2xl text-black leading-5 whitespace-nowrap">
                {{ __('The') }}<br>{{ __('Popcorn Post') }}</p>
        </a>
    </li>
    <li class="w-1/2">
        <form action={{ route('search') }} method="GET" class="seach-form" role="search">
            <div class="search-container flex flex-row justify-between">
                @isset($type)
                    <input type="text" name="type" value="{{ $type }}" hidden>
                    @if ($type == 'articles' || $type == 'comments' || $type == 'articles_comments')
                        @isset($time)
                            <input type="text" name="time" value="{{ $time }}" hidden>
                        @endisset
                        @isset($minScore)
                            <input type="text" name="minScore" value="{{ $minScore }}" hidden>
                        @endisset
                        @isset($maxScore)
                            <input type="text" name="maxScore" value="{{ $maxScore }}" hidden>
                        @endisset
                    @endif
                @endisset
                @isset($exactMatch)
                    <input type="text" name="exactMatch" value="{{ $exactMatch }}" hidden>
                @endisset
                <label for="query" class="sr-only">Search:</label>
                <input class="border-2 border-blue border-solid rounded-md px-1.5 w-full" type="text" name="query" id="query"
                    placeholder="Search here" value="{{ isset($query) ? $query : '' }}">
                <button type="submit" class="ml-5 px-3 py-1 bg-blue text-white rounded hidden xl:block">Search</button>
            </div>
        </form>
    </li>
    @auth
        @if (!Auth::user()->is_blocked)
            <li class="h-full hidden md:inline-flex">
                <a class="flex flex-row bg-blue text-white py-1 px-2 rounded gap-2" href="{{ route('create.article') }}">
                    <i class="bi bi-pencil-square" aria-hidden="true"></i>
                    <p class="whitespace-nowrap">Create Article</p>
                </a>
            </li>
            <li class="h-full hidden md:inline-flex">
                <button type="button" id="topic-suggestion-button"
                    class="flex flex-row bg-blue text-white py-1 px-2 rounded gap-1 hover:cursor-pointer">
                    <i class="bi bi-plus-lg" aria-hidden="true"></i>
                    <span class="whitespace-nowrap">Propose Topic</span>
                </button>
            </li>
        @endif
    @endauth
    <li class="inline-flex flex-row items-center gap-3">
        @auth
            <div class="relative">
                <div id="notification-toggle"
                    class="notification-icon group relative hover:cursor-pointer flex flex-row pl-2">
                    <i class="bi bi-bell-fill" aria-hidden="true"></i>
                    <span class="sr-only">Notifications</span>
                    <span class="badge">0</span>
                    <span
                        class="hidden z-50 text-white bg-black opacity-90 rounded p-2 text-xs font-main font-thin group-focus:block group-hover:block top-full right-1 absolute  transition-all duration-300 delay-1000">Notifications</span>
                </div>
                <div
                    class="absolute -right-10 z-50 md:right-0 shadow-md bg-white text-xs sm:text-sm border-solid rounded-lg hidden">
                    <ul id="notification-dropdown" class="overflow-y-auto max-h-48 w-60 sm:w-80">
                    </ul>
                    <div class="flex flex-row justify-center border-t-2 border-brown" id="notification-dropdown-options">
                        <button
                            class="flex flex-row gap-2 p-2 w-full focus:bg-brown focus:text-white hover:bg-brown hover:text-white cursor-pointer justify-center"
                            id="mark-all-notifications-as-read">
                            <i class="bi bi-check2-all" aria-hidden="true"></i>
                            <span class="text-xs font-bold">Mark all as read</span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="relative overflow-visible">
                <div class="flex flex-row w-max md:w-44 gap-2 bg-transparent sm:border border-brown rounded-full sm:rounded sm:py-1 sm:px-3 focus:bg-brown focus:text-white hover:bg-beige hover:bg-opacity-20 cursor-pointer"
                    id="member-menu">
                    <img class="w-12 h-12 sm:w-10 sm:h-10 rounded-full object-cover border-2 border-brown sm:border"
                        src="/images/profile/{{ Auth::user()->profile_image->file_name }}" alt="Profile Image">
                    <div class="hidden sm:block">
                        <p class="text-sm font-medium text-brown whitespace-nowrap">{{ Auth::user()->name() }}</p>
                        <p class="text-xs text-brown">{{ '@' . Auth::user()->username }}</p>
                    </div>
                </div>
                <ul class="absolute top-full right-0 hidden z-50 bg-white rounded-lg w-fit sm:w-full shadow-md flex-col items-start text-sm"
                    id="options-dropdown">
                    <li class="w-full focus:bg-brown focus:text-white hover:bg-brown hover:text-white rounded-t-lg">
                        <a href="/members/{{ Auth::user()->username }}"
                            class="w-full flex flex-row gap-2 items-center p-3">
                            <i class="bi bi-person-circle text-base md:text-xl" aria-hidden="true"></i>
                            Profile
                        </a>
                    </li>
                    <li class="w-full focus:bg-brown focus:text-white hover:bg-brown hover:text-white">
                        <a href="/members/{{ Auth::user()->username }}/settings"
                            class="w-full flex flex-row gap-2 items-center p-3">
                            <i class="bi bi-gear-wide-connected text-base md:text-xl" aria-hidden="true"></i>
                            Settings
                        </a>
                    </li>
                    @if (Auth::user()->is_admin)
                        <li class="w-full focus:bg-brown focus:text-white hover:bg-brown hover:text-white">
                            <a href="/administration" class="w-full flex flex-row gap-2 items-center p-3">
                                <i class="bi bi-person-fill-lock text-base md:text-xl" aria-hidden="true"></i>
                                Administration
                            </a>
                        </li>
                    @endif
                    @if (Auth::user()->is_blocked)
                        <button type="button" id="appeal-button">
                            <li
                                class="w-full focus:bg-brown focus:text-white hover:bg-brown hover:text-white flex flex-row gap-2 text-left items-center p-3 whitespace-nowrap">

                                <i class="bi bi-exclamation-triangle-fill text-base md:text-xl" aria-hidden="true"></i>
                                Appeal for Unblock

                            </li>
                        </button>
                    @endif
                    <li class="w-full focus:bg-brown focus:text-white hover:bg-brown hover:text-white rounded-b-lg ">
                        <form method="post" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex flex-row gap-2 items-center p-3"><i
                                    class="bi bi-box-arrow-left text-base md:text-xl" aria-hidden="true"></i>
                                Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        @else
            <a href={{ route('login') }}
                class="rounded bg-blue text-white hover:bg-white hover:text-blue hover:border hover:border-blue focus:bg-white focus:text-blue focus:border focus:border-blue px-5 py-2">
                Login </a>
        @endauth
        <dialog id="appeal-dialog" class="p-4 rounded-md">
            <form action="/appeals/create" method="POST">
                @csrf
                <div class="flex flex-col gap-2">
                    <label for="appeal_body" class="text-center text-xl font-title font-semibold mb-3">Appeal
                        Message</label>
                    <textarea name="appeal_body" id="appeal_body" cols="30" rows="10"
                        class="border-2 border-blue border-solid rounded-md mb-3 px-1.5 w-full"></textarea>
                    <div class="text-red rounded-md px-2 py-1">
                        @if ($errors->any())
                            <div class="text-red rounded-md px-2 py-1">
                                @if ($errors->has('appeal_body'))
                                    <p>
                                        <i class="bi bi-x-circle text-xxs"></i>
                                            {{ $errors->first('appeal_body') }}
                                    </p>
                                @endif
                                @if ($errors->has('other_appeal'))
                                    <p>
                                        <i class="bi bi-x-circle text-xxs"></i>
                                            {{ $errors->first('other_appeal') }}
                                    </p>
                                @endif
                            </div>
                        @endif
                    </div>
                    <button type="submit" class="bg-blue text-white rounded-md px-2 py-1"
                        onclick="disableSubmit(this)">Submit</button>
                    <button type="button" id="cancel-appeal"
                        class="bg-red text-white rounded-md px-2 py-1">Cancel</button>

                </div>
            </form>
        </dialog>
        <script>
            @if ($errors->has('appeal_body') || $errors->has('other_appeal'))
                document.getElementById('appeal-dialog').showModal();
            @endif
        </script>
    </li>
</ul>
<dialog id="topic-suggestion-dialog" class="p-4 rounded-md">
    <form action="/topic_suggestions" method="POST">
        @csrf
        <div class="flex flex-col gap-2">
            <label for="topic_name" class="text-center text-xl font-title font-semibold mb-3">Topic Name</label>
            <input type="text" name="topic_name" id="topic_name"
                class="border-2 border-blue border-solid rounded-md mb-3 px-1.5 w-full">
            @if ($errors->any())
                <div class="text-red rounded-md px-2 py-1">
                    @if ($errors->has('topic_name'))
                        <p>
                            <i class="bi bi-x-circle text-xxs"></i>
                                {{ $errors->first('topic_name') }}
                        </p>
                    @endif
                    @if ($errors->has('suggestion_exists'))
                        <p>
                            <i class="bi bi-x-circle text-xxs"></i>
                                {{ $errors->first('suggestion_exists') }}
                        </p>
                    @endif
                    @if ($errors->has('topic_exists'))
                        <p>
                            <i class="bi bi-x-circle text-xxs"></i>
                                {{ $errors->first('topic_exists') }}
                        </p>
                    @endif
                </div>
            @endif
            <button type="submit" id="topic-suggestion-submit" class="bg-blue text-white rounded-md px-2 py-1"
                onclick="disableSubmit(this)">Submit</button>
            <button type="button" id="topic-suggestion-cancel"
                class="bg-red text-white rounded-md px-2 py-1">Cancel</button>

        </div>
    </form>
</dialog>
<script>
    @if ($errors->has('topic_name') || $errors->has('suggestion_exists') || $errors->has('topic_exists'))
        document.getElementById('topic-suggestion-dialog').showModal();
    @endif
</script>
