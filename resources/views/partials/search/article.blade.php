<article class="mb-6 shadow-std rounded-xl pt-5 px-5 pb-2 text-sm bg-white text-black flex flex-col">
    <h2 class="mb-2"> <a class="text-black text-lg font-bold font-title focus:underline hover:underline" href="/articles/{{ $article->id }}"> {{ $article->title }} </a> </h2> 
    <p class="mb-2"> {{ $article->preview(200) }} </p>
    <footer class="flex flex-row justify-between text-xs sm:text-sm">
        <div class="flex flex-row gap-3 sm:gap-10">
            <p> Score: {{ $article->score() }} </p>
            <p> Comments: {{ count($article->comments) }} </p>
        </div>
        <div class="flex flex-row gap-1">
            <p class="hidden sm:block"> {{ $article->date(). ", " . $article->time() . "," }}</p>
            <p class="text-right"><a class="font-medium focus:underline hover:underline font-main" href="/members/{{ $article->author()->username }}">{{ $article->author()->name() }} </a></p>
        </div>
    </footer>
</article>
