@forelse ($tags as $tag)
    <li class=" bg-white rounded-xl text-black shadow-std mb-5 p-5 flex flex-row flex-wrap align-top justify-between">
        <div class="tag-info overflow-x-scroll">
            <a href="/tags/{{ $tag->name }}" class="font-title font-bold text-xl"> #{{ $tag->name }} </a>
            @if ($tag->is_banned)
                <h3 class="text-red"> Banned </h3>
            @endif
        </div>
        <div class="manage-tag-actions flex flex-row gap-2 h-min">
            <button class='edit-tag-button bg-red text-white rounded py-1 px-2 '>
                <div class="group relative">
                    <i class="bi bi-pen-fill" aria-hidden="true"></i>
                    <span
                        class="hidden top-full z-50 text-white bg-black opacity-90 rounded p-2 text-xs font-main font-thin group-focus:block group-hover:block absolute left-0 sm:right-0 sm:left-auto text-right whitespace-nowrap">
                        Edit Tag
                    </span>
                </div>
            </button>
            <dialog class="edit-tag-dialog bg-white rounded-2xl px-7 sm:px-10 py-5 w-4/5 sm:w-3/5 md:w-2/5 xl:w-1/5 text-center">
                <h2 class="mb-5 font-title font-semibold text-xl">Edit the
                    tag
                    <strong class="text-red">#{{ $tag->name }}</strong>
                </h2>
                <form method='POST' action='/tags/{{ $tag->name }}' class="flex flex-col items-center">
                    {{ method_field('patch') }}
                    {{ csrf_field() }}
                    <div class="flex flex-col items-start">
                        <label for="{{ $tag->name }}" class="pr-1">Name: </label>
                        <input type="text" name="name" id="{{ $tag->name }}"
                            class="border-2 border-red rounded focus:outline-none pl-2 py-1 text-sm" value="{{ $tag->name }}">
                    </div>
                    <div class="flex flex-row justify-between mt-8 w-full">
                        <button type='submit' class="bg-red text-white rounded-md px-2 py-1">Submit</button>
                        <button type="button"
                            class="cancel-edit-tag bg-white text-red border border-red rounded-md px-2 py-1">Cancel</button>
                    </div>
                </form>
            </dialog>
            <button class='ban-tag-button {{ !$tag->is_banned ? "bg-red text-white" : "bg-white text-red border border-red"}} rounded py-1 px-2 '>
                <div class="group relative">
                    <i class="bi bi-ban" aria-hidden="true"></i>
                    <span
                        class="hidden top-full z-50 text-white bg-black opacity-90 rounded p-2 text-xs font-main font-thin group-focus:block group-hover:block absolute left-0 sm:right-0 sm:left-auto text-right whitespace-nowrap">
                        {{ !$tag->is_banned ? "Ban Tag" : "Unban Tag" }}
                    </span>
                </div>
            </button>
            <dialog class="ban-tag-dialog bg-white rounded-2xl px-10 py-5 w-4/5 sm:w-3/5 md:w-2/5 xl:w-1/5 text-center">
                @if (!$tag->is_banned)
                    <h2 class="mb-9"> Are you sure you want to <strong>ban</strong>
                        this tag? </h2>
                @else
                    <h2 class="mb-9"> Are you sure you want to <strong>unban</strong>
                        this tag? </h2>
                @endif
                <form method='POST' action='/tags/{{ $tag->name }}'>
                    {{ method_field('patch') }}
                    {{ csrf_field() }}
                    <input type="hidden" name="is_banned" value="{{ !$tag->is_banned }}">
                    <div class="flex flex-row justify-between">
                        <button type='submit' class="bg-red text-white rounded-md px-2 py-1">Confirm</button>
                        <button type="button"
                            class="cancel-ban-tag bg-white text-red border border-red rounded-md px-2 py-1">Cancel</button>
                    </div>
                </form>
            </dialog>
        </div>
    </li>

@empty
    <li class="bg-white rounded-xl text-black shadow-std mb-5 p-5 flex flex-row align-top justify-between">
        <div class="tag-info">
            <h2 class="font-title font-bold text-xl"> No tags to show. </h2>
        </div>
    </li>
@endforelse
