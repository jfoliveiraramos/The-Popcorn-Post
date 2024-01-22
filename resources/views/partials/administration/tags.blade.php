<section id='tags-list' class='tags-list administration-section'>
    <header>
        <h2 class="font-title text-2xl font-semibold mb-3"> Tags </h2>
    </header>

    <form  class="admin-tags-form text-white text-sm mb-4" id="admin-tags-form">
        <label for="tags-type" class="sr-only">Tags Type</label>
        <select name="tagsType" id="tags-type" class="p-2 rounded bg-red">
            <option value="all">All</option>
            <option value="banned">Banned</option>
            <option value="unbanned">Unbanned</option>
        </select>
    </form>

    <ul id="tag-list" class="list-none"></ul> 
    
    
</section>
