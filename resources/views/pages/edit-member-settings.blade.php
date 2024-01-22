@extends('layouts.app')

@section('top-nav')
    @include('partials.top-nav')
@endsection

@section('header')
    <div class="font-title text-black mb-5">
        <h1 class=" text-3xl font-bold ">Edit Settings</h1>
        <h2>Make changes to your personal information below.</h2>
    </div>
@endsection

@section('content')
    
    @include('partials.settings.edit-form', ['member' => $member])
    @if (!Auth::user()->is_admin)
        @include('partials.settings.delete-account-form', ['member' => $member])
    @endif
@endsection

@section('right-aside')
    @include('partials.footer')
@endsection
