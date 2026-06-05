@extends('layouts.app')
@section('title','Manage Bookings')
@section('content')
<h2 class="fw-bold mb-4">Manage Bookings</h2>
<div class="card card-body table-responsive">
<table class="table align-middle"><thead><tr><th>Tourist</th><th>Accommodation</th><th>Dates</th><th>Nights</th><th>Guests</th><th>Total</th><th>Payment</th><th>Status</th><th>Update</th></tr></thead><tbody>
@forelse($bookings as $booking)
<tr>
<td>{{ $booking->user->name }}</td>
<td><strong>{{ $booking->package->title }}</strong><br><small class="text-muted">{{ $booking->package->location }}</small></td>
<td>{{ optional($booking->check_in_date ?? $booking->booking_date)->format('M d, Y') }}<br><small>to {{ optional($booking->check_out_date)->format('M d, Y') ?? 'N/A' }}</small></td>
<td>{{ $booking->nights ?? 1 }}</td><td>{{ $booking->people_count }}</td><td>₱{{ number_format($booking->total_amount,2) }}</td><td>{{ $booking->payment->payment_method ?? 'N/A' }} / {{ $booking->payment->payment_status ?? 'N/A' }}</td><td><span class="badge text-bg-secondary">{{ $booking->status }}</span></td>
<td><form method="POST" action="{{ route('admin.bookings.update',$booking) }}" class="d-flex gap-2">@csrf @method('PATCH')<select name="status" class="form-select form-select-sm"><option @selected($booking->status==='Pending')>Pending</option><option @selected($booking->status==='Approved')>Approved</option><option @selected($booking->status==='Rejected')>Rejected</option><option @selected($booking->status==='Completed')>Completed</option></select><button class="btn btn-sm btn-main">Save</button></form></td></tr>
@empty<tr><td colspan="9">No bookings yet.</td></tr>@endforelse
</tbody></table></div>
@endsection
