@extends('layouts.dashboard')

@section('title', 'Edit Program')

@section('content')
<h2 style="font-weight: 600;">Edit Program</h2>

<a href="{{ route('programs.index') }}" style="text-decoration: none; color: #159ed5;">← Back to list</a>

@if ($errors->any())
    <div style="color: red; margin-top: 1rem;">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('programs.update', $program->id) }}" enctype="multipart/form-data" style="margin-top: 1.5rem; max-width: 700px;">
    @csrf
    @method('PUT')

    <div class="form-group">
        <label>Program Name</label>
        <input type="text" name="program_name" class="form-control" value="{{ old('program_name', $program->program_name) }}" required>
    </div>

    <div class="form-group">
        <label>Program Abbr.</label>
        <input type="text" name="program_abbr" class="form-control" value="{{ old('program_abbr', $program->program_abbr) }}">
    </div>

    <div class="form-group">
        <label>Subdivision</label>
        <input type="text" name="subdivision" class="form-control" value="{{ old('subdivision', $program->subdivision) }}">
    </div>

    <div class="form-group">
        <label>Faculty Member</label>
        <input type="text" name="faculty_member" class="form-control" value="{{ old('faculty_member', $program->faculty_member) }}">
    </div>

    <div class="form-group">
        <label>Role</label>
        <input type="text" name="role" class="form-control" value="{{ old('role', $program->role) }}">
    </div>

    <div class="form-group">
        <label>License Renewal Date</label>
        <input type="date" name="license_renewal_date" class="form-control" value="{{ old('license_renewal_date', $program->license_renewal_date) }}">
    </div>

    <div class="form-group">
        <label>Next Approval Date</label>
        <input type="date" name="next_approval_date" class="form-control" value="{{ old('next_approval_date', $program->next_approval_date) }}">
    </div>

    <div class="form-group">
        <label>License Document</label>
        <input type="file" name="license_document" class="form-control">
        @if ($program->license_document)
            <div><a href="{{ asset($program->license_document) }}" target="_blank">Current File</a></div>
        @endif
    </div>

    <div class="form-group">
        <label>Approval Document</label>
        <input type="file" name="approval_document" class="form-control">
        @if ($program->approval_document)
            <div><a href="{{ asset($program->approval_document) }}" target="_blank">Current File</a></div>
        @endif
    </div>

    <div class="form-group">
        <label>Certificate</label>
        <input type="file" name="certificate" class="form-control">
        @if ($program->certificate)
            <div><a href="{{ asset($program->certificate) }}" target="_blank">Current File</a></div>
        @endif
    </div>

    <div class="form-group">
        <label>Other Documents</label>
        <input type="file" name="other_documents" class="form-control">
        @if ($program->other_documents)
            <div><a href="{{ asset($program->other_documents) }}" target="_blank">Current File</a></div>
        @endif
    </div>

    <div style="margin-top: 1rem;">
        <button type="submit" style="background: #159ed5; color: white; padding: 0.6rem 1.5rem; border: none; border-radius: 5px;">
            <i class="fas fa-save"></i> Update
        </button>
        <a href="{{ route('programs.index') }}" style="margin-left: 1rem; color: #333;">Cancel</a>
    </div>
</form>
@endsection
