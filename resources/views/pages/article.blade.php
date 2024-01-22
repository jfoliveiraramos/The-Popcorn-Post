@extends('layouts.app')

@section('top-nav')
    @include('partials.top-nav')
@endsection

@section('content')
    @include('partials.article.article', ['article' => $article])
    @include('partials.article.comments', ['article' => $article])
@endsection

@section('right-aside')
    @include('partials.article.related-news', ['article' => $article])
    @include('partials.article.same-author-news', ['article' => $article])
    @include('partials.footer')
@endsection