@extends('layouts.dashboard')

@section('title', 'Add New Standard')

@section('content')
<section class="standard-form">
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between p-3 rounded-top-3">
            <h2 class="h5 mb-0 fw-semibold">Add New Standard</h2>
            <a href="{{ route('standards.index') }}" class="btn btn-outline-light btn-sm fw-medium">
                <i class="fas fa-list me-2"></i>View All Standards
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

            <form method="POST" action="{{ route('standards.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="standard_name" class="form-label fw-medium text-secondary">Standard Name <span class="text-danger">*</span></label>
                        <input type="text" name="standard_name" id="standard_name" class="form-control rounded-3"
                               placeholder="e.g., ISO 9001" value="{{ old('standard_name') }}" required>
                        @error('standard_name')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="standard_number" class="form-label fw-medium text-secondary">Standard Number <span class="text-danger">*</span></label>
                        <input type="text" name="standard_number" id="standard_number" class="form-control rounded-3"
                               placeholder="e.g., 9001" value="{{ old('standard_number') }}" required>
                        @error('standard_number')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="standard" class="form-label fw-medium text-secondary">Standard Description</label>
                        <textarea name="standard" id="standard" class="form-control rounded-3" rows="4"
                                  placeholder="Brief description of the standard">{{ old('standard') }}</textarea>
                        @error('standard')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="clause" class="form-label fw-medium text-secondary">Clause</label>
                        <input type="text" name="clause" id="clause" class="form-control rounded-3"
                               placeholder="e.g., Clause 7.1" value="{{ old('clause') }}">
                        @error('clause')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="revision_version" class="form-label fw-medium text-secondary">Revision Version</label>
                        <input type="text" name="revision_version" id="revision_version" class="form-control rounded-3"
                               placeholder="e.g., v2.1" value="{{ old('revision_version') }}">
                        @error('revision_version')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="date_created" class="form-label fw-medium text-secondary">Date Created</label>
                        <input type="date" name="date_created" id="date_created" class="form-control rounded-3"
                               value="{{ old('date_created') }}">
                        @error('date_created')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="date_of_issue" class="form-label fw-medium text-secondary">Date of Issue</label>
                        <input type="date" name="date_of_issue" id="date_of_issue" class="form-control rounded-3"
                               value="{{ old('date_of_issue') }}">
                        @error('date_of_issue')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="next_revision_date" class="form-label fw-medium text-secondary">Next Revision Date</label>
                        <input type="date" name="next_revision_date" id="next_revision_date" class="form-control rounded-3"
                               value="{{ old('next_revision_date') }}">
                        @error('next_revision_date')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="next_date_of_issue" class="form-label fw-medium text-secondary">Next Date of Issue</label>
                        <input type="date" name="next_date_of_issue" id="next_date_of_issue" class="form-control rounded-3"
                               value="{{ old('next_date_of_issue') }}">
                        @error('next_date_of_issue')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <hr class="my-4 border-light">

                <h5 class="mb-3 fw-semibold text-primary">Document Uploads</h5>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="standard_file" class="form-label fw-medium text-secondary">Standard Document</label>
                        <input type="file" name="standard_file" id="standard_file" class="form-control rounded-3">
                        <small class="text-muted mt-1 d-block">PDF, DOCX, JPEG, PNG (Max: 5MB)</small>
                        @error('standard_file')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="other_documents" class="form-label fw-medium text-secondary">Other Documents</label>
                        <input type="file" name="other_documents[]" id="other_documents" class="form-control rounded-3" multiple>
                        <small class="text-muted mt-1 d-block">Multiple files (Max total: 10MB)</small>
                        @error('other_documents')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                    <a href="{{ route('standards.index') }}" class="btn btn-outline-secondary rounded-3 px-4 fw-medium">
                        <i class="fas fa-circle-xmark me-2"></i>Cancel
                    </a>
                    <button type="submit" class="btn btn-primary rounded-3 px-4 fw-medium">
                        <i class="fas fa-check me-2"></i>Save Standard
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

@section('styles')
<style>
    .standard-form .card {
        border-radius: 0.75rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    .standard-form .card-header {
        background-color: #159ed5;
        border-bottom: none;
        border-radius: 0.75rem 0.75rem 0 0;
    }
    .standard-form .form-label {
        font-size: 0.875rem;
        color: #6c757d;
        margin-bottom: 0.5rem;
    }
    .standard-form .form-control, .standard-form .form-select, .standard-form textarea {
        padding: 0.75rem 1rem;
        font-size: 0.875rem;
        border-radius: 0.5rem;
        border: 1px solid #ced4da;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }
    .standard-form .form-control:focus, .standard-form .form-select:focus, .standard-form textarea:focus {
        border-color: #159ed5;
        box-shadow: 0 0 0 0.2rem rgba(21, 158, 213, 0.25);
    }
    .standard-form .btn-primary {
        background-color: #159ed5;
        border-color: #159ed5;
        padding: 0.5rem 1.5rem;
        font-size: 0.875rem;
    }
    .standard-form .btn-primary:hover {
        background-color: #007ca0;
        border-color: #007ca0;
    }
    .standard-form .btn-outline-secondary {
        border-color: #ced4da;
        color: #6c757d;
        padding: 0.5rem 1.5rem;
        font-size: 0.875rem;
    }
    .standard-form .btn-outline-secondary:hover {
        background-color: #f8f9fa;
        color: #495057;
    }
    .standard-form .btn-outline-light {
        border-color: rgba(255, 255, 255, 0.5);
        color: #fff;
    }
    .standard-form .btn-outline-light:hover {
        background-color: rgba(255, 255, 255, 0.2);
    }
    .standard-form hr.border-light {
        border-top: 1px solid rgba(0, 0, 0, 0.1);
    }
    .standard-form .text-danger.small {
        font-size: 0.75rem;
    }
    .standard-form .alert {
        padding: 1rem;
        font-size: 0.875rem;
    }
    @media (max-width: 576px) {
        .standard-form .card-body {
            padding: 1rem;
        }
        .standard-form .form-control, .standard-form .form-select, .standard-form textarea {
            font-size: 0.8125rem;
            padding: 0.5rem 0.75rem;
        }
        .standard-form .btn {
            padding: 0.375rem 1rem;
            font-size: 0.8125rem;
        }
    }
</style>
@endsection
@endsection