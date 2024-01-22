<li class="comment-article my-4 [&>form]:sm:col-start-2" id="{{ $comment->id }}">
    @include('partials.components.vote', ['contentItem' => $comment])
    <div class="comment-body col-start-2 border-b border-beige pb-4">
        @if (Auth::check())
            <header class="flex flexrow items-center gap-2 mb-2">
                <img src="/images/profile/{{ $comment->author()->profile_image->file_name }}"
                    class="w-10 h-10 overflow-hidden rounded-full object-cover" alt="{{ $comment->author()->name() }}">
                <div class="flex flex-col">
                    <span class="comment-author text-red text-lg">
                        @if (!$comment->author()->is_deleted)
                            <a href="/members/{{ $comment->author()->username }}" class="flex flex-row gap-1">
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
                                            class="hidden top-full z-50 text-white bg-black opacity-90 rounded p-2 text-xs w-fit whitespace-nowrapfont-main font-thin group-focus:block group-hover:block absolute whitespace-nowrap">
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
        @endif
        <p>{{ $comment->body() }}</p>
        <foot er class="flex flex-row items-center text-xs md:text-sm w-full gap-2 md:gap-4 mt-2">
            @if ($comment->replies_count())
                <button class="show-replies underline">Show replies ({{ $comment->replies_count() }})</button>
                <button class="hide-replies underline hidden">Hide replies</button>
            @endif
            @if (Auth::check() && !Auth::user()->is_blocked)
                @if (!$comment->is_reply)
                    <button class="comment-reply underline" data-id="{{ $comment->id }}">Reply</button>
                @else
                    <button class="comment-reply underline" data-id="{{ $comment->reply_id }}">Reply</button>
                @endif
                <button class="comment-cancel-reply underline hidden">Cancel</button>
            @endif
            @if (Auth::check() && !Auth::user()->is_blocked)
                @if ($comment->canEdit())
                    <button class="comment-edit underline"
                        data-action="/api/articles/{{ $comment->article_id }}/comments/{{ $comment->id }}">Edit</button>
                    <button class="comment-cancel-edit underline hidden">Cancel</button>
                @endif
                @if ($comment->canDelete())
                    <button type="submit" class="comment-delete underline"
                        data-action="/api/articles/{{ $comment->article_id }}/comments/{{ $comment->id }}">Delete</button>
                @endif
            @endif
            </footer>
    </div>
    @if ($comment->replies_count() && Auth::check())
        <ul class="replies col-start-2 hidden">
            @foreach ($comment->replies as $reply)
                @if (!$reply->contentItem->is_deleted)
                    @include('partials.article.comment', ['comment' => $reply])
                @endif
            @endforeach
        </ul>
    @elseif ($comment->replies_count())
        <ul class="replies col-start-2 ml-6 hidden">
            @foreach ($comment->replies as $reply)
                @if (!$reply->contentItem->is_deleted)
                    @include('partials.article.comment', ['comment' => $reply])
                @endif
            @endforeach
        </ul>
    @endif
</li>
