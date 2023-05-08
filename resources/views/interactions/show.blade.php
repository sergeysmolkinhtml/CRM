@extends('layouts.app')

@section('content')
<a href="{{ route('interactions.index', ['customer' => $customer]) }}">View Interaction History</a>
@endsection
