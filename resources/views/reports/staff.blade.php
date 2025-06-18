@extends('layouts.dashboard')

@section('title', 'Staff Report')

@include('reports.partials.print-styles')

@section('content')
<div class="printable-area">
    @include('reports.partials.report-header', ['title' => 'Education Staff Report'])

    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Staff Number</th>
                            <th>Email</th>
                            <th>Department</th>
                            <th>License Renewal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($staff as $person)
                            <tr>
                                <td>{{ $person->first_name }} {{ $person->last_name }}</td>
                                <td>{{ $person->staff_number }}</td>
                                <td>{{ $person->email }}</td>
                                <td>{{ $person->department ?? 'N/A' }}</td>
                                <td>{{ $person->license_renewal_date ? \Carbon\Carbon::parse($person->license_renewal_date)->format('d M, Y') : 'N/A' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    No staff records found for the selected criteria.
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
