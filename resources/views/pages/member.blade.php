@extends('layouts.app')

@section('top-nav')
    @include('partials.top-nav')
@endsection

@section('header')
    @include('partials.profile.card', ['member' => $member])
@endsection

@section('content')
    @include('partials.profile.content',  ['member' => $member])
@endsection

@section('right-aside')
    @include('partials.footer')
@endsection

@if(Auth::user()->id == $member->id)    
    @section('floating-button')
        @include('partials.create_button')
    @endsection
@endif
