@extends('layouts.dashboard')

@section('title', 'Add New Program')

@section('content')
    {{-- We initialize Alpine.js and load the data for the dynamic dropdowns --}}
    <div class="container-fluid py-4" x-data="programForm()">
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

                    <h5 class="mb-3 fw-semibold text-primary">Program Classification</h5>
                    <div class="row g-3 mb-4">
                        {{-- 1. Subdivision Dropdown --}}
                        <div class="col-md-4">
                            <label for="subdivision_id" class="form-label fw-medium text-secondary">Subdivision <span class="text-danger">*</span></label>
                            <select name="subdivision_id" id="subdivision_id" class="form-select rounded-3" x-model="selectedSubdivision" @change="updateSchools" required>
                                <option value="">Select Subdivision</option>
                                @foreach ($subdivisions as $subdivision)
                                    <option value="{{ $subdivision->id }}">{{ $subdivision->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- 2. School/Category Dropdown --}}
                        <div class="col-md-4">
                            <label for="school_id" class="form-label fw-medium text-secondary">School / Category <span class="text-danger">*</span></label>
                            <select name="school_id" id="school_id" class="form-select rounded-3" x-model="selectedSchool" @change="updatePrograms" :disabled="!schools.length" required>
                                <option value="">Select School</option>
                                <template x-for="school in schools" :key="school.id">
                                    <option :value="school.id" x-text="school.name"></option>
                                </template>
                            </select>
                        </div>

                        {{-- 3. Program Dropdown --}}
                        <div class="col-md-4">
                            <label for="program_id" class="form-label fw-medium text-secondary">Program <span class="text-danger">*</span></label>
                            <select name="program_id" id="program_id" class="form-select rounded-3" x-model="selectedProgram" :disabled="!programs.length" required>
                                <option value="">Select Program</option>
                                <template x-for="program in programs" :key="program.id">
                                    <option :value="program.id" x-text="program.program_name"></option>
                                </template>
                            </select>
                            @error('program_id')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr class="my-4 border-light">

                    <h5 class="mb-3 fw-semibold text-primary">Program Details</h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="program_abbr" class="form-label fw-medium text-secondary">Program Abbreviation</label>
                            <input type="text" name="program_abbr" id="program_abbr" class="form-control rounded-3"
                                   placeholder="e.g., BSCS"
                                   value="{{ old('program_abbr') }}">
                            @error('program_abbr')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="faculty_member_id" class="form-label fw-medium text-secondary">Faculty Member</label>
                            <select name="faculty_member_id" id="faculty_member_id" class="form-select rounded-3">
                                <option value="">Select Faculty Member</option>
                                @foreach($staff as $member)
                                    <option value="{{ $member->id }}" {{ old('faculty_member_id') == $member->id ? 'selected' : '' }}>
                                        {{ $member->first_name }} {{ $member->last_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('faculty_member_id')
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

    {{-- This script drives the dynamic dropdowns --}}
    <script>
        function programForm() {
            return {
                allData: @json($subdivisions),
                selectedSubdivision: `{{ old('subdivision_id') ?? '' }}`,
                selectedSchool: `{{ old('school_id') ?? '' }}`,
                selectedProgram: `{{ old('program_id') ?? '' }}`,
                schools: [],
                programs: [],

                init() {
                    if (this.selectedSubdivision) {
                        this.updateSchools();
                    }
                    if (this.selectedSchool) {
                        this.updatePrograms();
                    }
                },

                updateSchools() {
                    this.selectedSchool = ''; // Reset child dropdown
                    this.schools = [];
                    if (this.selectedSubdivision) {
                        const subdivision = this.allData.find(sub => sub.id == this.selectedSubdivision);
                        this.schools = subdivision ? subdivision.schools : [];
                    }
                    this.updatePrograms(); // Also reset grandchild dropdown
                },

                updatePrograms() {
                    this.selectedProgram = ''; // Reset
                    this.programs = [];
                    if (this.selectedSchool) {
                        const school = this.schools.find(sch => sch.id == this.selectedSchool);
                        this.programs = school ? school.programs : [];
                    }
                }
            }
        }
    </script>
@endsection


@section('styles')
    {{-- Your original styles remain unchanged --}}
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
        .card-body { padding: 1.5rem; }
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
        /* ... other styles from your original file ... */
    </style>
@endsection
