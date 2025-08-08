@extends('layouts.dashboard')

@section('title', 'View Staff Details')

@section('content')
<div style="max-width: 900px; margin: 0 auto; padding: 2rem; background-color: #ffffff; border-radius: 8px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
    <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e5e7eb; padding-bottom: 1.5rem; margin-bottom: 1.5rem;">
        <div>
            <h2 style="font-weight: 600; font-size: 1.75rem; color: #111827;">{{ $staff->first_name }} {{ $staff->last_name }}</h2>
            <p style="color: #6b7280; margin-top: 0.25rem;">Staff Details</p>
        </div>
        <div>
            <a href="{{ route('staff.index') }}" style="background: #e5e7eb; color: #374151; padding: 0.6rem 1.2rem; border-radius: 5px; text-decoration: none; margin-right: 1rem;">Back to List</a>
            <a href="{{ route('staff.edit', $staff->id) }}" style="background: #159ed5; color: white; padding: 0.6rem 1.2rem; border-radius: 5px; text-decoration: none;">Edit Staff</a>
        </div>
    </div>

    {{-- Personal & Professional Details --}}
    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 2rem; font-size: 0.95rem;">
        <div style="background-color: #f9fafb; padding: 1.5rem; border-radius: 8px;">
            <h3 style="font-weight: 600; font-size: 1.1rem; margin-bottom: 1rem; color: #111827;">Personal Information</h3>
            <div style="display: grid; grid-template-columns: 120px 1fr; gap: 0.75rem;">
                <strong style="color: #4b5563;">Staff No:</strong>
                <span>{{ $staff->staff_number }}</span>
                <strong style="color: #4b5563;">Email:</strong>
                <span>{{ $staff->email }}</span>
                <strong style="color: #4b5563;">Department:</strong>
                <span>{{ $staff->department ?: 'N/A' }}</span>
            </div>
        </div>

        <div style="background-color: #f9fafb; padding: 1.5rem; border-radius: 8px;">
            <h3 style="font-weight: 600; font-size: 1.1rem; margin-bottom: 1rem; color: #111827;">License Information</h3>
            <div style="display: grid; grid-template-columns: 120px 1fr; gap: 0.75rem;">
                <strong style="color: #4b5563;">License #:</strong>
                <span>{{ $staff->license_number ?: 'N/A' }}</span>
                <strong style="color: #4b5563;">Renewal Date:</strong>
                <span>{{ $staff->license_renewal_date ? \Carbon\Carbon::parse($staff->license_renewal_date)->format('d M, Y') : 'N/A' }}</span>
            </div>
            <strong style="color: #4b5563;">Start Date:</strong>
                <span>{{ $staff->start_date ? \Carbon\Carbon::parse($staff->start_date)->format('d M, Y') : 'N/A' }}</span>
                <strong style="color: #4b5563;">Experience:</strong>
                <span>
                    @if($staff->start_date)
                        @php
                            $startDate = \Carbon\Carbon::parse($staff->start_date);
                            $now = \Carbon\Carbon::now();
                            $diff = $startDate->diff($now);
                            $years = $diff->y;
                            $months = $diff->m;
                        @endphp
                        {{ $years > 0 ? $years . ' year' . ($years > 1 ? 's' : '') : '' }}
                        {{ $months > 0 ? ($years > 0 ? ' and ' : '') . $months . ' month' . ($months > 1 ? 's' : '') : '' }}
                        {{ $years == 0 && $months == 0 ? 'Less than a month' : '' }}
                    @else
                        N/A
                    @endif
                </span>



        </div>
    </div>

    {{-- Uploaded Documents --}}
    <div style="margin-top: 2rem; border-top: 1px solid #e5e7eb; padding-top: 1.5rem;">
        <h3 style="font-weight: 600; font-size: 1.25rem; margin-bottom: 1.5rem; color: #111827;">Uploaded Documents</h3>
        @php
            $documents = [
                'License Document' => $staff->license_document,
                'Appointment Letter' => $staff->appointment_letter,
                'CV / Resume' => $staff->cv,
                'Short Course Certificate' => $staff->short_course_certificate,
                'Other Certificate' => $staff->other_certificate,
            ];
        @endphp

        @if (count(array_filter($documents)) > 0)
            <ul style="list-style-type: none; padding: 0; display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 1rem;">
                @foreach ($documents as $label => $file)
                    @if ($file)
                        <li style="background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; padding: 1rem; display: flex; justify-content: space-between; align-items: center;">
                            <span style="font-weight: 500; color: #374151;">{{ $label }}</span>
                            <a href="{{ Storage::url($file) }}" target="_blank" style="background: #159ed5; color: white; padding: 0.4rem 0.8rem; border-radius: 5px; text-decoration: none; font-size: 0.8rem;">
                                View File
                            </a>
                        </li>
                    @endif
                @endforeach
            </ul>
        @else
            <p style="color: #6b7280; text-align: center; background-color: #f9fafb; padding: 2rem; border-radius: 8px;">No documents have been uploaded for this staff member.</p>
        @endif
    </div>
</div>
@endsection
