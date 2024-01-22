<section id='members-list' class='members-list administration-section'>
    <header class="flex flex-wrap gap-3 justify-between mb-3">
        <h2 class="font-title text-2xl font-semibold"> Members </h2>
        <a href="{{ route('create.member') }}" class="bg-red px-2 py-1 text-white rounded flex flex-row w-fit gap-2 self-end">
            <i class="bi bi-plus-lg" aria-hidden="true"></i>
            <h2>Add New Member</h2>
        </a>
    </header>
    <ul id='administration-members-list'>
    </ul>
</section>