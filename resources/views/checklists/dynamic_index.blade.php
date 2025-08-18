@extends('layouts.dashboard')

@section('title', 'Dynamic Checklists')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">

            {{-- Success & Error Messages --}}
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="card-title">Generated Checklists</h4>
                        <p class="text-muted">Manage and complete checklists generated from your audits</p>
                    </div>
                    <div>
                        <a href="{{ route('audits.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Create New Checklist
                        </a>
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

                                {{-- Static Checklist Row --}}
                                <tr>
                                    <td>Medical Specialist Program</td>
                                    <td>Medical Specialist Checklist</td>
                                    <td>All Departments</td>
                                    <td>
                                        <span class="badge badge-info bg-info bg-color green">In Progress</span>
                                    </td>
                                    <td>
                                        {{-- <div class="progress" style="height: 20px;">
                                            <div class="progress-bar bg-success" role="progressbar"
                                                 style="width: {{ $staticProgress }}%;" aria-valuenow="{{ $staticProgress }}"
                                                 aria-valuemin="0" aria-valuemax="100">
                                                {{ $staticProgress }}%
                                            </div>
                                        </div> --}}
                                    </td>
                                    <td>No Due Date</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('checklists.medical-specialist.start') }}"
                                               class="btn btn-sm btn-success" title="Start Checklist">
                                                <i class="fas fa-check"></i> Submit
                                            </a>
                                            <a href="{{ route('checklists.medical-specialist.drafts') }}"
                                               class="btn btn-sm btn-info ml-2" title="View Drafts">
                                                <i class="fas fa-file-alt"></i> View Drafts
                                            </a>
                                            @if ($staticSubmissionId)
                                                <a href="{{ route('checklists.medical-specialist.results', ['submission' => $staticSubmissionId]) }}" 
                                                    class="btn btn-sm btn-secondary ml-2" title="View Results">
                                                    <i class="fas fa-chart-pie"></i> View Results
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>

                                {{-- Dynamic Checklist Rows --}}
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
                                                <div class="progress-bar" style="width: {{ $auditChecklist->progress_percentage ?? 0 }}%;">
                                                    {{ $auditChecklist->progress_percentage ?? 0 }}%
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            {{ $auditChecklist->due_date ? $auditChecklist->due_date->format('M d, Y') : 'No due date' }}
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('checklists.edit', $auditChecklist->checklist) }}" 
                                                   class="btn btn-sm btn-primary" title="Edit Checklist">
                                                    <i class="fas fa-pencil-alt"></i> Edit
                                                </a>
                                                <a href="{{ route('checklists.submit', $auditChecklist->checklist->id) }}" 
                                                   class="btn btn-sm btn-success" title="Submit New Checklist">
                                                    <i class="fas fa-check"></i> Submit
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">No dynamic checklists found.</td>
                                    </tr>
                                @endforelse

                            </tbody>
                        </table>
                    </div>

                    {{-- Info Section --}}
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
