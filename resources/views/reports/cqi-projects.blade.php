@extends('layouts.dashboard')

@section('title', 'CQI Projects Report')

@include('reports.partials.print-styles')

@section('content')
<div class="printable-area">
    @include('reports.partials.report-header', ['title' => 'CQI Projects Report'])

    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Project Name</th>
                            <th>Project Leader</th>
                            <th>Methodology</th>
                            <th>Status</th>
                            <th>Progress</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($cqiProjects as $project)
                            <tr>
                                <td>{{ $project->project_name }}</td>
                                <td>{{ $project->project_leader ?? 'N/A' }}</td>
                                <td>{{ $project->methodology }}</td>
                                <td>
                                    <span class="badge text-capitalize bg-info">{{ $project->status }}</span>
                                </td>
                                <td>{{ $project->initial_progress }}%</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    No CQI projects found for the selected criteria.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="text-center mt-4">
    <button onclick="window.print()" class="btn btn-primary"><i class="fas fa-print me-2"></i>Print Report</button>
</div>
@endsection
