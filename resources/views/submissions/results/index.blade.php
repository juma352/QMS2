@extends('layouts.dashboard')

@section('title', 'Submission Results')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h1 class="h3 mb-4">Submission Results</h1>
            
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">All Submissions</h5>
                </div>
                <div class="card-body">
                    @if($submissions->isEmpty())
                        <div class="text-center py-5">
                            <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No submissions yet</h5>
                            <p class="text-muted">Submissions will appear here once checklists are completed.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Checklist</th>
                                        <th>Department</th>
                                        <th>Submitted By</th>
                                        <th>Submitted At</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($submissions as $submission)
                                        <tr>
                                            <td>{{ $submission->id }}</td>
                                            <td>{{ $submission->checklist->title ?? 'N/A' }}</td>
                                            <td>{{ $submission->department_name }}</td>
                                            <td>{{ $submission->user->name ?? 'Unknown' }}</td>
                                            <td>{{ $submission->created_at ? $submission->created_at->format('M d, Y H:i') : 'N/A' }}</td>
                                            <td>
                                                <span class="badge badge-{{ $submission->status == 'completed' ? 'success' : 'warning' }}">
                                                    {{ ucfirst($submission->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('submissions.show', $submission) }}" 
                                                   class="btn btn-sm btn-primary">
                                                    <i class="fas fa-eye"></i> View
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-flex justify-content-center">
                            {{ $submissions->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
