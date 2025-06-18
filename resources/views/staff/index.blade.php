@extends('layouts.dashboard')

@section('title', 'Education Staff')

@section('content')
<div class="card shadow-sm border-0 rounded-3">
    <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between p-3 rounded-top-3">
        <h2 class="h5 mb-0 fw-semibold">Education Staff</h2>
        <a href="{{ route('staff.create') }}" class="btn btn-outline-light btn-sm fw-medium">
            <i class="fas fa-plus-circle me-2"></i>Add New Staff
        </a>
    </div>

    <div class="card-body p-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3 border-0" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Staff No.</th>
                        <th scope="col">Email</th>
                        <th scope="col">Department</th>
                        <th scope="col">License #</th>
                        <th scope="col">Renewal</th>
                        <th scope="col" class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- CORRECTED: The loop now uses the '$staff' variable provided by the controller --}}
                    @forelse ($staff as $person)
                        <tr>
                            <td>{{ $person->id }}</td>
                            <td>{{ $person->first_name }} {{ $person->last_name }}</td>
                            <td>{{ $person->staff_number }}</td>
                            <td>{{ $person->email }}</td>
                            <td>{{ $person->department ?? 'N/A' }}</td>
                            <td>{{ $person->license_number ?? 'N/A' }}</td>
                            <td>{{ $person->license_renewal_date ? \Carbon\Carbon::parse($person->license_renewal_date)->format('d M, Y') : 'N/A' }}</td>
                            <td class="text-center">
                                <div class="d-inline-flex gap-2">
                                    <a href="{{ route('staff.show', $person->id) }}" class="btn btn-outline-info btn-sm" title="View"><i class="fas fa-eye"></i></a>
                                    <a href="{{ route('staff.edit', $person->id) }}" class="btn btn-outline-warning btn-sm" title="Edit"><i class="fas fa-pencil-alt"></i></a>
                                    <form action="{{ route('staff.destroy', $person->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this staff member?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm" title="Delete"><i class="fas fa-trash-alt"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="fas fa-folder-open fa-2x mb-2"></i>
                                <p class="mb-0">No staff records found. Please add a new one.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
