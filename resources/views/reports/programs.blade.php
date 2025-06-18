@extends('layouts.dashboard')

@section('title', 'Programs Report')

@include('reports.partials.print-styles')

@section('content')
<div class="printable-area">
    @include('reports.partials.report-header', ['title' => 'Programs Report'])

    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Program Name</th>
                            <th>Abbreviation</th>
                            <th>Faculty Member</th>
                            <th>Role</th>
                            <th>Next Approval Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($programs as $program)
                            <tr>
                                <td>{{ $program->program_name }}</td>
                                <td>{{ $program->program_abbr }}</td>
                                <td>{{ $program->faculty_member ?? 'N/A' }}</td>
                                <td>{{ $program->role ?? 'N/A' }}</td>
                                <td>{{ $program->next_approval_date ? \Carbon\Carbon::parse($program->next_approval_date)->format('d M, Y') : 'N/A' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    No programs found for the selected criteria.
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
