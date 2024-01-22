@foreach ($comments as $comment)
    @if ($comment->is_reply)
        @continue
    @endif
    @include('partials.article.comment', ['comment' => $comment])
@endforeach
