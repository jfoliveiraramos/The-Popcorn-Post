<article class="feed-item mb-6 shadow-std rounded-xl p-5 bg-white text-black overflow-hidden" data-id="{{ $article->id }}">
    @include('partials.components.vote', ['contentItem' => $article])
    <header class="col-start-2">
        <h2 class="text-black font-title font-semibold text-2xl"><a
                href="/articles/{{ $article->id }}">{{ $article->title }}</a></h2>
        <ul class="article-tags flex flex-row flex-wrap gap-x-5 gap-y-2 overflow-x-scroll mt-1">
            <li class="bg-gold rounded px-1.5">
                @if($article->topic->id != 0)
                    <a href="/search?type=articles_comments&topic={{$article->topic->id}}&time=all" class="text-white text-xs">{{ $article->topic->name }}</a>
                @else
                    <span class="text-white text-xs">{{ $article->topic->name }}</a>
                @endif
            </li>
            @foreach ($article->tags as $tag)
                <li class="bg-red rounded px-1.5">
                    <a href="/tags/{{ $tag->name }}" class="italic text-white text-xs">{{ $tag->name }}</a>
                </li>
            @endforeach
        </ul>
    </header>
    <div class="col-start-2 flex flex-col items-center">
        @if ($article->images->count())
            <img class="my-3 w-full object-cover" src="/images/articles/{{ $article->images->first()->file_name }}"
                alt="{{ $article->title }}">
        @endif
        <p class="my-3 text-sm w-full">{{ $article->preview() }}</p>
        <footer class="flex flex-row justify-between mt-2 text-xs sm:text-sm w-full">
            <div class="flex flex-row gap-x-2 sm:gap-x-5">
                <a href="/articles/{{ $article->id }}" class="underline">Comments
                    ({{ $article->comments_count() }})</a>
            </div>
            @if (Auth::check())
                <p class="article-info flex flex-row items-end">
                    <span class="article-date hidden sm:block">{{ $article->date() }}</span>
                    <span class="article-author">
                        @if (!$article->author()->is_deleted)
                            <a href="/members/{{ $article->author()->username }}"
                                class="underline">{{ $article->author()->name() }}</a>
                        @else
                            {{ $article->author()->name() }}
                        @endif
                    </span>
                </p>
            @endif
        </footer>
    </div>
</article>
