<x-layout>
    <h3 class="text-center mb-4" style="color: #333; font-weight: 700;">Login</h3>

    <x-auth-session-status class="mb-3" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-3">
            <x-input-label for="email" :value="__('Email Address')" />
            <x-text-input id="email" class="form-control" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="text-danger mt-2" />
        </div>

        <div class="mb-3">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="form-control" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="text-danger mt-2" />
        </div>

        <div class="mb-3 form-check">
            <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
            <label for="remember_me" class="form-check-label">
                {{ __('Remember me') }}
            </label>
        </div>

        <button type="submit" class="btn btn-primary">{{ __('Log in') }}</button>

        @if (Route::has('password.request'))
            <div class="forgot-password">
                <a href="{{ route('password.request') }}">{{ __('Forgot your password?') }}</a>
            </div>
        @endif

        <div class="register-link">
            Don't have an account? <a href="{{ route('register') }}">Register here</a>
        </div>
    </form>
</x-layout>

