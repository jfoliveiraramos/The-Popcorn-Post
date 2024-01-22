<article class="mb-6 shadow-std rounded-xl pt-3 px-5 pb-2 text-sm bg-white text-black flex flex-col">
    @if (!$comment->is_reply) 
        <h2 class="text-base mb-2 border-l-4 border-red pl-2 rounded"> 
            <a class=" font-title font-semibold focus:underline hover:underline" href="/articles/{{$comment->article->id}}"> {{ $comment->article->title }} </a> 
        </h2>
    @else 
        <h2 class="text-base mb-2 border-l-4 border-red pl-2 rounded"> 
            <a class=" italic focus:underline hover:underline" href="/articles/{{$comment->article->id}}"> {{ $comment->replyTo->preview() }} </a> 
        </h2>
    @endif
    <h2 class="text-base mb-2"> <a class="focus:underline hover:underline" href="/articles/{{$comment->article->id}}"> {{ $comment->preview() }} </a> </h2>
    <div class="flex flex-row justify-between text-xs sm:text-sm">
        <div class="flex flex-row gap-10">
            <p> Score: {{ $comment->score() }} </p>
        </div>
        <div class="flex flex-row gap-1">
            <p class="hidden sm:block"> {{ $comment->date() }}, {{ $comment->time() }}, </p>
            <p class="text-right">
                <a class="font-medium focus:underline hover:underline font-main" href="/members/{{ $comment->author()->username }}"> {{ $comment->author()->name() }} </a>
            </p>
        </div>
    </div>
   

        
</article>
