
@forelse ($suggestions as $suggestion)
    <li class=" bg-white rounded-xl text-black shadow-std mb-5 p-5 flex flex-row flex-wrap align-middle justify-between">
        <div class="suggestion-text h-fit">
            <h2 class="font-title font-bold text-xl"> {{ $suggestion->name }} </h2>
            @if ($suggestion->status == 'Pending')
                <h3 class=" text-gold leading-3 mt-0"> {{ $suggestion->status }} </h3>
            @elseif ($suggestion->status == 'Accepted')
                <h3 class="text-green leading-3 mt-0"> {{ $suggestion->status }} </h3>
            @elseif ($suggestion->status == 'Rejected')
                <h3 class="text-red leading-3 mt-0"> {{ $suggestion->status }} </h3>
            @endif
        </div>
        @if ($suggestion->status == 'Pending')
            <div class='manage-suggestion-actions flex flex-row gap-2 items-center'>
                <form action="{{ route('suggestions.update', $suggestion->id) }}" class="h-fit" method="POST">
                    {{ method_field('patch') }}
                    {{ csrf_field() }}
                    <input name="status" value="Accepted" hidden>
                    <button type="submit">
                        <div class="group relative">
                            <i class="text-green text-3xl bi bi-check-square-fill rounded" aria-hidden="true"></i>
                            <span
                                class="hidden top-full z-50 text-white bg-black opacity-90 rounded p-2 text-xs font-main font-thin group-focus:block group-hover:block absolute right-0 text-right">
                                Accept
                            </span>
                        </div>
                    </button>
                </form>
                <form action="{{ route('suggestions.update', $suggestion->id) }}" class="h-fit" method="POST">
                    {{ method_field('patch') }}
                    {{ csrf_field() }}
                    <input name="status" value="Rejected" hidden>
                    <button type="submit">
                        <div class="group relative">
                            <i class="text-red text-3xl bi bi-x-square-fill rounded" aria-hidden="true"></i>
                            <span
                                class="hidden top-full z-50 text-white bg-black opacity-90 rounded p-2 text-xs font-main font-thin group-focus:block group-hover:block absolute right-0 text-right">
                                Reject
                            </span>
                        </div>
                    </button>
                </form>
            </div>
        @endif
    </li>
@empty
<li class="bg-white rounded-xl text-black shadow-std mb-5 p-5 flex flex-row align-top justify-between">
    <div class="tag-info">
        <h2 class="font-title font-bold text-xl"> No suggestions to show. </h2>
    </div>
</li>
@endforelse

