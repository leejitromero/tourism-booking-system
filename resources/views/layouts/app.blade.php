<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Lingayen Tourism Booking System')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    <style>
        :root{--main:#003b95;--accent:#0071c2;--dark:#0f172a;--soft:#f5f7fb;--yellow:#febb02}
        body{background:var(--soft);font-family:Segoe UI,Arial,sans-serif;color:#172033}.navbar{background:var(--main)}
        .navbar-brand,.nav-link{font-weight:600}.hero{background:linear-gradient(90deg,rgba(0,53,128,.92),rgba(0,113,194,.70)),url('https://images.unsplash.com/photo-1507525428034-b723cf961d3e?q=80&w=1600&auto=format&fit=crop');background-size:cover;background-position:center;color:#fff;padding:70px 0 105px;border-radius:0 0 28px 28px}.search-box{margin-top:-55px;position:relative;z-index:2}.search-card{border:4px solid var(--yellow);border-radius:16px;box-shadow:0 20px 45px rgba(15,23,42,.18)}
        .card{border:0;border-radius:18px;box-shadow:0 10px 25px rgba(15,23,42,.08)}.package-card{transition:.2s}.package-card:hover,.booking-list-card:hover{box-shadow:0 18px 38px rgba(15,23,42,.14)}.package-img{height:235px;object-fit:cover;border-radius:18px 18px 0 0}.listing-img{height:255px;width:100%;object-fit:cover}.detail-main-img{height:520px;width:100%;object-fit:cover;border-radius:18px;box-shadow:0 10px 25px rgba(15,23,42,.10)}.summary-img{height:230px;width:100%;object-fit:cover;border-radius:18px 18px 0 0}.btn-main{background:var(--accent);color:#fff;border:0}.btn-main:hover{background:#005fa3;color:#fff}.btn-yellow{background:var(--yellow);color:#172033;border:0;font-weight:700}.badge-rating{background:#003b95;color:white}.score-box{background:#003b95;color:#fff;border-radius:8px;padding:.45rem .6rem;font-weight:800;display:inline-block}.small-score{font-size:.85rem;padding:.25rem .5rem}.text-booking{color:#006ce4}.stars{color:#febb02;font-size:.65rem;vertical-align:middle}.heart-btn{position:absolute;top:12px;right:12px;width:42px;height:42px;border-radius:50%;border:0;background:#fff;font-size:1.2rem;box-shadow:0 4px 14px rgba(0,0,0,.18)}.amenity-pill{background:#eef6ff;color:#003b95;border-radius:999px;padding:.32rem .65rem;font-weight:600;font-size:.82rem}.listing-description{line-height:1.55}.map-placeholder{background:#eaf4ff;border:1px dashed #8bbceb;border-radius:16px;min-height:170px;display:flex;align-items:center;justify-content:center;color:#003b95;font-weight:700}.booking-summary{position:sticky;top:95px}.stat-card{border-left:5px solid var(--accent)}.footer{background:var(--dark);color:white;margin-top:70px;padding:26px 0}.table td,.table th{vertical-align:middle}.mini-muted{font-size:.9rem;color:#64748b}.feature-pill{background:#e8f2ff;color:#003b95;border-radius:999px;padding:.35rem .7rem;font-weight:600;font-size:.85rem}.print-only{display:none}@media(max-width:767px){.listing-img{height:220px}.detail-main-img{height:330px}}@media print{nav,.footer,.no-print,.btn,.search-box{display:none!important}.print-only{display:block}body{background:white}.card{box-shadow:none;border:1px solid #ddd}}
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark sticky-top shadow-sm no-print">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ route('packages.index') }}"><i class="fa-solid fa-earth-asia me-2"></i>Lingayen Tourism</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav"><span class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse" id="nav">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                <li><a class="nav-link" href="{{ route('packages.index') }}">Packages</a></li>
                @auth
                    <li><a class="nav-link" href="{{ route('bookings.index') }}">My Bookings</a></li>
                    @if(auth()->user()->is_admin)
                        <li><a class="nav-link" href="{{ route('admin.dashboard') }}">Admin</a></li>
                        <li><a class="nav-link" href="{{ route('admin.reports') }}">Reports</a></li>
                    @endif
                    <li><a class="nav-link" href="{{ route('profile.edit') }}">Profile</a></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">@csrf<button class="btn btn-light btn-sm rounded-pill px-3">Logout</button></form>
                    </li>
                @else
                    <li><a class="btn btn-light btn-sm rounded-pill px-3" href="{{ route('login') }}">Login</a></li>
                    <li><a class="btn btn-yellow btn-sm rounded-pill px-3" href="{{ route('register') }}">Register</a></li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

@if(request()->routeIs('packages.index'))
<section class="hero text-center no-print">
    <div class="container">
        <span class="feature-pill bg-white">Lingayen, Pangasinan</span>
        <h1 class="display-4 fw-bold mt-3">Find and book Lingayen tour packages</h1>
        <p class="lead mb-0">Inspired by booking platforms: search, compare prices, reserve, and track your booking status.</p>
    </div>
</section>
<section class="container search-box no-print">
    <form class="card card-body search-card" method="GET" action="{{ route('packages.index') }}">
        <div class="row g-3 align-items-end">
            <div class="col-md-5"><label class="form-label fw-semibold">Destination or package</label><input class="form-control form-control-lg" name="search" value="{{ request('search') }}" placeholder="Lingayen Beach, Capitol, staycation..."></div>
            <div class="col-md-3"><label class="form-label fw-semibold">Category</label><select class="form-select form-select-lg" name="category"><option value="">All categories</option>@foreach(($categories ?? []) as $category)<option value="{{ $category }}" @selected(request('category')===$category)>{{ $category }}</option>@endforeach</select></div>
            <div class="col-md-2"><label class="form-label fw-semibold">Sort</label><select class="form-select form-select-lg" name="sort"><option value="latest" @selected(request('sort')==='latest')>Latest</option><option value="price_low" @selected(request('sort')==='price_low')>Lowest price</option><option value="price_high" @selected(request('sort')==='price_high')>Highest price</option><option value="rating" @selected(request('sort')==='rating')>Best rating</option></select></div>
            <div class="col-md-2"><button class="btn btn-main btn-lg w-100"><i class="fa-solid fa-magnifying-glass me-1"></i> Search</button></div>
        </div>
    </form>
</section>
@endif

<main class="container py-5">
    @if(session('success'))<div class="alert alert-success no-print"><i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}</div>@endif
    @if(session('error'))<div class="alert alert-danger no-print"><i class="fa-solid fa-triangle-exclamation me-2"></i>{{ session('error') }}</div>@endif
    @if($errors->any())<div class="alert alert-danger no-print"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
    @yield('content')
</main>

<footer class="footer text-center no-print"><div class="container">Lingayen Tourism Booking System | Laravel Final Project 2026</div></footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
