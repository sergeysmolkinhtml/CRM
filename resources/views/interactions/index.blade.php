@extends('layouts.app')

@section('content')
    @foreach($interactions as $interaction)
        <div>
            <p>Customer: {{ $interaction->employee->name }}</p>
            <p>Type: {{ $interaction->type }}</p>
            <p>Description: {{ $interaction->description }}</p>
            <p>Datetime: {{ $interaction->datetime }}</p>
        </div>
    @endforeach
@endsection
