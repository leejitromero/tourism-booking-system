@extends('layouts.app')
@section('title','Users')
@section('content')
<h2 class="fw-bold mb-4">Users</h2><div class="card card-body table-responsive"><table class="table"><thead><tr><th>Name</th><th>Email</th><th>Role</th><th>Joined</th></tr></thead><tbody>@foreach($users as $user)<tr><td>{{ $user->name }}</td><td>{{ $user->email }}</td><td><span class="badge {{ $user->is_admin ? 'text-bg-primary' : 'text-bg-secondary' }}">{{ $user->is_admin ? 'Admin' : 'Tourist' }}</span></td><td>{{ $user->created_at?->format('M d, Y') }}</td></tr>@endforeach</tbody></table></div>
@endsection
