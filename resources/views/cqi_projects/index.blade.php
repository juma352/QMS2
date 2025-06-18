@extends('layouts.dashboard')

@section('title', 'CQI Projects')

@section('content')
<section class="cqi-projects-list">
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between p-3 rounded-top-3">
            <h2 class="h5 mb-0 fw-semibold">All CQI Projects</h2>
            {{-- CORRECTED ROUTE --}}
            <a href="{{ route('cqi_projects.create') }}" class="btn btn-outline-light btn-sm fw-medium">
                <i class="fas fa-plus-circle me-2"></i>Add New Project
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
                            <th scope="col">Project Name</th>
                            <th scope="col">Project Leader</th>
                            <th scope="col">Methodology</th>
                            <th scope="col">Status</th>
                            <th scope="col" class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($projects as $project)
                            <tr>
                                <td>{{ $project->project_name }}</td>
                                <td>{{ $project->project_leader ?? 'N/A' }}</td>
                                <td>{{ $project->methodology }}</td>
                                <td><span class="badge bg-secondary">{{ $project->status }}</span></td>
                                <td class="text-center">
                                    <div class="d-inline-flex gap-2">
                                        {{-- CORRECTED ROUTES --}}
                                        <a href="{{ route('cqi_projects.show', $project->id) }}" class="btn btn-outline-info btn-sm" title="View"><i class="fas fa-eye"></i></a>
                                        <a href="{{ route('cqi_projects.edit', $project->id) }}" class="btn btn-outline-warning btn-sm" title="Edit"><i class="fas fa-pencil-alt"></i></a>
                                        <form action="{{ route('cqi_projects.destroy', $project->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this project?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm" title="Delete"><i class="fas fa-trash-alt"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    <i class="fas fa-folder-open fa-2x mb-2"></i>
                                    <p class="mb-0">No CQI Projects found. Please add a new one.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($projects->hasPages())
                <div class="mt-4 d-flex justify-content-center">
                    {{ $projects->links() }}
                </div>
            @endif
        </div>
    </div>
</section>
@endsection
