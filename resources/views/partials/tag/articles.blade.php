@forelse ($articles as $article)
    @include('partials.feed.article', ['article' => $article])
@empty
    @if ($tag->is_banned)
        <div class="alert alert-info mt-5">
            <p class="font-title text-2xl text-black">This tag is <strong class="text-red">banned</strong>.</p>
        </div>
    @else
    <div class="alert alert-info">
        <p class="text-xl text-black py-3">There are no articles for this tag.</p>
    </div>
    @endif
@endforelse