@php $sameAuthorNews = $article->sameAuthorNews(); @endphp
@if ($sameAuthorNews->count() > 0)
    <section class="bg-white rounded-lg p-5 pb-1 mb-10 shadow font-title">
        <h2 class=" text-black text-2xl font-semibold mb-5">By The Same Author</h2>
        <ul class="list-decimal list-inside text-base font-medium underline" id="same-author-news">
            @foreach ($sameAuthorNews as $sameAuthorArticle)
                <li class="mb-5">
                    <a href="{{ route('articles', ['id' => $sameAuthorArticle->id]) }}" class="text-black focus:text-brown hover:text-brown">
                        {{ $sameAuthorArticle->title }}
                    </a>
                </li>
            @endforeach
        </ul>
    </section>
@endif
