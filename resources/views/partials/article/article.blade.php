<div class="article-section mt-8">
    <article class="article-item mb-6 shadow-std rounded-xl p-5 bg-white text-black" id="{{ $article->id }}">
        @include('partials.components.vote', ['contentItem' => $article])
        <header class="col-start-2">
            <ul class="article-topic flex flex-row">
                <li class="bg-gold rounded px-1.5">
                    @if ($article->topic->id != 0)
                        <a href="/search?type=articles_comments&topic={{ $article->topic->id }}&time=all"
                            class="text-white text-xs">{{ $article->topic->name }}</a>
                    @else
                        <span class="text-white text-xs">{{ $article->topic->name }}</a>
                    @endif
                </li>
            </ul>
            <h2 class="text-black font-title font-semibold text-2xl my-2"><a
                    href="/articles/{{ $article->id }}">{{ $article->title }}</a></h2>
            <ul class="article-tags flex flex-row flex-wrap gap-x-5 gap-y-2">
                @foreach ($article->tags as $tag)
                    <li class="bg-red rounded-sm px-1">
                        <a href="/tags/{{ $tag->name }}" class="italic text-white text-xs">{{ $tag->name }}</a>
                    </li>
                @endforeach
            </ul>
        </header>
        <div class="image-gallery col-start-2 my-3 grid grid-cols-12">
            <button class="btn-prev hidden" onclick="prevImage()"><i class="bi bi-caret-left-fill" aria-hidden="true"></i>
                <span class="sr-only">Previous</span></button>
            <div class="image-container col-start-2 col-span-10 flex justify-center">
                @foreach ($article->images as $image)
                    <img src="/images/articles/{{ $image->file_name }}" alt="{{ $article->title }}"
                        class="w-full object-cover">
                @endforeach
            </div>
            <button class="btn-next hidden" onclick="nextImage()"><i class="bi bi-caret-right-fill" aria-hidden="true"></i>
                <span class="sr-only">Next</span></button>
        </div>
        <p class="col-start-2 my-3 text-sm">{{ $article->body() }}</p>
        @if (Auth::check())
            <footer class="col-start-2 flex flex-row justify-between mt-2 text-xs sm:text-sm w-full items-center">
                <div class="options flex flex-row gap-4 items-center">
                    @if ($article->canEdit() && !Auth::user()->is_blocked)
                        <a href="/articles/{{ $article->id }}/edit" class="underline">Edit</a>
                    @endif
                    @if ($article->canDelete() && !Auth::user()->is_blocked)
                        <button class="delete-article underline">Delete</button>
                        <dialog class="delete-article-dialog p-7 rounded-lg gap-10">
                            <form method="POST" action="/articles/{{ $article->id }}" class="flex flex-col gap-8">
                                {{ method_field('delete') }}
                                {{ csrf_field() }}
                                <h2 class="text-base">Are you sure you want to delete this article?</h2>
                                <div class="flex flex-row justify-between text-sm">
                                    <button type="submit" class="bg-red text-white rounded px-3 py-1 w-fit self-center"
                                        onclick="disableSubmit(this)">Confirm</button>
                                    <button type="button"
                                        class="cancel-delete-article border border-red text-red rounded px-3 py-1 w-fit self-center">
                                        Cancel
                                    </button>
                                </div>
                            </form>
                        </dialog>
                    @endif
                </div>
                <div class="article-info flex flex-row items-center">
                    <span class="hidden sm:block article-date">{{ $article->date() }}</span>
                    <span class="article-author">
                        @if (!$article->author()->is_deleted)
                            <a href="/members/{{ $article->author()->username }}"
                                class="underline">{{ $article->author()->name() }}</a>
                        @else
                            {{ $article->author()->name() }}
                        @endif
                    </span>
                </div>
            </footer>
        @endif
    </article>
</div>
