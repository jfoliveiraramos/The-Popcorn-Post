@extends('layouts.app')

@section('top-nav')
    @include('partials.top-nav')
@endsection

@section('header')
    <h2 class="row-start-2 row-span-1 font-semibold pl-3 text-4xl text-black self-center text-start font-title"> Create Member Account </h2>
@endsection

@section('content')
    @include('partials.administration.create-member')
@endsection

@section('right-aside')
    @include('partials.footer')
@endsection
