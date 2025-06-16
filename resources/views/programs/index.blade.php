@extends('layouts.dashboard')

@section('title', 'Programs List')

@section('content')
<h2 style="font-weight: 600;">Programs</h2>

@if (session('success'))
    <div style="color: green; margin-bottom: 1rem;">{{ session('success') }}</div>
@endif

<a href="{{ route('programs.create') }}" style="background: #159ed5; color: white; padding: 0.5rem 1rem; border-radius: 5px; text-decoration: none; font-size: 0.9rem;">
    <i class="fas fa-plus"></i> Add Program
</a>

<table style="width: 100%; margin-top: 1rem; border-collapse: collapse; font-size: 0.9rem;">
    <thead style="background: #159ed5; color: white;">
        <tr>
            <th style="padding: 0.6rem;">#</th>
            <th style="padding: 0.6rem;">Name</th>
            <th style="padding: 0.6rem;">Abbr</th>
            <th style="padding: 0.6rem;">Coordinator</th>
            <th style="padding: 0.6rem;">Subdivision</th>
            <th style="padding: 0.6rem;">Renewal</th>
            <th style="padding: 0.6rem;">Approval</th>
            <th style="padding: 0.6rem;">Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($programs as $program)
            <tr style="background: white; border-bottom: 1px solid #eee;">
                <td style="padding: 0.6rem;">{{ $program->id }}</td>
                <td style="padding: 0.6rem;">{{ $program->program_name }}</td>
                <td style="padding: 0.6rem;">{{ $program->program_abbr }}</td>
                <td style="padding: 0.6rem;">{{ $program->faculty_member }}</td>
                <td style="padding: 0.6rem;">{{ $program->subdivision }}</td>
                <td style="padding: 0.6rem;">
                    {{ $program->license_renewal_date }}
                </td>
                <td style="padding: 0.6rem;">
                    {{ $program->next_approval_date }}
                </td>
                <td style="padding: 0.6rem; white-space: nowrap;">
                    <a href="{{ route('programs.show', $program->id) }}" style="color: #159ed5; text-decoration: none; margin-right: 0.4rem;">
                        <i class="fas fa-eye"></i> View
                    </a>
                    <a href="{{ route('programs.edit', $program->id) }}" style="color: #F4A300; text-decoration: none; margin-right: 0.4rem;">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" style="padding: 1rem; text-align: center;">No programs found.</td>
            </tr>
        @endforelse
    </tbody>
</table>
@endsection
