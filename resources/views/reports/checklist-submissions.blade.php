@extends('layouts.dashboard')

@section('title', 'Checklist Submissions Report')

@include('reports.partials.print-styles')

@section('content')
<div class="printable-area">
    @include('reports.partials.report-header', ['title' => 'Checklist Submissions Report'])

    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Checklist Title</th>
                            <th>Submitted By</th>
                            <th>Status</th>
                            <th>Date Submitted</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($submissions as $submission)
                            <tr>
                                <td>{{ $submission->checklist->title ?? 'N/A' }}</td>
                                <td>{{ $submission->user->name ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge text-capitalize bg-{{ $submission->status == 'complete' ? 'success' : 'secondary' }}">
                                        {{ $submission->status }}
                                    </span>
                                </td>
                                <td>{{ $submission->created_at->format('d M, Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">
                                    No checklist submissions found for the selected criteria.
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
