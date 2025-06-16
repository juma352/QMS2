@extends('layouts.dashboard')

@section('title', 'Add New Program')

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between p-3 rounded-top-3">
            <h2 class="h5 mb-0 fw-semibold">Add New Program</h2>
            <a href="{{ route('programs.index') }}" class="btn btn-outline-light btn-sm fw-medium">
                <i class="fas fa-list me-2"></i>View All Programs
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

            <form method="POST" action="{{ route('programs.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="program_name" class="form-label fw-medium text-secondary">Program Name <span class="text-danger">*</span></label>
                        <input type="text" name="program_name" id="program_name" class="form-control rounded-3" 
                               placeholder="e.g., Bachelor of Science in Computer Science" 
                               value="{{ old('program_name') }}" required>
                        @error('program_name')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="program_abbr" class="form-label fw-medium text-secondary">Program Abbreviation</label>
                        <input type="text" name="program_abbr" id="program_abbr" class="form-control rounded-3" 
                               placeholder="e.g., BSCS" 
                               value="{{ old('program_abbr') }}">
                        @error('program_abbr')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="subdivision" class="form-label fw-medium text-secondary">Subdivision</label>
                        <select name="subdivision" id="subdivision" class="form-select rounded-3">
                            <option value="" {{ old('subdivision') ? '' : 'selected' }}>Select Subdivision</option>
                            <option value="Health" {{ old('subdivision') == 'Health' ? 'selected' : '' }}>Health</option>
                            <option value="Education" {{ old('subdivision') == 'Education' ? 'selected' : '' }}>Education</option>
                            <option value="Engineering" {{ old('subdivision') == 'Engineering' ? 'selected' : '' }}>Engineering</option>
                            <option value="Business" {{ old('subdivision') == 'Business' ? 'selected' : '' }}>Business</option>
                            <option value="Arts" {{ old('subdivision') == 'Arts' ? 'selected' : '' }}>Arts</option>
                        </select>
                        @error('subdivision')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="faculty_member" class="form-label fw-medium text-secondary">Faculty Member</label>
                        <select name="faculty_member" id="faculty_member" class="form-select rounded-3">
                            <option value="" {{ old('faculty_member') ? '' : 'selected' }}>Select Faculty Member</option>
                            <option value="John Doe" {{ old('faculty_member') == 'John Doe' ? 'selected' : '' }}>John Doe</option>
                            <option value="Jane Smith" {{ old('faculty_member') == 'Jane Smith' ? 'selected' : '' }}>Jane Smith</option>
                            <option value="Robert Johnson" {{ old('faculty_member') == 'Robert Johnson' ? 'selected' : '' }}>Robert Johnson</option>
                            <option value="Emily Davis" {{ old('faculty_member') == 'Emily Davis' ? 'selected' : '' }}>Emily Davis</option>
                        </select>
                        @error('faculty_member')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="role" class="form-label fw-medium text-secondary">Role</label>
                        <select name="role" id="role" class="form-select rounded-3">
                            <option value="" {{ old('role') ? '' : 'selected' }}>Select Role</option>
                            <option value="Coordinator" {{ old('role') == 'Coordinator' ? 'selected' : '' }}>Coordinator</option>
                            <option value="Lecturer" {{ old('role') == 'Lecturer' ? 'selected' : '' }}>Lecturer</option>
                            <option value="Program Director" {{ old('role') == 'Program Director' ? 'selected' : '' }}>Program Director</option>
                            <option value="Department Head" {{ old('role') == 'Department Head' ? 'selected' : '' }}>Department Head</option>
                        </select>
                        @error('role')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="license_renewal_date" class="form-label fw-medium text-secondary">License Renewal Date</label>
                        <input type="date" name="license_renewal_date" id="license_renewal_date" 
                               class="form-control rounded-3" value="{{ old('license_renewal_date') }}">
                        @error('license_renewal_date')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="next_approval_date" class="form-label fw-medium text-secondary">Next Approval Date</label>
                        <input type="date" name="next_approval_date" id="next_approval_date" 
                               class="form-control rounded-3" value="{{ old('next_approval_date') }}">
                        @error('next_approval_date')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <hr class="my-4 border-light">

                <h5 class="mb-3 fw-semibold text-primary">Document Uploads</h5>

                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label for="license_document" class="form-label fw-medium text-secondary">License Document</label>
                        <input type="file" name="license_document" id="license_document" class="form-control rounded-3">
                        <small class="text-muted mt-1 d-block">PDF, DOC, JPG (Max: 5MB)</small>
                        @error('license_document')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="approval_document" class="form-label fw-medium text-secondary">Approval Document</label>
                        <input type="file" name="approval_document" id="approval_document" class="form-control rounded-3">
                        <small class="text-muted mt-1 d-block">PDF, DOC, JPG (Max: 5MB)</small>
                        @error('approval_document')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="certificate" class="form-label fw-medium text-secondary">Certificate</label>
                        <input type="file" name="certificate" id="certificate" class="form-control rounded-3">
                        <small class="text-muted mt-1 d-block">PDF, DOC, JPG (Max: 5MB)</small>
                        @error('certificate')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="other_documents" class="form-label fw-medium text-secondary">Other Documents</label>
                        <input type="file" name="other_documents" class="form-control">

                        <small class="text-muted mt-1 d-block">Multiple files (Max total: 10MB)</small>
                        @error('other_documents')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                    <a href="{{ route('programs.index') }}" class="btn btn-outline-secondary rounded-3 px-4 fw-medium">
                        <i class="fas fa-times me-2"></i>Cancel
                    </a>
                    <button type="submit" class="btn btn-primary rounded-3 px-4 fw-medium">
                        <i class="fas fa-check me-2"></i>Save Program
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
.card {
    border-radius: 0.75rem;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.card-header {
    border-bottom: none;
    border-radius: 0.75rem 0.75rem 0 0;
    background-color: #007bff;
}

.card-body {
    padding: 1.5rem;
}

.form-label {
    font-size: 0.875rem;
    color: #6c757d;
    margin-bottom: 0.5rem;
}

.form-control, .form-select {
    padding: 0.75rem 1rem;
    font-size: 0.875rem;
    border-radius: 0.5rem;
    border: 1px solid #ced4da;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
}

.form-control:focus, .form-select:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.btn-primary {
    background-color: #007bff;
    border-color: #007bff;
    padding: 0.5rem 1.5rem;
    font-size: 0.875rem;
}

.btn-primary:hover {
    background-color: #0056b3;
    border-color: #004085;
}

.btn-outline-secondary {
    border-color: #ced4da;
    color: #6c757d;
    padding: 0.5rem 1.5rem;
    font-size: 0.875rem;
}

.btn-outline-secondary:hover {
    background-color: #f8f9fa;
    color: #495057;
}

.btn-outline-light {
    border-color: rgba(255, 255, 255, 0.5);
    color: #fff;
}

.btn-outline-light:hover {
    background-color: rgba(255, 255, 255, 0.2);
}

hr.border-light {
    border-top: 1px solid rgba(0, 0, 0, 0.1);
}

.text-danger.small {
    font-size: 0.75rem;
}

.alert {
    padding: 1rem;
    font-size: 0.875rem;
}

.container-fluid {
    max-width: 1400px;
}
</style>
@endsection