@extends('layouts.app')

@section('top-nav')
    @include('partials.top-nav')
@endsection

@section('header')
    @include('partials.tag.tag-header', ['tag' => $tag])
@endsection

@section('content')
    <section id="tag-articles" data-tag="{{ $tag->name }}"> </section>
@endsection

@section('right-aside')
    @include('partials.trending')
    @include('partials.footer')
@endsection
