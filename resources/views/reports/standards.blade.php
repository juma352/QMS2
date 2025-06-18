@extends('layouts.dashboard')

@section('title', 'Standards Report')

@include('reports.partials.print-styles')

@section('content')
<div class="printable-area">
    @include('reports.partials.report-header', ['title' => 'Standards Report'])

    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Standard Name</th>
                            <th>Version</th>
                            <th>Issuing Authority</th>
                            <th>Date of Issue</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($standards as $standard)
                            <tr>
                                <td>{{ $standard->standard_name }}</td>
                                <td>{{ $standard->version }}</td>
                                <td>{{ $standard->issuing_authority }}</td>
                                <td>{{ $standard->date_of_issue ? \Carbon\Carbon::parse($standard->date_of_issue)->format('d M, Y') : 'N/A' }}</td>
                                <td>{{ Str::limit($standard->description, 70) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    No standards found for the selected criteria.
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
