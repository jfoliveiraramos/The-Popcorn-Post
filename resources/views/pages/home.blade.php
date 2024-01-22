@extends('layouts.app')

@section('top-nav')
    @include('partials.top-nav')
@endsection

@section('header')  
    @include('partials.title-header')
@endsection

@section('right-aside')
    @include('partials.trending')
    @include('partials.footer')
    
@endsection


@section('content')
    @include('partials.feed.form')
    <div id="feed" class="overflow-visible">
    </div>
@endsection

@section('floating-button')
    @include('partials.create_button')
@endsection
