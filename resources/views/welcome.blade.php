@extends('layouts.app')
@section('title','Lingayen Tourism Booking System')
@section('content')
<div class="text-center py-5">
    <h1 class="fw-bold">Lingayen Tourism Booking System</h1>
    <p class="lead text-muted">Browse Lingayen tour packages and create your reservation.</p>
    <a href="{{ route('packages.index') }}" class="btn btn-main btn-lg">View Packages</a>
</div>
@endsection
