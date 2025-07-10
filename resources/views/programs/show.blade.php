@extends('layouts.dashboard')

@section('title', 'Program Details')

@section('content')
    <div class="container-fluid py-4">
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between p-3 rounded-top-3">
                <h2 class="h5 mb-0 fw-semibold">{{ $program->program_name }}</h2>
                <div>
                    <a href="{{ route('programs.index') }}" class="btn btn-outline-light btn-sm fw-medium me-2">
                        <i class="fas fa-list me-2"></i>View All Programs
                    </a>
                    <a href="{{ route('programs.edit', $program) }}" class="btn btn-light btn-sm fw-medium">
                        <i class="fas fa-edit me-2"></i>Edit Program
                    </a>
                </div>
            </div>

            <div class="card-body p-4">
                <h5 class="mb-3 fw-semibold text-primary">Program Classification</h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label class="form-label text-secondary fw-medium">Subdivision</label>
                        <p class="form-control-plaintext bg-light p-2 rounded-3">{{ $program->school->subdivision->name ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-secondary fw-medium">School / Category</label>
                        <p class="form-control-plaintext bg-light p-2 rounded-3">{{ $program->school->name ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-secondary fw-medium">Program Name</label>
                        <p class="form-control-plaintext bg-light p-2 rounded-3">{{ $program->program_name }}</p>
                    </div>
                </div>

                <hr class="my-4 border-light">

                <h5 class="mb-3 fw-semibold text-primary">Program Details</h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label class="form-label text-secondary fw-medium">Program Abbreviation</label>
                        <p class="form-control-plaintext bg-light p-2 rounded-3">{{ $program->program_abbr ?? 'Not specified' }}</p>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-secondary fw-medium">Head of Program</label>
                        <p class="form-control-plaintext bg-light p-2 rounded-3">{{ $program->facultyMember ? $program->facultyMember->first_name . ' ' . $program->facultyMember->last_name : 'Not Assigned' }}</p>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-secondary fw-medium">License Date</label>
                        <p class="form-control-plaintext bg-light p-2 rounded-3">{{ $program->license_date ? \Carbon\Carbon::parse($program->license_date)->format('F j, Y') : 'Not specified' }}</p>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-secondary fw-medium">Appointment Date</label>
                        <p class="form-control-plaintext bg-light p-2 rounded-3">{{ $program->appointment_date ? \Carbon\Carbon::parse($program->appointment_date)->format('F j, Y') : 'Not specified' }}</p>
                    </div>
                </div>

                <hr class="my-4 border-light">

                <h5 class="mb-3 fw-semibold text-primary">Uploaded Documents</h5>
                @if($program->documents->isNotEmpty())
                    <ul class="list-group">
                        @foreach($program->documents as $document)
                            <li class="list-group-item d-flex justify-content-between align-items-center rounded-3 mb-2 border">
                        <span class="fw-medium text-secondary">
                            <i class="fas fa-file-alt me-2 text-primary"></i>
                            {{ $document->document_name }}
                        </span>
                                <a href="{{ Storage::url($document->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-download me-1"></i> View/Download
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="alert alert-light text-center" role="alert">
                        No documents have been uploaded for this program.
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
