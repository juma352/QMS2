@extends('layouts.dashboard')

@section('title', 'Add New Staff Member')

@section('content')
<div class="card shadow-sm border-0 rounded-3">
    <div class="card-header bg-primary text-white p-3">
        <h2 class="h5 mb-0 fw-semibold">Add New Staff Member</h2>
    </div>
    <div class="card-body p-4">

        {{-- Display Validation Errors --}}
        @if ($errors->any())
            <div class="alert alert-danger rounded-3 border-0" role="alert">
                <h4 class="alert-heading h6">Please correct the following errors:</h4>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('staff.store') }}" enctype="multipart/form-data">
            @csrf
            
            <h3 class="h6 fw-semibold border-bottom pb-2 mb-3">Personal & Contact Information</h3>
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                    <input type="text" name="first_name" id="first_name" class="form-control" value="{{ old('first_name') }}" required>
                </div>
                <div class="col-md-6">
                    <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                    <input type="text" name="last_name" id="last_name" class="form-control" value="{{ old('last_name') }}" required>
                </div>
                <div class="col-md-6">
                    <label for="staff_number" class="form-label">Staff Number <span class="text-danger">*</span></label>
                    <input type="text" name="staff_number" id="staff_number" class="form-control" value="{{ old('staff_number') }}" required>
                </div>
                <div class="col-md-6">
                    <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                    <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required>
                </div>
               <div class="col-md-12">
                    <label for="department" class="form-label">Department</label>
                    <select name="department" id="department" class="form-select">
                        <option value="">Select Department</option>
                        @foreach($subdivisions as $subdivision)
                            <option value="{{ $subdivision->name }}" {{ old('department') == $subdivision->name ? 'selected' : '' }}>
                                {{ $subdivision->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <h3 class="h6 fw-semibold border-bottom pb-2 mb-3 mt-4">License Information</h3>
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="license_number" class="form-label">License Number</label>
                    <input type="text" name="license_number" id="license_number" class="form-control" value="{{ old('license_number') }}">
                </div>
                <div class="col-md-6">
                    <label for="license_renewal_date" class="form-label">License Renewal Date</label>
                    <input type="date" name="license_renewal_date" id="license_renewal_date" class="form-control" value="{{ old('license_renewal_date') }}">
                </div>
            </div>
             <h3 class="h6 fw-semibold border-bottom pb-2 mb-3 mt-4">Experience Information</h3>
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="start_date" class="form-label">Start Date <span class="text-danger">*</span></label>
                    <input type="date" name="start_date" id="start_date" class="form-control" value="{{ old('start_date') }}" required>
                </div>
                <div class="col-md-6">
                    <label for="years_of_experience" class="form-label">Years of Experience</label>
                    <input type="text" id="years_of_experience" class="form-control" readonly>
                </div>
            </div>
            <h3 class="h6 fw-semibold border-bottom pb-2 mb-3 mt-4">Document Uploads</h3>
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="license_document" class="form-label">License Document</label>
                    <input type="file" name="license_document" id="license_document" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label for="appointment_letter" class="form-label">Appointment Letter</label>
                    <input type="file" name="appointment_letter" id="appointment_letter" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label for="cv" class="form-label">CV / Resume</label>
                    <input type="file" name="cv" id="cv" class="form-control" required>
                </div>
                 <div class="col-md-6">
                    <label for="short_course_certificate" class="form-label">Short Course Certificate</label>
                    <input type="file" name="short_course_certificate" id="short_course_certificate" class="form-control" required>
                </div>
                 <div class="col-md-6">
                    <label for="other_certificate" class="form-label">Other Certificate</label>
                    <input type="file" name="other_certificate" id="other_certificate" class="form-control" required>
                </div>
            </div>

            <div class="mt-4 text-end">
                <a href="{{ route('staff.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Save Staff Member</button>
            </div>
        </form>
    </div>
</div>
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const startDateInput = document.getElementById('start_date');
        const experienceInput = document.getElementById('years_of_experience');

        function calculateExperience() {
            if (startDateInput.value) {
                const startDate = new Date(startDateInput.value);
                const today = new Date();
                const diffTime = Math.abs(today - startDate);
                const diffYears = (diffTime / (1000 * 60 * 60 * 24 * 365.25)).toFixed(1);
                experienceInput.value = diffYears + ' years';
            } else {
                experienceInput.value = '';
            }
        }

        startDateInput.addEventListener('change', calculateExperience);
        // Calculate on page load if date is pre-filled
        calculateExperience();
    });
</script>
@endpush
@endsection
