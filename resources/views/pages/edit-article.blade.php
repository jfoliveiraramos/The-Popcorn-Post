@extends('layouts.app')

@section('top-nav')
    @include('partials.top-nav')
@endsection

@section('header')
    <div class="font-title text-black mb-5">
        <h1 class="text-3xl font-bold">Edit Article</h1>
        <h2>Make changes to your article below.</h2>
    </div>
@endsection
@section('content')
    
    @include('partials.article.edit-article', ['article' => $article, 'topics' => $topics])
@endsection

@section('right-aside')
    @include('partials.footer')
@endsection