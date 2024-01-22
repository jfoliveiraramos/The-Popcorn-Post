@forelse ($comments as $comment)
    @include('partials.feed.comment', ['comment' => $comment])
@empty
    <p class="text-center">No comments yet.</p>
@endforelse