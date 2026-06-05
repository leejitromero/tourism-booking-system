<x-guest-layout>
    <div class="auth-card">
        <div class="text-center mb-4">
            <div class="brand-icon">
                <i class="fa-solid fa-map-location-dot"></i>
            </div>

            <h3 class="fw-bold mb-1">Create Account</h3>
            <p class="small-muted mb-0">Register to book your tourism package</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Registration failed.</strong>
                <ul class="mb-0 mt-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label fw-semibold">Full Name</label>
                <input id="name"
                       type="text"
                       name="name"
                       value="{{ old('name') }}"
                       class="form-control"
                       placeholder="Enter your full name"
                       required
                       autofocus>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label fw-semibold">Email Address</label>
                <input id="email"
                       type="email"
                       name="email"
                       value="{{ old('email') }}"
                       class="form-control"
                       placeholder="Enter your email"
                       required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label fw-semibold">Password</label>
                <input id="password"
                       type="password"
                       name="password"
                       class="form-control"
                       placeholder="Create a password"
                       required>
            </div>

            <div class="mb-4">
                <label for="password_confirmation" class="form-label fw-semibold">Confirm Password</label>
                <input id="password_confirmation"
                       type="password"
                       name="password_confirmation"
                       class="form-control"
                       placeholder="Confirm your password"
                       required>
            </div>

            <button type="submit" class="btn btn-main w-100">
                <i class="fa-solid fa-user-plus me-1"></i>
                Register
            </button>
        </form>

        <div class="text-center mt-4">
            <p class="small-muted mb-0">
                Already have an account?
                <a href="{{ route('login') }}" class="auth-link">Login here</a>
            </p>
        </div>

        <div class="text-center mt-3">
            <a href="{{ url('/packages') }}" class="auth-link small">
                <i class="fa-solid fa-arrow-left me-1"></i>
                Back to Packages
            </a>
        </div>
    </div>
</x-guest-layout>
