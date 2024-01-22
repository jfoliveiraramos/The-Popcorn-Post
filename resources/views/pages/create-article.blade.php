@extends('layouts.app')

@section('top-nav')
    @include('partials.top-nav')
@endsection

@section('header')
    <h2 class="row-start-2 row-span-1 font-semibold pl-3 text-4xl text-black self-center text-start font-title"> Create News Article </h2>
@endsection

@section('content')
    @include('partials.article.create-article', ['topics' => $topics])
@endsection

@section('right-aside')
    @include('partials.footer')
@endsection