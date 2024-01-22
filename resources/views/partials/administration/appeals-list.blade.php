@forelse ($appeals as $appeal)
    @include('partials.administration.appeal', ['appeal' => $appeal])
@empty
    <li class="bg-white rounded-xl text-black shadow-std mb-5 p-5 flex flex-row align-top justify-between">
        <div class="tag-info">
            <h2 class="font-title font-bold text-xl"> No appeals to show. </h2>
        </div>
    </li>
@endforelse
