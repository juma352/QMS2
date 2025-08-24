@extends('layouts.dashboard')

@section('title', 'User Management')

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between p-3 rounded-top-3">
            <h2 class="h5 mb-0 fw-semibold">Manage Users</h2>
            <a href="{{ route('users.create') }}" class="btn btn-outline-light btn-sm fw-medium">
                <i class="fas fa-plus-circle me-2"></i>Add New User
            </a>
        </div>

        <div class="card-body p-4">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-3 border-0" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Registered On</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <td>
                                    @php
                                        $roleClass = '';
                                        switch ($user->role) {
                                            case 'admin':
                                                $roleClass = 'bg-success';
                                                break;
                                            case 'hod':
                                                $roleClass = 'bg-primary';
                                                break;
                                            case 'staff':
                                                $roleClass = 'bg-info';
                                                break;
                                            default:
                                                $roleClass = 'bg-secondary';
                                        }
                                    @endphp
                                    <span class="badge text-capitalize {{ $roleClass }}">
                                        {{ $user->role }}
                                    </span>
                                </td>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $user->is_active ? 'success' : 'danger' }}">
                                        {{ $user->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>{{ $user->created_at->format('d M, Y') }}</td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center flex-wrap gap-2">
                                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-outline-warning btn-sm">
                                            <i class="fas fa-pencil-alt me-1"></i> Edit Role
                                        </a>

                                        <form action="{{ route('users.reset-password', $user->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-info btn-sm" onclick="return confirm('Reset password for {{ $user->name }}?')">
                                                <i class="fas fa-redo-alt me-1"></i> Reset Password
                                            </button>
                                        </form>

                                        @if($user->is_active)
                                            <form action="{{ route('users.deactivate', $user->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Deactivate {{ $user->name }}?')">
                                                    <i class="fas fa-user-slash me-1"></i> Deactivate
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('users.activate', $user->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-success btn-sm" onclick="return confirm('Activate {{ $user->name }}?')">
                                                    <i class="fas fa-user-check me-1"></i> Activate
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">No users found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($users->hasPages())
                <div class="mt-4 d-flex justify-content-center">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
