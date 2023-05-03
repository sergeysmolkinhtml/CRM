@extends('layouts.base')

@section('content')

    <div class="container">
        <h1>Hello world</h1>

        <div class="px-6">
            <a href="{{route('teams.create')}}">Create</a>
        </div>
    </div>

@endsection
