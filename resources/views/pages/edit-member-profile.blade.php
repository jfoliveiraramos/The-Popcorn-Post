@extends('layouts.app')

@section('top-nav')
    @include('partials.top-nav')
@endsection

@section('header')
    <div class="font-title text-black mb-5">
        <h1 class="text-3xl font-bold">Edit Member Profile</h1>
        <h2>Make changes to your profile below.</h2>
    </div>
@endsection

@section('content')
    @include('partials.profile.edit-form', ['member' => $member])
@endsection

@section('right-aside')
    @include('partials.footer')
@endsection