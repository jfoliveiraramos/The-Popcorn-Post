@foreach ($items as $item)
    @if ($item->getMorphClass() == 'App\Models\Article')
        @include('partials.feed.article', ['article' => $item])
    @elseif ($item->getMorphClass() == 'App\Models\Comment')
        @if ($item->is_reply)
            @include('partials.feed.reply', ['reply' => $item])
        @else
            @include('partials.feed.comment', ['comment' => $item])
        @endif
    @endif
@endforeach

