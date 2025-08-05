@extends('layouts.dashboard')

@section('title', 'My Checklist Submissions')

@section('content')
<div class="card shadow-sm border-0 rounded-3">
    <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between p-3 rounded-top-3">
        <h2 class="h5 mb-0 fw-semibold">My Submissions</h2>
        <a href="{{ route('dashboard') }}" class="btn btn-outline-light btn-sm fw-medium">
            <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
        </a>
    </div>

    <div class="card-body p-4">
        @if(session('message'))
            <div class="alert alert-success alert-dismissible fade show rounded-3 border-0" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Checklist Title</th>
                        <th>Status</th>
                        <th>Submitted On</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($submissions as $submission)
                        <tr>
                            <td class="fw-medium">{{ $submission->checklist->title }}</td>
                            <td>
                                <span class="badge bg-{{ $submission->status === 'Completed' ? 'success' : 'secondary' }}">
                                    {{ $submission->status }}
                                </span>
                            </td>
                            <td>{{ $submission->created_at->format('F d, Y') }}</td>
                            <td class="text-center">
                                <a href="{{ route('checklists.results.show', $submission->id) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye me-1"></i> View Results
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">
                                <i class="fas fa-folder-open fa-2x mb-2"></i>
                                <p class="mb-0">No submissions found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
