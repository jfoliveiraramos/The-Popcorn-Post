<form class="flex flex-col mt-10 mb-5" id="filter" action="{{ route('search') }}" method="GET" role="search">
    <input type="text" name="query" id="query-hidden" value="{{ $query }}" hidden>
    <div class="flex flex-row flex-wrap justify-between text-sm gap-x-1 gap-y-4">
        <div class="flex flex-row flex-wrap gap-4">
            @if ($type == 'articles' || $type == 'comments' || $type == 'articles_comments')
                <button type="button" id="filter-button"
                    class="bg-red text-white rounded px-3 py-1 h-8 hover:cursor-pointer hover:bg-white hover:text-red  focus:bg-white focus:text-red whitespace-nowrap w-min">
                    <i class="bi bi-funnel" aria-hidden="true"></i>
                    Filters
                </button>
            @endif
            <label for="type-selector" class="sr-only">Type</label>
            <select name="type" id="type-selector"
                class="bg-red text-white rounded px-3 py-1 h-8  hover:cursor-pointer">
                @foreach ($typeOptions as $value => $label)
                    <option class="option" value="{{ $value }}"
                        {{ $value == old('type', $type) ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="flex flex-row flex-wrap items-end gap-4">
            <label for="exact-match"
                class="w-min bg-red text-white flex flex-row gap-2 items-center rounded px-3 py-1 h-8 hover:cursor-pointer hover:bg-white hover:text-red  focus:bg-white focus:text-red">
                <span class="whitespace-nowrap"> Exact Match </span>
                <input class="accent-white hover:accent-red hover:cursor-pointer focus:accent-red" id="exact-match"
                    type="checkbox" name="exactMatch" value="true" {{ $exactMatch ? 'checked' : '' }}>
            </label>
            <label for="sort-selector" class="sr-only">Sort by</label>
            <select name="sort" id="sort-selector"
                class="bg-red text-white rounded px-3 py-1 h-8 hover:cursor-pointer">
                <option value="highest_score" {{ isset($sort) && $sort === 'highest_score' ? 'selected' : '' }}>Highest
                    Score</option>
                <option value="lowest_score" {{ isset($sort) && $sort === 'lowest_score' ? 'selected' : '' }}>Lowest
                    Score</option>
                @if ($type == 'articles' || $type == 'comments' || $type == 'articles_comments')
                    <option value="newest" {{ isset($sort) && $sort === 'newest' ? 'selected' : '' }}>Most Recent
                    </option>
                    <option value="oldest" {{ isset($sort) && $sort === 'oldest' ? 'selected' : '' }}>Most Dated
                    </option>
                @endif
            </select>
        </div>
    </div>
    @if ($type == 'articles' || $type == 'comments' || $type == 'articles_comments')
        <dialog id="filter-dialog" class="bg-white p-7 rounded-lg">
            <div class="flex flex-col mb-3">
                <label for="topic-selector" class="text-red font-semibold font-title">Topic</label>
                <select name="topic" id="topic-selector" class="bg-red text-white rounded px-3 py-2 w-fit">
                    <option value="all" {{ isset($selectedTopic) && $selectedTopic === 'all' ? 'selected' : '' }}>
                        All Topics</option>
                    @foreach ($topics as $topic)
                        <option value="{{ $topic->id }}"
                            {{ isset($selectedTopic) && $selectedTopic == $topic->id ? 'selected' : '' }}>
                            {{ $topic->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex flex-col mb-3">
                <label for="time-selector" class="text-red font-semibold font-title">Time</label>
                <select name="time" id="time-selector" class="bg-red text-white rounded px-3 py-2 w-fit">
                    @php
                        $timeOptions = [
                            'all' => 'All Time',
                            'past_hour' => 'Past Hour',
                            'past_day' => 'Past Day',
                            'past_week' => 'Past Week',
                            'past_month' => 'Past Month',
                            'past_year' => 'Past Year',
                        ];
                    @endphp
                    @foreach ($timeOptions as $value => $label)
                        <option value="{{ $value }}" {{ isset($time) && $time === $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex flex-col mb-3">
                <label for="time-selector" class="text-red text-lg font-semibold font-title">Score</label>
                <div class="flex flex-row gap-2">
                    <label for="min-score" class="sr-only">Min Score</label>
                    <input name="minScore" id="min-score" value="{{ $minScore }}" type="number"
                        placeholder="min score"
                        class="bg-white border border-red text-red  rounded pl-3 pr-1 py-1 w-36 placeholder:text-red">
                    <p class="text-red text-lg font-semibold font-title self-end">to</p>
                    <label for="max-score" class="sr-only">Max Score</label>
                    <input name="maxScore" id="max-score" value="{{ $maxScore }}" type="number"
                        class="bg-white border border-red text-red  rounded pl-3 pr-1 w-36 placeholder:text-red"
                        placeholder="max score">
                </div>
            </div>
            <div class="flex flex-row justify-between mt-12">
                <button type="button" id="filter-cancel-button"
                    class="bg-white text-red border border-red rounded px-3 py-1 hover:cursor-pointer hover:bg-red hover:text-white  focus:bg-red focus:text-white">
                    Cancel
                </button>
                <button type="submit" id="filter-submit-button"
                    class="bg-red text-white rounded px-3 py-1 hover:cursor-pointer hover:bg-white hover:text-red focus:bg-white focus:text-red"
                    onclick="disableSubmit(this)">
                    Apply
                </button>
            </div>
        </dialog>
    @endif
</form>
