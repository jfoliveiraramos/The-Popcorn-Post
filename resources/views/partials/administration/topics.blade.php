<section id='topics-list' class='topics-list administration-section'>
    <header>
        <h2 class="font-title text-2xl font-semibold mb-3"> Topics </h2>
    </header>

    <form  class="admin-topics-form text-white text-sm mb-4 flex flex-row flex-wrap gap-3" id="admin-topics-form">
        <label for="topic-type" class="sr-only">Topic Type</label>
        <select name="topic-type" id="topic-type" class="p-2 rounded bg-red">
            <option value="suggestions">Suggestions</option>
            <option value="topics">Topics</option>
        </select>
        <label for="suggestions-type" class="sr-only">Suggestions Type</label>
        <select name="suggestions-type" id="suggestions-type" class="p-2 rounded bg-red text-white ml-1">
            <option value="all">All</option>
            <option value="pending">Pending</option>
            <option value="accepted">Accepted</option>
            <option value="rejected">Rejected</option>
        </select>
    </form>

    <ul id="topic-list"></ul> 

</section>
