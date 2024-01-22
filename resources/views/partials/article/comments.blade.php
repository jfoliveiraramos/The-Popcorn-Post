<section class="comments-section mb-6 shadow-std rounded-xl p-5 bg-white text-black" id="comments"
    data-id="{{ $article->id }}">
    <header class="flex justify-between align-items-center">
        <form  class="sort-comments">
            <label for="sort-comments" class="sr-only">Sort Comments</label>
            <select name="sort-comments" id="sort-comments" class=" p-2 rounded bg-white text-red border border-red">
                <option value="asc-date">Oldest</option>
                <option value="desc-date">Newest</option>
                <option value="desc-score">Descending Score</option>
                <option value="asc-score">Ascending Score</option>
            </select>
        </form>
        @if ($article->comments_count() > 0)
            <span class="comments-count">{{ $article->comments_count() }} Comments</span>
        @else
            <span class="comments-count">No Comments</span>
        @endif
    </header>
    @auth
        @if (!Auth::user()->is_blocked)
            <form action="/api/articles/{{ $article->id }}/comments" method="POST"
                class="comment-form text-white text-sm my-4 flex flex-row gap-2 col-span-2">
                @csrf
                <input type="hidden" name="reply_id" value="">
                <label for="body" class="sr-only">Comment</label>
                <textarea name="body" id="body" placeholder="Add Comment" rows="1"
                    class="p-2 grow text-black resize-none border-b border-b-red"></textarea>
                <button type="submit" class="px-3 py-0 bg-red text-white rounded h-8" onclick="disableSubmit(this)">Submit</button>
            </form>
        @endif
    @endauth
    @if ($article->comments_count() > 0)
        <ul class="comments">
        </ul>
    @endif
    @Auth
        <dialog class="delete-comment-dialog p-7 rounded-lg gap-10">
            <form method="POST"  class="flex flex-col gap-8 text-sm">
                {{ method_field('delete') }}
                {{ csrf_field() }}
                <h2 class="text-base">Are you sure you want to delete this comment?</h2>
                <div class="flex flex-row justify-between">
                    <button type="submit"
                        class="confirm-delete-comment-dialog bg-red text-white rounded px-3 py-1 w-fit self-center" onclick="disableSubmit(this)">Confirm</button>
                    <button type="button"
                        class="cancel-delete-comment-dialog border border-red text-red rounded px-3 py-1 w-fit self-center">
                        Cancel
                    </button>
                </div>
            </form>
        </dialog>
    @endauth
</section>
