<article class="feed-item mb-6 shadow-std rounded-xl p-5 bg-white text-black" data-id="{{ $comment->id }}">
    @include('partials.components.vote', ['contentItem' => $comment])
    <header class="flex flexrow items-center gap-2 mb-6 col-start-2">
        <img class="w-12 h-12 sm:w-14 sm:h-14 overflow-hidden rounded-full object-cover"
            src="/images/profile/{{ $comment->author()->profile_image->file_name }}"
            alt="{{ $comment->author()->name() }}">
        <div class="flex flex-col">
            <span class="comment-author text-red text-lg">
                @if (!$comment->author()->is_deleted)
                    <a href="/members/{{ $comment->author()->username }}" class="flex flex-row">
                        {{ $comment->author()->name() }}
                        @if ($comment->article->author()->id == $comment->author()->id)
                            <div class="group relative">
                                <i class="fa-solid fa-marker text-brown text-sm ml-1" aria-hidden="true"></i>
                                <span
                                    class="hidden top-full z-50 text-white bg-black opacity-90 rounded p-2 text-xs font-main font-thin group-focus:block group-hover:block absolute  whitespace-nowrap">Author</span>
                            </div>
                        @endif
                        @if ($comment->author()->is_admin)
                            <div class="group relative">
                                <i class="fa-solid fa-user-tie text-blue text-sm ml-1" aria-hidden="true"></i>
                                <span
                                   class="hidden top-full z-50 text-white bg-black opacity-90 rounded p-2 text-xs font-main font-thin group-focus:block group-hover:block absolute  whitespace-nowrap">Administrator</span>
                            </div>
                        @endif
                        @if ($comment->author()->is_blocked)
                            <div class="group relative">
                                <i class="fa-solid fa-lock text-brown text-sm ml-1" aria-hidden="true"></i>
                                <span
                                    class="hidden top-full z-50 text-white bg-black opacity-90 rounded p-2 text-xs w-fit whitespace-nowrapfont-main font-thin group-focus:block group-hover:block absolute  whitespace-nowrap">
                                    Blocked Member</span>
                            </div>
                        @endif
                    </a>
                @else
                    {{ $comment->author()->name() }}
                @endif
            </span>
            <span class="comment-date text-xs">{{ $comment->date() }}</span>
        </div>
    </header>
    <div class="flex flex-col col-start-2">
        <p class=" mb-4">{{ $comment->preview() }}</p>
        <div class="article-preview shadow-std p-3 mb-6 rounded-xl border bg-white text-black">
            <div class="flex flex-col items-start justify-between gap-1 w-full">
                <h2 class="font-title font-semibold text-lg mb-2">
                    <a href="/articles/{{ $comment->article->id }}">{{ $comment->article->title }}</a>
                </h2>
                <div class="flex flex-row w-full items-center gap-2">
                    <p class="text-sm w-full block self-start">{{ $comment->article->preview(200) }}</p>
                    @if ($comment->article->images->count() > 0)
                    <a href="/articles/{{ $comment->article_id }}" class="hidden sm:block">
                        <img class="object-cover h-20 rounded"
                            src="/images/articles/{{ $comment->article->images->first()->file_name }}"
                            alt="{{ $comment->article->title }}">
                    </a>
                @endif
                </div>
                
            </div>
        </div>
        <footer class="options text-xs sm:text-sm flex flex-row justify-between">
            <a href="/articles/{{ $comment->article_id }}" class="underline">Replies
                ({{ count($comment->replies) }})</a>
        </footer>
    </div>
</article>
