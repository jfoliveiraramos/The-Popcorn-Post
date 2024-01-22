@extends('layouts.app')

@section('top-nav')
    @include('partials.top-nav')
@endsection


@section('header')
    <div class="font-title text-black mb-4">
        <h1 class="text-3xl font-bold"> Administration </h1>   
    </div>
@endsection

@section('content')
<div class="administration-options flex flex-row flex-wrap justify-center mb-10 gap-1" id="administration-options">
    <button type="button" class="w-5/12" id="administration-members-button" data-type="members" disabled>Members</button>
    <button type="button" class="w-5/12" id="administration-topics-button" data-type="topics">Topics</button>
    <button type="button" class="w-5/12" id="administration-tags-button" data-type="tags">Tags</button>
    <button type="button" class="w-5/12" id="administration-appeals-button" data-type="appeals">Appeals</button>
</div>
    @include('partials.administration.members')
    @include('partials.administration.topics')
    @include('partials.administration.tags')
    @include('partials.administration.appeals')
@endsection

@section('right-aside')
    @include('partials.footer')
@endsection