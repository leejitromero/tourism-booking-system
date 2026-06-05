<x-guest-layout>
    <div class="auth-card">
        <div class="text-center mb-4">
            <div class="brand-icon">
                <i class="fa-solid fa-umbrella-beach"></i>
            </div>

            <h3 class="fw-bold mb-1">Welcome Back</h3>
            <p class="small-muted mb-0">Login to your Tourism Booking account</p>
        </div>

        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Login failed.</strong>
                <ul class="mb-0 mt-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label fw-semibold">Email Address</label>
                <input id="email"
                       type="email"
                       name="email"
                       value="{{ old('email') }}"
                       class="form-control"
                       placeholder="Enter your email"
                       required
                       autofocus>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label fw-semibold">Password</label>
                <input id="password"
                       type="password"
                       name="password"
                       class="form-control"
                       placeholder="Enter your password"
                       required>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="form-check">
                    <input id="remember_me"
                           type="checkbox"
                           name="remember"
                           class="form-check-input">
                    <label class="form-check-label small-muted" for="remember_me">
                        Remember me
                    </label>
                </div>

                @if (Route::has('password.request'))
                    <a class="auth-link small" href="{{ route('password.request') }}">
                        Forgot password?
                    </a>
                @endif
            </div>

            <button type="submit" class="btn btn-main w-100">
                <i class="fa-solid fa-right-to-bracket me-1"></i>
                Login
            </button>
        </form>

        <div class="text-center mt-4">
            <p class="small-muted mb-0">
                Don't have an account?
                <a href="{{ route('register') }}" class="auth-link">Register here</a>
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
