@if ($trending_tags != null)
    <section class="bg-white rounded-lg p-5 pb-1 w-full mb-10 shadow">
        <h2 class="font-title text-black text-3xl font-semibold mb-2"> Trending </h2>
        <ul class="list-decimal list-inside text-base font-medium text-brown" id="trending-tags">
            @foreach ($trending_tags as $tag)
                <li class="py-2 font-title text-brown font-medium text-lg first:border-none border-t"> 
                    <a href="/tags/{{ $tag->name }}" class="text-black focus:text-brown hover:text-brown ml-1">
                        #{{ $tag->name }}
                    </a>
                    <div class="tag-info ml-8 mt-1 text-xs text-black grid grid-cols-6">
                        <p class="col-start-1 flex flex-row gap-1">Score: <span class="text-brown mr-4"> {{ $tag->getArticlesAcademyScore() }}</span></p> 
                        <p class="col-start-4 flex flex-row gap-1">Followers: <span class=" text-brown"> {{ $tag->followers->count() }}</span> </p>
                    </div>
                </li>
            @endforeach
        </ul>
    </section>
@endif
