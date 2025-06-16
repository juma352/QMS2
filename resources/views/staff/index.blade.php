@extends('layouts.dashboard')

@section('title', 'Education Staff')

@section('content')
<h2 style="font-weight: 600;">Education Staff</h2>

@if (session('success'))
    <div style="color: green; margin-bottom: 1rem;">{{ session('success') }}</div>
@endif

<a href="{{ route('staff.create') }}" style="background: #159ed5; color: white; padding: 0.5rem 1rem; border-radius: 5px; text-decoration: none; font-size: 0.9rem;">
    <i class="fas fa-plus"></i> Add New Staff
</a>

<table style="width: 100%; margin-top: 1rem; border-collapse: collapse; font-size: 0.9rem;">
    <thead style="background: #159ed5; color: white;">
        <tr>
            <th style="padding: 0.6rem;">#</th>
            <th style="padding: 0.6rem;">Name</th>
            <th style="padding: 0.6rem;">Staff No.</th>
            <th style="padding: 0.6rem;">Email</th>
            <th style="padding: 0.6rem;">Department</th>
            <th style="padding: 0.6rem;">License #</th>
            <th style="padding: 0.6rem;">Renewal</th>
            <th style="padding: 0.6rem;">Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($staff as $person)
            <tr style="background: white; border-bottom: 1px solid #eee;">
                <td style="padding: 0.6rem;">{{ $person->id }}</td>
                <td style="padding: 0.6rem;">{{ $person->first_name }} {{ $person->last_name }}</td>
                <td style="padding: 0.6rem;">{{ $person->staff_number }}</td>
                <td style="padding: 0.6rem;">{{ $person->email }}</td>
                <td style="padding: 0.6rem;">{{ $person->department }}</td>
                <td style="padding: 0.6rem;">{{ $person->license_number }}</td>
                <td style="padding: 0.6rem;">{{ $person->license_renewal_date }}</td>
                <td style="padding: 0.6rem; white-space: nowrap;">
                    <a href="#" class="btn btn-sm" style="color: #159ed5;">View</a>
                    <a href="#" class="btn btn-sm" style="color: #F4A300;">Edit</a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" style="padding: 1rem; text-align: center;">No staff records found.</td>
            </tr>
        @endforelse
    </tbody>
</table>
@endsection
