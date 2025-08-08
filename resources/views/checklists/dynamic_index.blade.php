@extends('layouts.dashboard')

@section('title', 'Dynamic Checklists')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title">Generated Dynamic Checklists</h4>
                            <p class="text-muted">Manage and complete checklists generated from your audits</p>
                        </div>
                        <div>
                            <a href="{{ route('audits.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Create New Checklist
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Audit Name</th>
                                    <th>Checklist Title</th>
                                    <th>Department</th>
                                    <th>Status</th>
                                    <th>Progress</th>
                                    <th>Due Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($dynamicChecklists as $auditChecklist)
                                <tr>
                                    <td>{{ $auditChecklist->audit->audit_name }}</td>
                                    <td>{{ $auditChecklist->checklist->title }}</td>
                                    <td>{{ $auditChecklist->department_name ?? 'All Departments' }}</td>
                                    <td>
                                        <span class="badge badge-{{ $auditChecklist->status == 'completed' ? 'success' : 'warning' }}">
                                            {{ ucfirst($auditChecklist->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar" style="width: {{ $auditChecklist->progress_percentage ?? 0 }}%">
                                                {{ $auditChecklist->progress_percentage ?? 0 }}%
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $auditChecklist->due_date ? $auditChecklist->due_date->format('M d, Y') : 'No due date' }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('checklists.edit', $auditChecklist->checklist) }}" 
                                               class="btn btn-sm btn-primary" title="Edit Checklist">
                                                <i class="fas fa-pencil-alt"></i>Edit
                                            </a>
                                            
                                            <!-- Submit New Checklist Button - Always Available -->
                                            <a href="{{ route('checklists.submit', $auditChecklist->checklist->id) }}" 
                                               class="btn btn-sm btn-success" title="Submit New Checklist">
                                                <i class="fas fa-check"></i> Submit New Checklist
                                            </a>
                                            
                                            <!-- Additional Actions Dropdown -->
                                            <div class="btn-group ml-1">
                                                <button type="button" class="btn btn-sm btn-secondary dropdown-toggle" 
                                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="{{ route('checklists.dynamic_show_complete', $auditChecklist->checklist->id) }}">
                                                        <i class="fas fa-eye"></i> View Details
                                                    </a>
                                                    <a class="dropdown-item" href="{{ route('submissions.index') }}">
                                                        <i class="fas fa-history"></i> View Submissions
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">No checklists found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Additional Info Section -->
                    <div class="mt-4">
                        <div class="alert alert-info">
                            <h5><i class="fas fa-info-circle"></i> Submit Multiple Times</h5>
                            <p>You can submit each generated checklist as many times as needed. Each submission creates a new record with your current responses.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection