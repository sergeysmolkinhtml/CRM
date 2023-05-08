@extends('layouts.app')

@section('content')

<form method="POST" action="{{ route('interactions.store') }}">
    @csrf
    <div>
        <label for="customer">Customer:</label>
        <select name="customer_id" id="customer">
            @foreach($customers as $customer)
                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label for="type">Type:</label>
        <input type="text" name="type" id="type">
    </div>
    <div>
        <label for="description">Description:</label>
        <textarea name="description" id="description"></textarea>
    </div>
    <div>
        <label for="datetime">Datetime:</label>
        <input type="datetime-local" name="datetime" id="datetime">
    </div>
    <div>
        <button type="submit">Add Interaction</button>
    </div>
</form>

@endsection
