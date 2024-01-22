@php $authenticated = Auth::user() @endphp
@foreach ($members as $member)
    @if ($member->id != $authenticated->id)
        @include('partials.administration.member', ['member' => $member])
    @endif
@endforeach