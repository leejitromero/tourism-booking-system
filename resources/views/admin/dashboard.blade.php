@extends('layouts.app')

@section('title','Admin Dashboard')

@section('content')
<h2 class="fw-bold mb-4">Admin Dashboard</h2>

<div class="row g-4">
    <div class="col-md-3">
        <div class="card card-body stat-card">
            <h6>Packages</h6>
            <h2>{{ $packages }}</h2>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card card-body stat-card">
            <h6>Total Bookings</h6>
            <h2>{{ $bookings }}</h2>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card card-body stat-card">
            <h6>Pending</h6>
            <h2>{{ $pending }}</h2>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card card-body stat-card">
            <h6>Revenue</h6>
            <h2>₱{{ number_format($revenue,2) }}</h2>
        </div>
    </div>
</div>

<div class="mt-4 d-flex flex-wrap gap-2">
    <a class="btn btn-main" href="{{ route('admin.bookings') }}">
        Manage Bookings
    </a>

    <a class="btn btn-outline-primary" href="{{ route('admin.reports') }}">
        Reports
    </a>

    <a class="btn btn-outline-success" href="{{ route('admin.packages.create') }}">
        Add Package
    </a>

    <a class="btn btn-outline-info" href="{{ route('admin.packages.manage') }}">
        Manage Packages
    </a>

    <a class="btn btn-outline-dark" href="{{ route('admin.users') }}">
        Users
    </a>
</div>

<div class="card card-body mt-4">
    <h4 class="fw-bold mb-3">Recent Bookings</h4>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Tourist</th>
                    <th>Package</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>
                @forelse($recentBookings as $booking)
                    <tr>
                        <td>{{ $booking->user->name }}</td>
                        <td>{{ $booking->package->title }}</td>
                        <td>{{ $booking->status }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">No bookings yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection