@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">My Profile</h2>
        <p class="text-muted mb-0">Manage your account and view your tourism booking activity.</p>
    </div>

    <a href="{{ route('packages.index') }}" class="btn btn-main mt-3 mt-md-0">
        <i class="fa-solid fa-magnifying-glass me-1"></i> Browse Packages
    </a>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card card-body text-center mb-4">
            <div class="mx-auto rounded-circle d-flex align-items-center justify-content-center mb-3"
                 style="width:95px;height:95px;background:#e8f2ff;color:#003b95;font-size:38px;font-weight:800;">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>

            <h4 class="fw-bold mb-1">{{ $user->name }}</h4>
            <p class="text-muted mb-2">{{ $user->email }}</p>

            @if($user->is_admin)
                <span class="badge text-bg-primary rounded-pill px-3 py-2">Administrator</span>
            @else
                <span class="badge text-bg-success rounded-pill px-3 py-2">Tourist Account</span>
            @endif

            <hr>

            <div class="text-start">
                <p class="mb-2"><strong>Member since:</strong> {{ $user->created_at->format('M d, Y') }}</p>
                <p class="mb-2"><strong>Account status:</strong> Active</p>
                <p class="mb-0"><strong>Email:</strong> {{ $user->email_verified_at ? 'Verified' : 'Not verified' }}</p>
            </div>
        </div>

        <div class="card card-body">
            <h5 class="fw-bold mb-3">Account Settings</h5>

            <div class="mb-4">
                @include('profile.partials.update-profile-information-form')
            </div>

            <hr>

            <div class="mb-4">
                @include('profile.partials.update-password-form')
            </div>

            <hr>

            <div>
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card card-body h-100">
                    <p class="text-muted mb-1">Total Bookings</p>
                    <h3 class="fw-bold mb-0">{{ $stats['total'] }}</h3>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card card-body h-100">
                    <p class="text-muted mb-1">Pending</p>
                    <h3 class="fw-bold text-warning mb-0">{{ $stats['pending'] }}</h3>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card card-body h-100">
                    <p class="text-muted mb-1">Approved</p>
                    <h3 class="fw-bold text-primary mb-0">{{ $stats['approved'] }}</h3>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card card-body h-100">
                    <p class="text-muted mb-1">Completed</p>
                    <h3 class="fw-bold text-success mb-0">{{ $stats['completed'] }}</h3>
                </div>
            </div>
        </div>

        <div class="card card-body mb-4">
            <div class="d-flex flex-wrap justify-content-between align-items-center">
                <div>
                    <h5 class="fw-bold mb-1">Booking Summary</h5>
                    <p class="text-muted mb-0">Overview of your reservation history.</p>
                </div>

                <div class="text-md-end mt-3 mt-md-0">
                    <p class="text-muted mb-1">Total Approved/Completed Amount</p>
                    <h4 class="fw-bold text-primary mb-0">₱{{ number_format($totalSpent, 2) }}</h4>
                </div>
            </div>
        </div>

        <div class="card card-body mb-4">
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                <div>
                    <h5 class="fw-bold mb-1">Recent Bookings</h5>
                    <p class="text-muted mb-0">Your latest tourism reservations.</p>
                </div>

                <a href="{{ route('bookings.index') }}" class="btn btn-outline-primary btn-sm mt-3 mt-md-0">
                    View All
                </a>
            </div>

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Package</th>
                            <th>Guests</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($bookings as $booking)
                            <tr>
                                <td>
                                    <strong>{{ $booking->package->title ?? 'Deleted Package' }}</strong><br>
                                    <small class="text-muted">{{ $booking->package->location ?? 'N/A' }}</small>
                                </td>

                                <td>{{ $booking->people_count }}</td>

                                <td>₱{{ number_format($booking->total_amount, 2) }}</td>

                                <td>
                                    @if($booking->status === 'Approved' || $booking->status === 'Completed')
                                        <span class="badge text-bg-success">{{ $booking->status }}</span>
                                    @elseif($booking->status === 'Rejected')
                                        <span class="badge text-bg-danger">{{ $booking->status }}</span>
                                    @else
                                        <span class="badge text-bg-warning">{{ $booking->status }}</span>
                                    @endif
                                </td>

                                <td>
                                    <a href="{{ route('bookings.show', $booking) }}" class="btn btn-outline-primary btn-sm">
                                        View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    No bookings yet. Start by browsing available resorts and packages.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card card-body">
            <h5 class="fw-bold mb-3">Tourist Activity Timeline</h5>

            <div class="border-start ps-4">
                <div class="mb-4">
                    <h6 class="fw-bold mb-1">Account Created</h6>
                    <p class="text-muted mb-0">{{ $user->created_at->format('F d, Y') }}</p>
                </div>

                @forelse($bookings->take(3) as $booking)
                    <div class="mb-4">
                        <h6 class="fw-bold mb-1">Booked {{ $booking->package->title ?? 'a package' }}</h6>
                        <p class="text-muted mb-0">
                            {{ $booking->created_at->format('F d, Y') }}
                            • Status: {{ $booking->status }}
                        </p>
                    </div>
                @empty
                    <div>
                        <h6 class="fw-bold mb-1">No booking activity yet</h6>
                        <p class="text-muted mb-0">Your future reservations will appear here.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection