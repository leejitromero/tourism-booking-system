@extends('layouts.app')

@section('title','Lingayen Accommodations')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <div>
        <h2 class="fw-bold mb-1">Lingayen stays and resorts</h2>
        <p class="text-muted mb-0">
            Booking.com-inspired accommodation listings with ratings, distance, beach information, and date selection.
        </p>
    </div>

    @auth
        @if(auth()->user()->is_admin)
            <a href="{{ route('admin.packages.create') }}" class="btn btn-main">
                <i class="fa-solid fa-plus me-1"></i>Add Accommodation
            </a>
        @endif
    @endauth
</div>

<div class="d-grid gap-3">
@forelse($packages as $package)
    @php
        $stars = (int) ($package->stars ?? 0);
        $stars = max(0, min(5, $stars));

        $rating = (float) ($package->average_rating ?? 0);
        $reviewCount = (int) ($package->review_count ?: $package->reviews->count());
    @endphp

    <div class="card booking-list-card overflow-hidden">
        <div class="row g-0">
            <div class="col-md-4 col-lg-3 position-relative">
                <img class="listing-img" src="{{ $package->image_src }}" alt="{{ $package->title }}">
                <button type="button" class="heart-btn" title="Save">
                    <i class="fa-regular fa-heart"></i>
                </button>
            </div>

            <div class="col-md-8 col-lg-9">
                <div class="card-body p-3 p-lg-4 h-100">
                    <div class="row h-100">
                        <div class="col-lg-8">
                            <h3 class="fw-bold text-booking mb-1">
                                <a class="text-decoration-none" href="{{ route('packages.show', $package) }}">
                                    {{ $package->title }}
                                </a>

                                @if($stars > 0)
                                    <span class="stars ms-2">
                                        @for($i = 0; $i < $stars; $i++)
                                            <i class="fa-solid fa-star text-warning"></i>
                                        @endfor
                                    </span>
                                @endif
                            </h3>

                            <p class="small mb-1">
                                <a href="#" class="fw-semibold">{{ $package->location }}</a>
                                <span class="mx-1">·</span>
                                <a href="#map" class="fw-semibold">Show on map</a>

                                @if($package->distance)
                                    <span class="mx-1">·</span>{{ $package->distance }}
                                @endif
                            </p>

                            @if($package->beach_info)
                                <p class="small mb-2">
                                    <i class="fa-solid fa-umbrella-beach me-1"></i>{{ $package->beach_info }}
                                </p>
                            @endif

                            <p class="small mb-3 listing-description">
                                {{ Str::limit($package->description, 210) }}
                            </p>

                            <div class="d-flex flex-wrap gap-2">
                                @foreach(array_slice($package->amenities_list, 0, 4) as $amenity)
                                    <span class="amenity-pill">
                                        <i class="fa-solid fa-check me-1"></i>{{ $amenity }}
                                    </span>
                                @endforeach
                            </div>
                        </div>

                        <div class="col-lg-4 d-flex flex-column justify-content-between align-items-lg-end mt-3 mt-lg-0">
                            <div class="text-lg-end w-100">
                                <div class="d-flex justify-content-lg-end align-items-center gap-2">
                                    <div class="text-end lh-sm">
                                        <strong>{{ $rating >= 7 ? 'Good' : 'Review score' }}</strong><br>
                                        <small class="text-muted">{{ $reviewCount }} reviews</small>
                                    </div>
                                    <span class="score-box">{{ number_format($rating, 1) }}</span>
                                </div>
                            </div>

                            <div class="text-lg-end w-100 mt-4">
                                <small class="text-muted">Price for 1 night</small>
                                <h4 class="fw-bold mb-2">₱{{ number_format((float) $package->price, 2) }}</h4>

                                <a href="{{ route('packages.show', $package) }}" class="btn btn-outline-primary w-100 mb-2">
                                    View details
                                </a>

                                @auth
                                    <a href="{{ route('bookings.create', ['package_id' => $package->id]) }}" class="btn btn-main w-100">
                                        Select dates
                                    </a>
                                @else
                                    <a href="{{ route('login') }}" class="btn btn-main w-100">
                                        Login to book
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>

                    @auth
                        @if(auth()->user()->is_admin)
                            <div class="border-top mt-3 pt-3 d-flex gap-2">
                                <a href="{{ route('admin.packages.edit', $package) }}" class="btn btn-warning btn-sm">
                                    Edit
                                </a>

                                <form method="POST" action="{{ route('admin.packages.destroy', $package) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm" onclick="return confirm('Delete this accommodation?')">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </div>
@empty
    <div class="alert alert-info">
        No accommodations found. Try another search keyword.
    </div>
@endforelse
</div>
@endsection