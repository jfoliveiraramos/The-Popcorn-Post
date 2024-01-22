@php
    $typeOptions = [
        'articles_comments' => 'Articles and Comments',
        'articles' => 'Articles',
        'comments' => 'Comments',
        'tags' => 'Tags',
    ];
    if (Auth::check()) {
        $typeOptions['members'] = 'Members';
    }
@endphp

@extends('layouts.app')

@section('top-nav')
    @include('partials.top-nav')
@endsection

@section('header')
    @include('partials.search.search-header')
@endsection

@section('content')
    @include('partials.search.search-form')
    <section id="search-results">   
        @include('partials.search.search-results')
    </section>
@endsection

@section('right-aside')
    @include('partials.trending')
    @include('partials.footer')
@endsection