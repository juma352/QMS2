@extends('layouts.dashboard')

@section('title', 'Program Details')

@section('content')
    <div class="container-fluid py-4">
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between p-3">
                {{-- Card Header: Title and Back Button --}}
                <h2 class="h5 mb-0 fw-semibold">
                    Program: {{ $program->program_name }}
                </h2>
                <a href="{{ route('programs.index') }}" class="btn btn-outline-light btn-sm fw-medium">
                    <i class="fas fa-arrow-left me-2"></i>Back to List
                </a>
            </div>

            <div class="card-body p-4">
                {{-- Section 1: Core Program Information --}}
                <h5 class="mb-3 fw-semibold text-primary">Program Information</h5>
                <div class="details-grid">
                    <div>
                        <strong class="text-secondary">Program Name</strong>
                        <p>{{ $program->program_name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <strong class="text-secondary">Abbreviation</strong>
                        <p>{{ $program->program_abbr ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <strong class="text-secondary">Subdivision</strong>
                        {{-- Correctly access the subdivision name through the school relationship --}}
                        <p>{{ $program->school?->subdivision?->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <strong class="text-secondary">School / Category</strong>
                        <p>{{ $program->school?->name ?? 'N/A' }}</p>
                    </div>
                </div>

                <hr class="my-4">

                {{-- Section 2: Staff and Role --}}
                <h5 class="mb-3 fw-semibold text-primary">Personnel</h5>
                <div class="details-grid">
                    <div>
                        <strong class="text-secondary">Head of Program</strong>
                        {{-- Correctly access the faculty member's full name --}}
                        <p>{{ $program->facultyMember ? $program->facultyMember->first_name . ' ' . $program->facultyMember->last_name : 'Not Assigned' }}</p>
                    </div>
                    <div>
                        <strong class="text-secondary">Role</strong>
                        <p>{{ $program->role ?? 'N/A' }}</p>
                    </div>
                </div>

                <hr class="my-4">

                {{-- Section 3: Dates and Deadlines --}}
                <h5 class="mb-3 fw-semibold text-primary">Important Dates</h5>
                <div class="details-grid">
                    <div>
                        <strong class="text-secondary">License Renewal Date</strong>
                        {{-- Format the date for readability and handle null values --}}
                        <p>{{ $program->license_renewal_date ? \Carbon\Carbon::parse($program->license_renewal_date)->format('F j, Y') : 'N/A' }}</p>
                    </div>
                    <div>
                        <strong class="text-secondary">Next Approval Date</strong>
                        <p>{{ $program->next_approval_date ? \Carbon\Carbon::parse($program->next_approval_date)->format('F j, Y') : 'N/A' }}</p>
                    </div>
                </div>

                <hr class="my-4">

                {{-- Section 4: Attached Documents --}}
                <h5 class="mb-3 fw-semibold text-primary">Uploaded Documents</h5>
                <div class="list-group">
                    @php
                        $documents = [
                            'License Document' => $program->license_document,
                            'Approval Document' => $program->approval_document,
                            'Certificate' => $program->certificate,
                            'Other Documents' => $program->other_documents,
                        ];
                    @endphp

                    @foreach ($documents as $label => $file)
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span>{{ $label }}</span>
                            @if ($file)
                                {{-- Use Storage::url() for files in the public disk --}}
                                <a href="{{ Illuminate\Support\Facades\Storage::url($file) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye me-1"></i> View
                                </a>
                            @else
                                <span class="badge bg-secondary">Not Uploaded</span>
                            @endif
                        </div>
                    @endforeach
                </div>

                {{-- Action Buttons: Edit and Delete --}}
                <div class="d-flex justify-content-end gap-2 mt-4 pt-4 border-top">
                    <a href="{{ route('programs.edit', $program->id) }}" class="btn btn-info text-white rounded-3 px-4 fw-medium">
                        <i class="fas fa-edit me-2"></i>Edit
                    </a>
                    <form action="{{ route('programs.destroy', $program->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this program?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger rounded-3 px-4 fw-medium">
                            <i class="fas fa-trash me-2"></i>Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        /* A simple two-column grid for details */
        .details-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
        }
        .details-grid p {
            margin-bottom: 0;
            font-size: 0.95rem;
        }
        .details-grid strong {
            display: block;
            margin-bottom: 0.25rem;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .card {
            border-radius: 0.75rem;
        }
        .card-header {
            border-bottom: none;
            border-radius: 0.75rem 0.75rem 0 0;
        }
        .list-group-item {
            border-left: none;
            border-right: none;
        }
        .list-group-item:first-child {
            border-top-left-radius: 0.5rem;
            border-top-right-radius: 0.5rem;
        }
        .list-group-item:last-child {
            border-bottom-left-radius: 0.5rem;
            border-bottom-right-radius: 0.5rem;
        }
    </style>
@endsection
