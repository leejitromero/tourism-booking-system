@extends('layouts.app')
@section('title','My Bookings')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold mb-0">My Bookings</h2>
    <a href="{{ route('packages.index') }}" class="btn btn-main">Book another stay</a>
</div>
<div class="card card-body">
    <div class="table-responsive">
        <table class="table align-middle">
            <thead><tr><th>Accommodation</th><th>Check-in</th><th>Check-out</th><th>Nights</th><th>Guests</th><th>Total</th><th>Status</th><th></th></tr></thead>
            <tbody>
            @forelse($bookings as $booking)
                <tr>
                    <td><strong>{{ $booking->package->title }}</strong><br><small class="text-muted">{{ $booking->package->location }}</small></td>
                    <td>{{ optional($booking->check_in_date ?? $booking->booking_date)->format('M d, Y') }}</td>
                    <td>{{ optional($booking->check_out_date)->format('M d, Y') ?? 'N/A' }}</td>
                    <td>{{ $booking->nights ?? 1 }}</td>
                    <td>{{ $booking->people_count }}</td>
                    <td>₱{{ number_format($booking->total_amount,2) }}</td>
                    <td><span class="badge text-bg-{{ $booking->status==='Approved' || $booking->status==='Completed' ? 'success' : ($booking->status==='Rejected' ? 'danger' : 'warning') }}">{{ $booking->status }}</span></td>
                    <td><a href="{{ route('bookings.show',$booking) }}" class="btn btn-outline-primary btn-sm">View</a></td>
                </tr>
            @empty
                <tr><td colspan="8" class="text-center text-muted py-4">No bookings yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
