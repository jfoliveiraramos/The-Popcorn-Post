@auth
<button id="create-content-button" class="fixed bottom-5 right-5 bg-blue w-20 h-20 rounded-full shadow-std md:hidden">
    <i class="bi bi-pencil-square text-white text-3xl" aria-hidden="true"></i>
</button>
<dialog id="create-content-dialog" class="bg-white rounded-xl px-8 pb-5 pt-14 relative">
    <a class="flex items-center bg-blue text-white text-sm py-1 px-2 rounded gap-2 mb-6" href="{{ route('create.article') }}">
        <i class="bi bi-pencil-square" aria-hidden="true"></i>
        Create Article
    </a>
    <button type="button" id="topic-suggestion-button-alt"
        class="flex flex-row bg-blue text-white text-sm py-1 px-2 rounded gap-1 hover:cursor-pointer">
        <i class="bi bi-plus-lg" aria-hidden="true"></i>
        Propose Topic
    </button>
    <button id="create-content-close" class="absolute top-2 right-3 text-brown w-5 h-5 text-xs text-center">
        <i class="bi bi-x-lg" aria-hidden="true"></i>
        <span class="sr-only">Close</span>
    </button>
</dialog>
@endauth
