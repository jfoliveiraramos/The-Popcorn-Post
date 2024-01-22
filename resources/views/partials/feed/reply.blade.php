<article class="feed-item mb-6 shadow-xl rounded-xl p-5 bg-white text-black" data-id="{{ $reply->id }}">
    @include('partials.components.vote', ['contentItem' => $reply])
    <header class="flex flexrow items-center gap-2 mb-6 col-start-2">
        <img class="w-12 h-12 sm:w-14 sm:h-14 overflow-hidden rounded-full object-cover"
            src="/images/profile/{{ $reply->author()->profile_image->file_name }}" alt="{{ $reply->author()->name() }}">
        <div class="flex flex-col">
            <span class="reply-author text-red text-lg">
                @if (!$reply->author()->is_deleted)
                    <a href="/members/{{ $reply->author()->username }}" class="flex flex-row">
                        {{ $reply->author()->name() }}
                        @if ($reply->article->author()->id == $reply->author()->id)
                            <div class="group relative">
                                <i class="fa-solid fa-marker text-brown text-sm ml-1" aria-hidden="true"></i>
                                <span
                                    class="hidden top-full z-50 text-white bg-black opacity-90 rounded p-2 text-xs font-main font-thin group-focus:block group-hover:block absolute  whitespace-nowrap">Author</span>
                            </div>
                        @endif
                        @if ($reply->author()->is_admin)
                            <div class="group relative">
                                <i class="fa-solid fa-user-tie text-blue text-sm ml-1" aria-hidden="true"></i>
                                <span
                                    class="hidden top-full z-50 text-white bg-black opacity-90 rounded p-2 text-xs font-main font-thin group-focus:block group-hover:block absolute  whitespace-nowrap">Administrator</span>
                            </div>
                        @endif
                        @if ($reply->author()->is_blocked)
                            <div class="group relative">
                                <i class="fa-solid fa-lock text-brown text-sm ml-1" aria-hidden="true"></i>
                                <span
                                    class="hidden top-full z-50 text-white bg-black opacity-90 rounded p-2 text-xs w-fit whitespace-nowrapfont-main font-thin group-focus:block group-hover:block absolute  whitespace-nowrap">
                                    Blocked Member</span>
                            </div>
                        @endif
                    </a>
                @else
                    {{ $reply->author()->name() }}
                @endif
            </span>
            <span class="comment-date text-xs">{{ $reply->date() }}</span>
        </div>
    </header>
    <div class="col-start-2 flex flex-col gap-y-3">
        <p><a href="articles/{{ $reply->article->id }}">{{ $reply->preview() }}</a></p>
        <div class="shadow-xl border rounded-xl p-3 bg-white text-black comment-preview">
            <header class="flex flexrow items-center gap-2 mb-6">
                <img class="w-12 h-12 sm:w-14 sm:h-14 rounded-full object-cover"
                    src="/images/profile/{{ $reply->replyTo->author()->profile_image->file_name }}"
                    alt="{{ $reply->replyTo->author()->name() }}">
                <div class="flex flex-col">
                    <span class="comment-author">
                        <a href="/members/{{ $reply->replyTo->author()->username }}"
                            class="text-red">{{ $reply->replyTo->author()->name() }}</a>
                    </span>
                    <span class="comment-date text-xs">{{ $reply->replyTo->date() }}</span>
                </div>
            </header>
            <p><a href="articles/{{ $reply->article->id }}">{{ $reply->replyTo->preview() }}</a></p>
        </div>
        <footer class="options text-xs sm:text-sm flex flex-row justify-between">
            <a href="/articles/{{ $reply->article_id }}" class="underline">Replies
                ({{ count($reply->replyTo->replies) }})</a>
        </footer>
    </div>
</article>
