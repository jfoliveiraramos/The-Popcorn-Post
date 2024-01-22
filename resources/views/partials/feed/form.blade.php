<form action="/api/feed" class="options-form text-white text-sm" id="options-form">
    <label for="feed-type" class="sr-only">Feed Type</label>
    <select name="feed-type" id="feed-type" class="p-2 rounded bg-red">
        @auth
            <option value="member" data-username="{{Auth::user()->username}}">For you</option>
        @endauth
        <option value="top">Top</option>
        <option value="new">Recent</option>
    </select>
    <label for="content-type" class="sr-only">Content Type</label>
    <select name="content-type" id="content-type" class="p-2 rounded bg-red text-white">
        @auth
            <option value="articles_comments">Articles and Comments</option>
        @endauth
        <option value="articles">Articles</option>
        @auth
            <option value="comments">Comments</option>
        @endauth
    </select>
</form>