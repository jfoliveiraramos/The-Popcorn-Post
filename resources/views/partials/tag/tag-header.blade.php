<header class="flex flex-row flex-wrap justify-between items-center mb-3">
    <h1 class="page-header font-title text-black font-bold text-5xl">#{{ $tag->name }}</h1>
    <div class="flex flex-row gap-2">
    @php $member = Auth::user(); @endphp
    @if (Auth::check() && $member->is_admin)
        <div class='manage-tag-actions flex flex-row h-min gap-2'>
            <button id='ban-tag-button' class='ban-tag-button {{ !$tag->is_banned ? "bg-red text-white" : "bg-white text-red border border-red"}} rounded py-1 px-2'>
                <i class="bi bi-ban" aria-hidden="true"></i>
                <span class="sr-only">Ban Tag</span>
            </button>
            <dialog id="ban-tag-dialog" class="ban-tag-dialog bg-white rounded-2xl px-10 py-5 w-1/5 text-center">
                @if ($tag->is_banned)
                    <h2 class="mb-9 "> Are you sure you want to <strong>unban</strong> this tag? </h2>
                @else
                    <h2 class="mb-9 "> Are you sure you want to <strong>ban</strong> this tag? </h2>
                @endif
                <form method='POST' action='/tags/{{ $tag->name }}'>
                    {{ method_field('patch') }}
                    {{ csrf_field() }}
                    <input type="hidden" name="is_banned" value= {{ !$tag->is_banned }}>
                    <input type="hidden" name="in_tag_page" value="true">
                    <div class="flex flex-row justify-between">
                        <button type='submit' class="bg-red text-white rounded-md px-2 py-1">Confirm</button>
                        <button type="button" id='cancel-ban-tag'
                            class="cancel-ban-tag bg-white text-red border border-red rounded-md px-2 py-1">Cancel</button>
                    </div>
                </form>
            </dialog>
            <dialog class="edit-tag-dialog bg-white rounded-2xl px-7 sm:px-10 py-5 w-4/5 sm:w-3/5 md:w-2/5 xl:w-1/5 text-center">
                <h2 class="mb-5 font-title font-semibold text-xl">Edit the
                    tag
                    <strong class="text-red">#{{ $tag->name }}</strong>
                </h2>
                <form method='POST' action='/tags/{{ $tag->name }}' class="flex flex-col items-center">
                    {{ method_field('patch') }}
                    {{ csrf_field() }}
                    <div class="flex flex-col items-start">
                        <label for="name" class="pr-1">Name: </label>
                        <input type="text" name="name" id="name"
                            class="border-2 border-red rounded focus:outline-none pl-2 py-1 text-sm" value="{{ $tag->name }}">
                    </div>
                    <div class="flex flex-row justify-between mt-8 w-full">
                        <button type='submit' class="bg-red text-white rounded-md px-2 py-1">Submit</button>
                        <button type="button"
                            class="cancel-edit-tag bg-white text-red border border-red rounded-md px-2 py-1">Cancel</button>
                    </div>
                </form>
            </dialog>
            <button id='edit-tag-button' class='edit-tag-button bg-red text-white rounded py-1 px-2'>
                <i class="bi bi-pen-fill" aria-hidden="true"></i>
                <span class="sr-only">Edit Tag</span>
            </button>
            <dialog id='edit-tag-dialog' class="edit-tag-dialog bg-white rounded-2xl px-7 sm:px-10 py-5 w-4/5 sm:w-3/5 md:w-2/5 xl:w-1/6 text-center">
                <h2 class="mb-5 font-title font-semibold text-xl">Edit the
                    tag
                    <strong class="text-red">#{{ $tag->name }}</strong>
                </h2>
                <form method='POST' action='/tags/{{ $tag->name }}' class="flex flex-col items-center">
                    {{ method_field('patch') }}
                    {{ csrf_field() }}
                    <div class="flex flex-col items-start">
                        <label for="name">Name</label>
                        <input type="text" name="name" class="border-2 border-red rounded focus:outline-none pl-2 py-1 text-sm" value="{{ $tag->name }}">
                        <input type="hidden" name="in_tag_page" value="true">
                    </div>
                    <div class="flex flex-row justify-between mt-8 w-full">
                        <button type='submit' class="bg-red text-white rounded-md px-2 py-1">Confirm</button>
                        <button type="button" id="cancel-edit-tag"
                            class="cancel-edit-tag bg-white text-red border border-red rounded-md px-2 py-1">Cancel</button>
                    </div>
                </form>
            </dialog>

        </div>
    @endif
    @if (!$tag->is_banned)
        @if (Auth::check() && $member->followsTag($tag->id))
            <form method='POST' action='/api/tags/{{ $tag->name }}/follow'>
                {{ csrf_field() }}
                {{ method_field('delete') }}
                <button type='submit' class="unfollow-button bg-white text-red border-red border  rounded-md px-2 py-1">
                    <i class="bi bi-person-fill-check" aria-hidden="true"></i>
                     Following
                </button>
            </form>
        @elseif (Auth::check() && !$member->followsTag($tag->id))
            <form method='POST' action='/api/tags/{{ $tag->name }}/follow'>
                {{ csrf_field() }}
                <button type='submit' class="follow-button bg-red text-white rounded-md px-2 py-1">
                    <i class="bi bi-person-fill-add" aria-hidden="true"></i>
                    Follow
                </button>
            </form>
        @endif
    @endif
    </dev>
</header>
