@extends('layouts.dashboard')

@section('title', 'Create User')

@section('content')
<div class="card shadow-sm border-0 rounded-3 mx-auto" style="max-width: 600px;">
    <div class="card-header bg-primary text-white p-3">
        <h2 class="h5 mb-0 fw-semibold">Create New User</h2>
    </div>
    <div class="card-body p-4">
        <form method="POST" action="{{ route('users.store') }}">
            @csrf

            <div class="mb-3">
                <label for="staff_id" class="form-label">Staff Member <span class="text-danger">*</span></label>
                <select id="staff_id" name="staff_id" class="form-select" required>
                    <option value="" disabled selected>Select a staff member</option>
                    @foreach ($staff as $staffMember)
                        <option value="{{ $staffMember->id }}">{{ $staffMember->name }} ({{ $staffMember->email }})</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                <input id="password" class="form-control" type="password" name="password" required autocomplete="new-password" />
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                <input id="password_confirmation" class="form-control" type="password" name="password_confirmation" required />
            </div>
            
            <div class="mb-4">
                <label for="role" class="form-label">User Role <span class="text-danger">*</span></label>
                <select name="role" id="role" class="form-select" required>
                    <option value="user" selected>User</option>
                    <option value="admin">Admin</option>
                    <option value="staff">Staff</option>
                    <option value="hod">HOD</option>
                </select>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Create User</button>
            </div>
        </form>
    </div>
</div>
@endsection
