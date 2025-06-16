@extends('layouts.dashboard')

@section('title', 'Audit Report')

@section('styles')
<style>
:root {
    --primary: #7c3aed;
    --secondary: #06b6d4;
    --accent: #ea580c;
    --text-dark: #0f172a;
    --text-light: #475569;
    --bg: #eff6ff;
    --radius: 0.8rem;
    --transition: all 0.3s ease;
}
.report-container {
    padding: 2rem;
    background: var(--bg);
    min-height: calc(100vh - 65px - 60px);
}
.report-header {
    background: linear-gradient(135deg, var(--primary), #c084fc);
    padding: 2rem;
    border-radius: var(--radius);
    color: #ffffff;
    margin-bottom: 2rem;
}
.table {
    background: #ffffff;
    border-radius: var(--radius);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}
.table th {
    background: var(--primary);
    color: #ffffff;
}
.table td {
    vertical-align: middle;
}
.no-data {
    text-align: center;
    color: var(--text-light);
    padding: 2rem;
}
</style>
@endsection

@section('content')
<div class="report-container">
    <div class="report-header">
        <h1>Audit Report</h1>
        <p>Summary of Internal and External Audits</p>
    </div>

    <h2 class="h4 mb-3">Internal Audits</h2>
    @if($internalAudits->isNotEmpty())
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Number</th>
                    <th>Standard</th>
                    <th>Date Conducted</th>
                    <th>Status</th>
                    <th>Findings</th>
                </tr>
            </thead>
            <tbody>
                @foreach($internalAudits as $audit)
                <tr>
                    <td>{{ $audit->audit_name }}</td>
                    <td>{{ $audit->audit_number }}</td>
                    <td>{{ $audit->standard->standard_name ?? 'N/A' }}</td>
                    <td>{{ $audit->date_conducted->format('Y-m-d') }}</td>
                    <td>{{ $audit->status }}</td>
                    <td>{{ Str::limit($audit->findings, 50) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="no-data">
        <p>No internal audits found for the selected date range.</p>
    </div>
    @endif

    <h2 class="h4 mb-3 mt-5">External Audits</h2>
    @if($externalAudits->isNotEmpty())
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Number</th>
                    <th>Standard</th>
                    <th>Date Conducted</th>
                    <th>Status</th>
                    <th>Findings</th>
                </tr>
            </thead>
            <tbody>
                @foreach($externalAudits as $audit)
                <tr>
                    <td>{{ $audit->audit_name }}</td>
                    <td>{{ $audit->audit_number }}</td>
                    <td>{{ $audit->standard->standard_name ?? 'N/A' }}</td>
                    <td>{{ $audit->date_conducted->format('Y-m-d') }}</td>
                    <td>{{ $audit->status }}</td>
                    <td>{{ Str::limit($audit->findings, 50) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="no-data">
        <p>No external audits found for the selected date range.</p>
    </div>
    @endif
</div>
@endsection