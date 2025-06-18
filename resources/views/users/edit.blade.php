@extends('layouts.dashboard')

@section('title', 'Edit User Role')

@section('content')
<div class="card shadow-sm border-0 rounded-3 mx-auto" style="max-width: 600px;">
    <div class="card-header bg-primary text-white p-3">
        <h2 class="h5 mb-0 fw-semibold">Edit Role for: {{ $user->name }}</h2>
    </div>
    <div class="card-body p-4">
        <form method="POST" action="{{ route('users.update', $user->id) }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" id="name" class="form-control" value="{{ $user->name }}" disabled readonly>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" id="email" class="form-control" value="{{ $user->email }}" disabled readonly>
            </div>

            <div class="mb-4">
                <label for="role" class="form-label">User Role <span class="text-danger">*</span></label>
                <select name="role" id="role" class="form-select" required>
                    @php
                        $roles = ['admin', 'user'];
                    @endphp
                    @foreach($roles as $role)
                        <option value="{{ $role }}" {{ old('role', $user->role) == $role ? 'selected' : '' }}>
                            {{ ucfirst($role) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Role</button>
            </div>
        </form>
    </div>
</div>
@endsection
