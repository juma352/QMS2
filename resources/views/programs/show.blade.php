@extends('layouts.dashboard')

@section('title', 'Program Details')

@section('content')
<h2 style="font-weight: 600;">Program Details</h2>

<a href="{{ route('programs.index') }}" style="text-decoration: none; color: #159ed5;">← Back to list</a>

<div style="margin-top: 1.5rem; padding: 1.5rem; background: white; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.05); max-width: 800px;">
    <table style="width: 100%; font-size: 0.95rem;">
        <tr><td><strong>Name</strong></td><td>{{ $program->program_name }}</td></tr>
        <tr><td><strong>Abbreviation</strong></td><td>{{ $program->program_abbr }}</td></tr>
        <tr><td><strong>Subdivision</strong></td><td>{{ $program->subdivision }}</td></tr>
        <tr><td><strong>Faculty Member</strong></td><td>{{ $program->faculty_member }}</td></tr>
        <tr><td><strong>Role</strong></td><td>{{ $program->role }}</td></tr>
        <tr><td><strong>License Renewal Date</strong></td><td>{{ $program->license_renewal_date }}</td></tr>
        <tr><td><strong>Next Approval Date</strong></td><td>{{ $program->next_approval_date }}</td></tr>
        <tr><td><strong>License Document</strong></td><td>
            @if ($program->license_document)
                <a href="{{ asset($program->license_document) }}" target="_blank">View</a>
            @endif
        </td></tr>
        <tr><td><strong>Approval Document</strong></td><td>
            @if ($program->approval_document)
                <a href="{{ asset($program->approval_document) }}" target="_blank">View</a>
            @endif
        </td></tr>
        <tr><td><strong>Certificate</strong></td><td>
            @if ($program->certificate)
                <a href="{{ asset($program->certificate) }}" target="_blank">View</a>
            @endif
        </td></tr>
        <tr><td><strong>Other Documents</strong></td><td>
            @if ($program->other_documents)
                <a href="{{ asset($program->other_documents) }}" target="_blank">View</a>
            @endif
        </td></tr>
    </table>
</div>
@endsection
