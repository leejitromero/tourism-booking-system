@extends('layouts.app')

@section('title', 'Manage Packages')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
    <div>
        <h2 class="fw-bold mb-1">Manage Packages</h2>
        <p class="text-muted mb-0">Select a package to edit or delete.</p>
    </div>

    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
            Back to Dashboard
        </a>

        <a href="{{ route('admin.packages.create') }}" class="btn btn-success">
            Add New Package
        </a>
    </div>
</div>

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
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Package Name</th>
                        <th>Category</th>
                        <th>Location</th>
                        <th>Price</th>
                        <th>Slots</th>
                        <th width="210">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($packages as $package)
                        <tr>
                            <td>{{ $package->id }}</td>

                            <td class="fw-semibold">
                                {{ $package->title }}
                            </td>

                            <td>{{ $package->category ?? 'N/A' }}</td>

                            <td>{{ $package->location ?? 'N/A' }}</td>

                            <td>₱{{ number_format($package->price ?? 0, 2) }}</td>

                            <td>{{ $package->slots ?? 0 }}</td>

                            <td>
                                <div class="d-flex flex-wrap gap-2">
                                    <a href="{{ route('admin.packages.edit', $package) }}"
                                       class="btn btn-sm btn-warning">
                                        Edit
                                    </a>

                                    <form method="POST"
                                          action="{{ route('admin.packages.destroy', $package) }}"
                                          onsubmit="return confirm('Are you sure you want to delete this package?');">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="btn btn-sm btn-danger">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                No packages found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
