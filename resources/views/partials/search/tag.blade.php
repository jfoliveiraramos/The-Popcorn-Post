@php
    $count = count($tag->nonDeletedArticles);
    $s = $count > 1 ? 's' : '';
@endphp
<article class="mb-3 shadow-std rounded-xl p-4 text-sm bg-white text-black flex flex-row gap-3 justify-between">
    <h2 class="font-title font-semibold text-lg leading-5"># <a class="hover:underline focus:underline" href="/tags/{{ $tag->name }}">{{ $tag->name }}</a></h2>
    <p> {{ $count }} article{{$s}} </p> 
</article>
