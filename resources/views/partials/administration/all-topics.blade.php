@forelse ($topics as $topic)
    <li class=" bg-white rounded-xl text-black shadow-std mb-5 p-5 flex flex-row flex-wrap gap-2 align-top justify-between">
        <div class="topic-info overflow-x-scroll">
            <h2 class="font-title font-bold text-xl"> {{ $topic->name }} </h2>
        </div>
        <div class="manage-topic-actions flex flex-row gap-2">
            @if ($topic->id !== 0)
                <button class='edit-topic-button bg-red text-white rounded py-1 px-2 '>
                    <div class="group relative">
                        <i class="bi bi-pen-fill" aria-hidden="true"></i>
                        <span
                            class="hidden top-full z-50 text-white bg-black opacity-90 rounded p-2 text-xs font-main font-thin group-focus:block group-hover:block absolute left-0 sm:right-0 sm:left-auto text-right whitespace-nowrap">
                            Edit Topic
                        </span>
                    </div>
                </button>
                        <dialog class="edit-topic-dialog bg-white rounded-2xl px-7 sm:px-10 py-5 w-4/5 sm:w-3/5 md:w-2/5 xl:w-1/5 text-center">
                            <h2 class="mb-5 font-title font-semibold text-xl">Edit the
                                topic <strong class="text-gold">{{ $topic->name }}</strong></h2>
                            <form method='POST' action='/topics/{{ $topic->id }}' class="flex flex-col items-center">
                                {{ method_field('patch') }}
                                {{ csrf_field() }}
                                <div class="flex flex-col items-start">
                                    <label for="{{ $topic->name }}" class="pr-1">Name: </label>
                                    <input type="text" name="name" id="{{ $topic->name }}"
                                        class="border-2 border-gold rounded focus:outline-none pl-2 py-1 text-sm" value="{{ $topic->name }}">
                                </div>
                                <div class="flex flex-row justify-between mt-8 w-full">
                                    <button type='submit'
                                        class="bg-red text-white rounded-md px-2 py-1">Submit</button>
                                    <button type="button"
                                        class="cancel-edit-topic bg-white text-red border border-red rounded-md px-2 py-1">Cancel</button>
                                </div>
                            </form>
                        </dialog>
                        <button class='delete-topic-button bg-red text-white rounded py-1 px-2 '>
                            <div class="group relative">
                                <i class="bi bi-trash3-fill" aria-hidden="true"></i>
                                <span
                                    class="hidden top-full z-50 text-white bg-black opacity-90 rounded p-2 text-xs font-main font-thin group-focus:block group-hover:block absolute left-0 sm:right-0 sm:left-auto text-right whitespace-nowrap">
                                    Delete Topic
                                </span>
                            </div>
                        </button>
                        <dialog class="delete-topic-dialog bg-white rounded-2xl px-10 py-5 w-4/5 sm:w-3/5 md:w-2/5 xl:w-1/5 text-center">
                            <h2 class="mb-9"> Are you sure you want to <strong class="text-red">delete</strong>
                                this topic? </h2>
                            <form method='POST' action='/topics/{{ $topic->id }}'>
                                {{ method_field('delete') }}
                                {{ csrf_field() }}
                                <div class="flex flex-row justify-between">
                                    <button type='submit'
                                        class="bg-red text-white rounded-md px-2 py-1">Confirm</button>
                                    <button type="button"
                                        class="cancel-delete-topic bg-white text-red border border-red rounded-md px-2 py-1">Cancel</button>
                                </div>
                            </form>
                        </dialog>
            @endif
        </div>
    </li>
    @empty
    <li class="bg-white rounded-xl text-black shadow-std mb-5 p-5 flex flex-row align-top justify-between">
        <div class="tag-info">
            <h2 class="font-title font-bold text-xl"> No topics to show. </h2>
        </div>
    </li>
@endforelse
