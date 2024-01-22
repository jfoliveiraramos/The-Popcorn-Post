@php $relatedNews = $article->relatedNews(); @endphp
@if ($relatedNews->count() > 0)
    <section class="bg-white rounded-lg p-5 pb-1 mb-10 shadow font-title">
        <h2 class=" text-black text-2xl font-semibold mb-5">Related News</h2>
        <ul class="list-decimal list-inside text-base font-medium underline" id="related-news">
            @foreach ($relatedNews as $related)
                <li class="mb-3">
                    <a href="{{ route('articles', ['id' => $related->id]) }}" class="text-black focus:text-brown hover:text-brown">
                        {{ $related->title }}
                    </a>
                </li>
            @endforeach
        </ul>
    </section>
@endif
