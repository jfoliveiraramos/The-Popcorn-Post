@auth
    @php
        $hasUpvoted = Auth::user()->hasUpvoted($contentItem->id);
        $hasDownvoted = Auth::user()->hasDownvoted($contentItem->id);
        $author = Auth::user()->id === $contentItem->author()->id;
        $canVote = !$author && !Auth::user()->is_blocked && !Auth::user()->is_deleted && !$contentItem->is_deleted;
    @endphp
    <div class="vote-menu flex flex-col items-center justify-center mr-5" data-id="{{ $contentItem->id }}"
        data-memberid="{{ Auth::user()->id }}" data-notifiedid="{{ $contentItem->author()->id }}">
        <button type="button"
            class="{{ $hasUpvoted ? 'text-gold' : '' }} {{ !$canVote ? ' hover:cursor-default opacity-20' : 'upvote-button' }}">
            <i class="bi bi-caret-up-fill" aria-hidden="true"></i>
            <span class="sr-only">Upvote</span>
        </button>
        <span class="score">{{ $contentItem->score() }}</span>
        <button type="button"
            class="{{ $hasDownvoted ? 'text-gold' : '' }} {{ !$canVote ? ' hover:cursor-default opacity-20' : 'downvote-button' }}">
             <i class="bi bi-caret-down-fill" aria-hidden="true"></i>
            <span class="sr-only">Downvote</span>
        </button>
    </div>
@endauth