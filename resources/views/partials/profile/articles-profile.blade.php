@forelse ($articles as $article)
    @include('partials.feed.article', ['article' => $article])
@empty
    <p class="text-center">No articles yet.</p>
@endforelse
