<div class="d-flex flex-column justify-content-center align-items-center min-vh-100 bg-light">
    <div class="mb-4">
        <a href="/">
            <x-application-logo class="d-block" style="width: 80px; height: 80px;" />
        </a>
    </div>

    <div class="card shadow-sm" style="width: 100%; max-width: 400px;">
        <div class="card-body p-4">
            {{ $slot }}
        </div>
    </div>
</div>

