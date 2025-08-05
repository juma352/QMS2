@extends('layouts.dashboard')

@section('title', 'Departments That Submitted Checklists')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
        <h2 class="h5 mb-0">Departments</h2>
    </div>
    <div class="card-body">
        @if($departments->count() > 0)
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Department</th>
                        <th>Submissions</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($departments as $dept)
                        <tr>
                            <td>{{ $dept->department_name ?? 'Unknown Department' }}</td>
                            <td>{{ $dept->submission_count }}</td>
                            <td class="text-center">
                                @if($dept->department_name)
                                    <a href="{{ route('checklists.byDepartment', ['department' => $dept->department_name]) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye me-1"></i> View Checklists
                                    </a>
                                @else
                                    <span class="btn btn-sm btn-secondary disabled">
                                        <i class="fas fa-exclamation-circle me-1"></i> No Department Name
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="alert alert-info">
                <p>No departments have submitted checklists yet.</p>
            </div>
        @endif
    </div>
</div>
@endsection
