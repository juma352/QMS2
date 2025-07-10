@extends('layouts.dashboard')

@section('title', 'Edit Program')

@section('content')
    <div class="container-fluid py-4">
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between p-3">
                <h2 class="h5 mb-0 fw-semibold">Edit Program</h2>
                <a href="{{ route('programs.index') }}" class="btn btn-outline-light btn-sm fw-medium">
                    <i class="fas fa-arrow-left me-2"></i>Back to List
                </a>
            </div>

            <div class="card-body p-4">
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show rounded-3 border-0" role="alert">
                        <h6 class="alert-heading fw-semibold mb-2"><i class="fas fa-exclamation-circle me-2"></i>Errors</h6>
                        <ul class="mb-0 list-unstyled">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form method="POST" action="{{ route('programs.update', $program->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- Read-only classification info --}}
                    <h5 class="mb-3 fw-semibold text-primary">Program Classification</h5>
                    <div class="classification-info">
                        <p><strong>Subdivision:</strong> {{ $program->school?->subdivision?->name ?? 'N/A' }}</p>
                        <p><strong>School:</strong> {{ $program->school?->name ?? 'N/A' }}</p>
                        <p><strong>Program:</strong> {{ $program->program_name }}</p>
                    </div>

                    <hr class="my-4">

                    <h5 class="mb-3 fw-semibold text-primary">Editable Details</h5>

                    {{-- Editable fields --}}
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="program_abbr" class="form-label fw-medium text-secondary">Program Abbreviation</label>
                            <input type="text" name="program_abbr" id="program_abbr" class="form-control rounded-3" value="{{ old('program_abbr', $program->program_abbr) }}">
                        </div>
                        <div class="col-md-6">
                            <label for="faculty_member_id" class="form-label fw-medium text-secondary">Head of Program</label>
                            {{-- Use a dropdown for faculty member --}}
                            <select name="faculty_member_id" id="faculty_member_id" class="form-select rounded-3">
                                <option value="">Select Head of Program</option>
                                @foreach($staff as $member)
                                    <option value="{{ $member->id }}" {{ old('faculty_member_id', $program->faculty_member_id) == $member->id ? 'selected' : '' }}>
                                        {{ $member->first_name }} {{ $member->last_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="role" class="form-label fw-medium text-secondary">Role</label>
                            <select name="role" id="role" class="form-select rounded-3">
                                <option value="">Select Role</option>
                                @php
                                    $roles = ['Coordinator', 'Lecturer', 'Program Director', 'Department Head'];
                                @endphp
                                @foreach($roles as $role)
                                    <option value="{{ $role }}" {{ old('role', $program->role) == $role ? 'selected' : '' }}>{{ $role }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="license_renewal_date" class="form-label fw-medium text-secondary">License Renewal Date</label>
                            <input type="date" name="license_renewal_date" id="license_renewal_date" class="form-control rounded-3" value="{{ old('license_renewal_date', $program->license_renewal_date) }}">
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="next_approval_date" class="form-label fw-medium text-secondary">Next Approval Date</label>
                            <input type="date" name="next_approval_date" id="next_approval_date" class="form-control rounded-3" value="{{ old('next_approval_date', $program->next_approval_date) }}">
                        </div>
                    </div>

                    <hr class="my-4">

                    {{-- Document Uploads --}}
                    <h5 class="mb-3 fw-semibold text-primary">Update Documents</h5>
                    {{-- Loop through documents for cleaner code --}}
                    @php
                        $documents = [
                            'license_document' => 'License Document',
                            'approval_document' => 'Approval Document',
                            'certificate' => 'Certificate',
                            'other_documents' => 'Other Documents'
                        ];
                    @endphp

                    <div class="row g-3">
                        @foreach($documents as $field => $label)
                            <div class="col-md-6 mb-3">
                                <label for="{{ $field }}" class="form-label fw-medium text-secondary">{{ $label }}</label>
                                <input type="file" name="{{ $field }}" id="{{ $field }}" class="form-control rounded-3">
                                @if ($program->$field)
                                    <small class="text-muted mt-1 d-block">
                                        Current file: <a href="{{ Illuminate\Support\Facades\Storage::url($program->$field) }}" target="_blank">View Document</a>
                                    </small>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4 pt-4 border-top">
                        <a href="{{ route('programs.index') }}" class="btn btn-outline-secondary rounded-3 px-4 fw-medium">Cancel</a>
                        <button type="submit" class="btn btn-primary rounded-3 px-4 fw-medium">
                            <i class="fas fa-save me-2"></i>Update Program
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        .card { border-radius: 0.75rem; }
        .card-header { border-bottom: none; border-radius: 0.75rem 0.75rem 0 0; }
        .form-label { font-size: 0.875rem; }
        .form-control, .form-select { border-radius: 0.5rem; }
        .classification-info {
            background-color: #f8f9fa;
            border-radius: 0.5rem;
            padding: 1rem;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 0.5rem 1rem;
        }
        .classification-info p { margin-bottom: 0; }
    </style>
@endsection
