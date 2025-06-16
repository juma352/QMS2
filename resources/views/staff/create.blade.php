@extends('layouts.dashboard')

@section('title', 'Add New Staff')

@section('content')
<h2 style="font-weight: 600;">Add New Staff</h2>

<a href="{{ route('staff.index') }}" style="text-decoration: none; color: #159ed5;">← Back to list</a>

@if ($errors->any())
    <div style="color: red; margin-top: 1rem;">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (session('success'))
    <div style="color: green; margin-top: 1rem;">
        {{ session('success') }}
    </div>
@endif

<form method="POST" action="{{ route('staff.store') }}" enctype="multipart/form-data" style="margin-top: 1.5rem; max-width: 700px;">
    @csrf

    <div class="form-group"><label>First Name</label><input type="text" name="first_name" class="form-control" required></div>
    <div class="form-group"><label>Last Name</label><input type="text" name="last_name" class="form-control" required></div>
    <div class="form-group"><label>Staff Number</label><input type="text" name="staff_number" class="form-control" required></div>
    <div class="form-group"><label>Email</label><input type="email" name="email" class="form-control" required></div>
    <div class="form-group">
        <label>Department</label>
        <select name="department" class="form-control">
            <option value="">- Select -</option>
            <option value="Nursing">Nursing</option>
            <option value="Physiotherapy">Physiotherapy</option>
            <option value="Education">Education</option>
        </select>
    </div>
    <div class="form-group"><label>License Number</label><input type="text" name="license_number" class="form-control"></div>
    <div class="form-group"><label>License Renewal Date</label><input type="date" name="license_renewal_date" class="form-control"></div>

    @foreach ([
        'license_document' => 'License Document',
        'appointment_letter' => 'Appointment Letter',
        'cv' => 'CV',
        'short_course_certificate' => 'Short Course Certificate',
        'other_certificate' => 'Other Certificate',
    ] as $field => $label)
        <div class="form-group">
            <label>{{ $label }}</label>
            <input type="file" name="{{ $field }}" class="form-control">
        </div>
    @endforeach

    <div style="margin-top: 1rem;">
        <button type="submit" style="background: #159ed5; color: white; padding: 0.6rem 1.5rem; border: none; border-radius: 5px;">
            <i class="fas fa-save"></i> Save
        </button>
    </div>
</form>
@endsection
