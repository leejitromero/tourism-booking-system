@extends('layouts.app')

@section('title', 'Booking Details')

@section('content')
<div class="container py-4">

    

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">
                <i class="fa-solid fa-calendar-check me-2"></i>
                Booking Details
            </h4>
        </div>

        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-6">
                    <h5 class="fw-bold mb-3">Tour Package Information</h5>

                    <p class="mb-2">
                        <strong>Package:</strong>
                        {{ $booking->package->title ?? 'N/A' }}
                    </p>

                    <p class="mb-2">
                        <strong>Location:</strong>
                        {{ $booking->package->location ?? 'N/A' }}
                    </p>

                    <p class="mb-2">
                        <strong>Price:</strong>
                        ₱{{ number_format($booking->package->price ?? 0, 2) }}
                    </p>
                </div>

                <div class="col-md-6">
                    <h5 class="fw-bold mb-3">Reservation Information</h5>

                    <p class="mb-2">
                        <strong>Check-in Date:</strong>
                        {{ \Carbon\Carbon::parse($booking->check_in_date)->format('F d, Y') }}
                    </p>

                    <p class="mb-2">
                        <strong>Check-out Date:</strong>
                        {{ \Carbon\Carbon::parse($booking->check_out_date)->format('F d, Y') }}
                    </p>

                    <p class="mb-2">
                        <strong>Nights:</strong>
                        {{ $booking->nights }}
                    </p>

                    <p class="mb-2">
                        <strong>People:</strong>
                        {{ $booking->people_count }}
                    </p>

                    <p class="mb-2">
                        <strong>Total Amount:</strong>
                        ₱{{ number_format($booking->total_amount, 2) }}
                    </p>

                    <p class="mb-2">
                        <strong>Status:</strong>

                        @if($booking->status === 'Pending')
                            <span class="badge bg-warning text-dark">Pending</span>
                        @elseif($booking->status === 'Approved')
                            <span class="badge bg-success">Approved</span>
                        @elseif($booking->status === 'Rejected')
                            <span class="badge bg-danger">Rejected</span>
                        @elseif($booking->status === 'Completed')
                            <span class="badge bg-info text-dark">Completed</span>
                        @else
                            <span class="badge bg-secondary">{{ $booking->status }}</span>
                        @endif
                    </p>
                </div>

            </div>

            <hr>

            <h5 class="fw-bold mb-3">Payment Information</h5>

            <p class="mb-2">
                <strong>Payment Method:</strong>
                {{ $booking->payment->payment_method ?? 'N/A' }}
            </p>

            <p class="mb-2">
                <strong>Reference Number:</strong>
                {{ $booking->payment->reference_number ?? 'N/A' }}
            </p>

            <p class="mb-2">
                <strong>Payment Status:</strong>

                @if(optional($booking->payment)->payment_status === 'Pending')
                    <span class="badge bg-warning text-dark">Pending</span>
                @elseif(optional($booking->payment)->payment_status === 'Paid')
                    <span class="badge bg-success">Paid</span>
                @elseif(optional($booking->payment)->payment_status === 'Failed')
                    <span class="badge bg-danger">Failed</span>
                @else
                    <span class="badge bg-secondary">
                        {{ $booking->payment->payment_status ?? 'N/A' }}
                    </span>
                @endif
            </p>

            @if(!empty($booking->notes))
                <hr>

                <h5 class="fw-bold mb-3">Notes</h5>

                <p class="mb-0">
                    {{ $booking->notes }}
                </p>
            @endif

            <hr>

            <div class="d-flex flex-wrap gap-2">

                <a href="{{ route('bookings.index') }}" class="btn btn-outline-secondary">
                    <i class="fa-solid fa-arrow-left me-1"></i>
                    Back to My Bookings
                </a>

                @if($booking->status === 'Pending')
                    <form method="POST"
                          action="{{ route('bookings.destroy', $booking) }}"
                          onsubmit="return confirm('Are you sure you want to cancel this reservation?');">

                        @csrf
                        @method('DELETE')

                        <button type="submit" class="btn btn-danger">
                            <i class="fa-solid fa-xmark me-1"></i>
                            Cancel Reservation
                        </button>
                    </form>
                @endif

            </div>

            @if($booking->status !== 'Pending')
                <div class="alert alert-info mt-3 mb-0">
                    This reservation can no longer be cancelled because it is already
                    <strong>{{ $booking->status }}</strong>.
                </div>
            @endif

        </div>
    </div>

</div>
@endsection