@extends('layouts.app')

@section('top-nav')
    @include('partials.top-nav')
@endsection

@section('header')
    <h2 class="flex items-center row-start-2 row-span-1 font-semibold text-4xl text-black mb-7 self-center text-start font-title"> Sitemap </h2>
@endsection

@section('content')
    @include('partials.static.sitemap')
@endsection

@section('right-aside')
    @include('partials.footer')
@endsection