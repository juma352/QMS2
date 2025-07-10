@extends('layouts.dashboard')

@section('title', 'Add New Program')

@section('content')
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
                        <div class="col-md-4">
                            <label for="subdivision_id" class="form-label fw-medium text-secondary">Subdivision <span class="text-danger">*</span></label>
                            <select name="subdivision_id" id="subdivision_id" class="form-select rounded-3" x-model="selectedSubdivision" @change="handleSubdivisionChange()" required>
                                <option value="">Select Subdivision</option>
                                @foreach ($subdivisions as $subdivision)
                                    <option value="{{ $subdivision->id }}">{{ $subdivision->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="school_id" class="form-label fw-medium text-secondary">School / Category <span class="text-danger">*</span></label>
                            <select name="school_id" id="school_id" class="form-select rounded-3" x-model="selectedSchool" @change="handleSchoolChange()" :disabled="!schools.length" required>
                                <option value="">Select School</option>
                                <template x-for="school in schools" :key="school.id">
                                    <option :value="school.id" x-text="school.name"></option>
                                </template>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="program_id" class="form-label fw-medium text-secondary">Program <span class="text-danger">*</span></label>
                            <select name="program_id" id="program_id" class="form-select rounded-3" x-model="selectedProgram" :disabled="!programs.length" required>
                                <option value="">Select Program</option>
                                <template x-for="program in programs" :key="program.id">
                                    <option :value="program.id" x-text="program.program_name"></option>
                                </template>
                            </select>
                        </div>
                    </div>

                    <hr class="my-4 border-light">

                    <h5 class="mb-3 fw-semibold text-primary">Program Details</h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="program_abbr" class="form-label fw-medium text-secondary">Program Abbreviation</label>
                            <input type="text" name="program_abbr" id="program_abbr" class="form-control rounded-3" placeholder="e.g., BSCS" value="{{ old('program_abbr') }}">
                        </div>
                        <div class="col-md-6">
                            <label for="faculty_member_id" class="form-label fw-medium text-secondary">Faculty Member</label>
                            <select name="faculty_member_id" id="faculty_member_id" class="form-select rounded-3">
                                <option value="">Select Head of Program</option>
                                @foreach($staff as $member)
                                    <option value="{{ $member->id }}" {{ old('faculty_member_id') == $member->id ? 'selected' : '' }}>
                                        {{ $member->first_name }} {{ $member->last_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="license_date" class="form-label fw-medium text-secondary">License Date</label>
                            <input type="date" name="license_date" id="license_date" class="form-control rounded-3" value="{{ old('license_date') }}">
                        </div>
                        <div class="col-md-6">
                            <label for="appointment_date" class="form-label fw-medium text-secondary">Appointment Date</label>
                            <input type="date" name="appointment_date" id="appointment_date" class="form-control rounded-3" value="{{ old('appointment_date') }}">
                        </div>
                    </div>

                    <hr class="my-4 border-light">

                    <h5 class="mb-3 fw-semibold text-primary">Document Uploads</h5>
                    <div id="document-uploads-container">
                        <template x-for="(doc, index) in documents" :key="index">
                            <div class="row g-3 mb-3 align-items-center">
                                <div class="col-md-5">
                                    <input type="text" :name="'documents[' + index + '][name]'" class="form-control" placeholder="Name of Document" x-model="doc.name" required>
                                </div>
                                <div class="col-md-5">
                                    <input type="file" :name="'documents[' + index + '][file]'" class="form-control" required>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" @click="removeDocument(index)" class="btn btn-outline-danger w-100">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>

                    <div class="mt-2">
                        <button type="button" @click="addDocument()" class="btn btn-outline-primary btn-sm fw-medium">
                            <i class="fas fa-plus me-2"></i>Add Document
                        </button>
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

@push('scripts')
    <script>
        function programForm() {
            return {
                allData: @json($subdivisions),
                selectedSubdivision: `{{ old('subdivision_id') ?? '' }}`,
                selectedSchool: `{{ old('school_id') ?? '' }}`,
                selectedProgram: `{{ old('program_id') ?? '' }}`,
                schools: [],
                programs: [],
                documents: [],

                init() {
                    // Repopulate documents array if there was a validation error
                    let oldDocs = @json(old('documents') ?? []);
                    if (oldDocs.length > 0) {
                        this.documents = oldDocs;
                    } else {
                        this.documents.push({ name: '', file: null });
                    }

                    // If a subdivision was selected from old input, populate the schools
                    if (this.selectedSubdivision) {
                        let subdivision = this.allData.find(s => s.id == this.selectedSubdivision);
                        if (subdivision) this.schools = subdivision.schools;
                    }

                    // If a school was selected from old input, populate the programs
                    if (this.selectedSchool && this.schools.length > 0) {
                        let school = this.schools.find(s => s.id == this.selectedSchool);
                        if (school) this.programs = school.programs;
                    }
                },

                handleSubdivisionChange() {
                    this.selectedSchool = '';
                    this.selectedProgram = '';
                    this.schools = [];
                    this.programs = [];
                    if (this.selectedSubdivision) {
                        let subdivision = this.allData.find(s => s.id == this.selectedSubdivision);
                        if (subdivision) this.schools = subdivision.schools;
                    }
                },

                handleSchoolChange() {
                    this.selectedProgram = '';
                    this.programs = [];
                    if (this.selectedSchool) {
                        let school = this.schools.find(s => s.id == this.selectedSchool);
                        if (school) this.programs = school.programs;
                    }
                },

                addDocument() {
                    this.documents.push({ name: '', file: null });
                },

                removeDocument(index) {
                    this.documents.splice(index, 1);
                }
            }
        }
    </script>
@endpush
