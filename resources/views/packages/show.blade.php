@extends('layouts.app')

@section('title', $package->title)

@section('content')
@php
    $stars = max(0, min(5, (int) ($package->stars ?? 0)));
    $rating = (float) ($package->average_rating ?? 0);
    $reviewCount = (int) ($package->review_count ?: $package->reviews->count());
@endphp

<div class="mb-3">
    <a href="{{ route('packages.index') }}" class="text-decoration-none">
        <i class="fa-solid fa-arrow-left me-1"></i>Back to search results
    </a>
</div>

<div class="d-flex flex-wrap justify-content-between gap-3 align-items-start mb-3">
    <div>
        <h1 class="fw-bold mb-1">
            {{ $package->title }}

            @if($stars > 0)
                <span class="stars fs-6">
                    @for($i = 0; $i < $stars; $i++)
                        <i class="fa-solid fa-star text-warning"></i>
                    @endfor
                </span>
            @endif
        </h1>

        <p class="mb-0">
            <i class="fa-solid fa-location-dot text-danger me-1"></i>
            {{ $package->location }}

            @if($package->distance)
                · {{ $package->distance }}
            @endif

            @if($package->beach_info)
                · {{ $package->beach_info }}
            @endif
        </p>
    </div>

    <div class="d-flex align-items-center gap-2">
        <div class="text-end lh-sm">
            <strong>{{ $rating >= 7 ? 'Good' : 'Review score' }}</strong><br>
            <small class="text-muted">{{ $reviewCount }} reviews</small>
        </div>
        <span class="score-box fs-5">{{ number_format($rating, 1) }}</span>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-lg-8">
        <img class="detail-main-img" src="{{ $package->image_src }}" alt="{{ $package->title }}">
    </div>

    <div class="col-lg-4">
        <div class="card card-body h-100 justify-content-between">
            <div>
                <h4 class="fw-bold">Property highlights</h4>

                <div class="d-flex flex-wrap gap-2 mt-3">
                    @forelse($package->amenities_list as $amenity)
                        <span class="amenity-pill">
                            <i class="fa-solid fa-check me-1"></i>{{ $amenity }}
                        </span>
                    @empty
                        <span class="text-muted small">No amenities listed.</span>
                    @endforelse
                </div>
            </div>

            <div class="mt-4">
                <small class="text-muted">Starts from</small>
                <h2 class="text-primary fw-bold">
                    ₱{{ number_format((float) $package->price, 2) }}
                    <small class="text-muted fs-6">/ night</small>
                </h2>

                @auth
                    <a href="{{ route('bookings.create', ['package_id' => $package->id]) }}" class="btn btn-main btn-lg w-100">
                        Select dates
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-main btn-lg w-100">
                        Login to Reserve
                    </a>
                @endauth
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card card-body mb-4">
            <h3 class="fw-bold">Overview</h3>
            <p class="mb-0">{{ $package->description }}</p>
        </div>

        <div class="card card-body mb-4" id="map">
            <h3 class="fw-bold">Location</h3>
            <p class="mb-2">
                {{ $package->location }}

                @if($package->distance)
                    · {{ $package->distance }}
                @endif
            </p>

            <div class="map-placeholder">
                <i class="fa-solid fa-map-location-dot me-2"></i>
                Map preview placeholder for Lingayen accommodation location
            </div>
        </div>

        <div class="card card-body">
            <h3 class="fw-bold mb-3">Guest reviews</h3>

            @forelse($package->reviews as $review)
                <div class="border-bottom py-3">
                    <div class="d-flex justify-content-between">
                        <strong>{{ $review->user->name ?? 'Guest' }}</strong>
                        <span class="score-box small-score">{{ number_format((float) $review->rating, 1) }}</span>
                    </div>

                    <p class="mb-0 text-muted">
                        {{ $review->comment ?: 'No comment added.' }}
                    </p>
                </div>
            @empty
                <p class="text-muted mb-0">
                    No guest reviews yet. Seeded review score: {{ number_format($rating, 1) }}.
                </p>
            @endforelse
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card card-body position-sticky" style="top:95px">
            <h4 class="fw-bold">Reserve your stay</h4>
            <p class="text-muted small">
                Choose your check-in and check-out dates on the next page.
            </p>

            <div class="row text-center g-2 mb-3">
                <div class="col-4">
                    <div class="bg-light rounded-3 p-2">
                        <strong>{{ $package->slots }}</strong><br>
                        <small>Slots</small>
                    </div>
                </div>

                <div class="col-4">
                    <div class="bg-light rounded-3 p-2">
                        <strong>{{ $package->duration ?? '1 Night' }}</strong><br>
                        <small>Duration</small>
                    </div>
                </div>

                <div class="col-4">
                    <div class="bg-light rounded-3 p-2">
                        <strong>{{ $package->category }}</strong><br>
                        <small>Type</small>
                    </div>
                </div>
            </div>

            @auth
                <a href="{{ route('bookings.create', ['package_id' => $package->id]) }}" class="btn btn-main btn-lg">
                    Book now
                </a>
            @else
                <a href="{{ route('login') }}" class="btn btn-main btn-lg">
                    Login to book
                </a>
            @endauth
        </div>
    </div>
</div>
@endsection