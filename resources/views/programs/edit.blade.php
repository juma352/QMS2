@extends('layouts.dashboard')

@section('title', 'Edit Program')

@php
    $existingDocs = $program->documents->map(function($doc) {
        return [
            'id' => $doc->id,
            'name' => $doc->document_name,
            'url' => Storage::url($doc->file_path),
        ];
    });
@endphp

@section('content')
    <div class="container-fluid py-4">
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between p-3 rounded-top-3">
                <h2 class="h5 mb-0 fw-semibold">Edit Program: {{ $program->program_name ?? 'Unnamed' }}</h2>
                <a href="{{ route('programs.index') }}" class="btn btn-outline-light btn-sm fw-medium">
                    <i class="fas fa-list me-2"></i>View All Programs
                </a>
            </div>

            <div class="card-body p-4">
                <!-- Debugging Section -->
                <div class="mb-4 p-3 bg-light rounded">
                    <h3 class="fw-semibold">Debugging Program Data</h3>
                    <p><strong>Program Name:</strong> '{{ $program->program_name ?? 'Not set' }}'</p>
                    <p><strong>Documents Count:</strong> {{ $program->documents->count() }}</p>
                    @if($program->documents->isNotEmpty())
                        <ul>
                            @foreach($program->documents as $doc)
                                <li>{{ $doc->document_name }} - <a href="{{ Storage::url($doc->file_path) }}" target="_blank">View</a></li>
                            @endforeach
                        </ul>
                    @else
                        <p>No documents loaded.</p>
                    @endif
                </div>

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

                <form method="POST" action="{{ route('programs.update', $program) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <h5 class="mb-3 fw-semibold text-primary">Program Classification (Locked)</h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label fw-medium text-secondary">Subdivision</label>
                            <input type="text" class="form-control" value="{{ $program->school->subdivision->name ?? 'N/A' }}" disabled>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-medium text-secondary">School / Category</label>
                            <input type="text" class="form-control" value="{{ $program->school->name ?? 'N/A' }}" disabled>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-medium text-secondary">Program</label>
                            <input type="text" class="form-control" value="{{ $program->program_name ?? 'N/A' }}" disabled>
                        </div>
                    </div>

                    <hr class="my-4 border-light">

                    <h5 class="mb-3 fw-semibold text-primary">Update Program Details</h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="program_abbr" class="form-label fw-medium text-secondary">Program Abbreviation</label>
                            <input type="text" name="program_abbr" id="program_abbr" class="form-control"
                                   placeholder="e.g., BSCS" value="{{ old('program_abbr', $program->program_abbr) }}">
                        </div>
                        <div class="col-md-6">
                            <label for="faculty_member_id" class="form-label fw-medium text-secondary">Faculty Member</label>
                            <select name="faculty_member_id" id="faculty_member_id" class="form-select">
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
                            <label for="license_date" class="form-label fw-medium text-secondary">License Date</label>
                            <input type="date" name="license_date" id="license_date" class="form-control"
                                   value="{{ old('license_date', $program->license_date ? $program->license_date->format('Y-m-d') : '') }}">
                        </div>
                        <div class="col-md-6">
                            <label for="appointment_date" class="form-label fw-medium text-secondary">Appointment Date</label>
                            <input type="date" name="appointment_date" id="appointment_date" class="form-control"
                                   value="{{ old('appointment_date', $program->appointment_date ? $program->appointment_date->format('Y-m-d') : '') }}">
                        </div>
                    </div>

                    <hr class="my-4 border-light">

                    <h5 class="mb-3 fw-semibold text-primary">Manage Documents</h5>

                    <!-- Existing Documents with Blade -->
                    <h6 class="mb-3 fw-semibold text-secondary">Existing Documents</h6>
                    @if($program->documents->isNotEmpty())
                        @foreach($program->documents as $doc)
                            <div class="row g-3 mb-3 align-items-center" id="doc-{{ $doc->id }}">
                                <div class="col-md-5">
                                    <input type="text" name="existing_documents[{{ $doc->id }}][name]" class="form-control" value="{{ $doc->document_name }}" placeholder="Name of Document">
                                </div>
                                <div class="col-md-5">
                                    <a href="{{ Storage::url($doc->file_path) }}" target="_blankpre" class="btn btn-outline-secondary w-100"><i class="fas fa-link me-2"></i> View Current File</a>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" onclick="deleteDocument({{ $doc->id }})" class="btn btn-outline-danger w-100" title="Delete this document"><i class="fas fa-trash-alt"></i></button>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="alert alert-light text-center border">No documents have been uploaded yet.</div>
                    @endif

                    <!-- New Documents with Alpine.js -->
                    <h6 class="mb-3 fw-semibold text-secondary">Upload New Documents</h6>
                    <div x-data="{ newDocuments: [] }" x-init="if (newDocuments.length === 0) newDocuments.push({})">
                        <div id="new-documents-container">
                            <template x-for="(doc, index) in newDocuments" :key="index">
                                <div class="row g-3 mb-3 align-items-center">
                                    <div class="col-md-5">
                                        <input type="text" :name="'documents[' + index + '][name]'" class="form-control" placeholder="Name of New Document" required>
                                    </div>
                                    <div class="col-md-5">
                                        <input type="file" :name="'documents[' + index + '][file]'" class="form-control" required>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" @click="newDocuments.splice(index, 1)" class="btn btn-outline-danger w-100"><i class="fas fa-times"></i></button>
                                    </div>
                                </div>
                            </template>
                        </div>
                        <div class="mt-2">
                            <button type="button" @click="newDocuments.push({})" class="btn btn-outline-primary btn-sm fw-medium">
                                <i class="fas fa-plus me-2"></i>Add New Document
                            </button>
                        </div>
                    </div>

                    <!-- Hidden Input for Deleted Documents -->
                    <div id="deleted-docs-input"></div>

                    <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                        <a href="{{ route('programs.index') }}" class="btn btn-outline-secondary rounded-3 px-4 fw-medium">Cancel</a>
                        <button type="submit" class="btn btn-primary rounded-3 px-4 fw-medium">
                            <i class="fas fa-check me-2"></i>Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function deleteDocument(docId) {
            if (confirm('Are you sure you want to delete this document? This cannot be undone.')) {
                // Hide the document row
                document.getElementById('doc-' + docId).style.display = 'none';
                // Add hidden input to track deletion
                const form = document.getElementById('deleted-docs-input');
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'deleted_documents[]';
                input.value = docId;
                form.appendChild(input);
            }
        }
    </script>
@endsection

@push('scripts')
    <!-- Alpine.js is assumed to be included in layouts.dashboard -->
@endpush
