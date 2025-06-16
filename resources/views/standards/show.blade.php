@extends('layouts.dashboard')

@section('title', 'Standard Details')

@section('content')
<section class="standard-details">
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between p-3 rounded-top-3">
            <h2 class="h5 mb-0 fw-semibold">Details for: {{ $standard->standard_name }}</h2>
            <a href="{{ route('standards.index') }}" class="btn btn-outline-light btn-sm fw-medium">
                <i class="fas fa-arrow-left me-2"></i>Back to List
            </a>
        </div>

        <div class="card-body p-4">
            <div class="row">
                <div class="col-lg-8">
                    <h5 class="text-primary fw-semibold mb-3">Standard Information</h5>
                    <dl class="row">
                        <dt class="col-sm-4">Standard Name:</dt>
                        <dd class="col-sm-8">{{ $standard->standard_name }}</dd>

                        <dt class="col-sm-4">Standard Number:</dt>
                        <dd class="col-sm-8">{{ $standard->standard_number }}</dd>

                        <dt class="col-sm-4">Description:</dt>
                        <dd class="col-sm-8">{{ $standard->standard ?? 'N/A' }}</dd>

                        <dt class="col-sm-4">Clause:</dt>
                        <dd class="col-sm-8">{{ $standard->clause ?? 'N/A' }}</dd>

                        <dt class="col-sm-4">Revision Version:</dt>
                        <dd class="col-sm-8">{{ $standard->revision_version ?? 'N/A' }}</dd>
                    </dl>

                    <hr class="my-4">

                    <h5 class="text-primary fw-semibold mb-3">Date Information</h5>
                    <dl class="row">
                        <dt class="col-sm-4">Date Created:</dt>
                        <dd class="col-sm-8">{{ $standard->date_created ? \Carbon\Carbon::parse($standard->date_created)->format('F d, Y') : 'N/A' }}</dd>

                        <dt class="col-sm-4">Date of Issue:</dt>
                        <dd class="col-sm-8">{{ $standard->date_of_issue ? \Carbon\Carbon::parse($standard->date_of_issue)->format('F d, Y') : 'N/A' }}</dd>

                        <dt class="col-sm-4">Next Revision Date:</dt>
                        <dd class="col-sm-8">{{ $standard->next_revision_date ? \Carbon\Carbon::parse($standard->next_revision_date)->format('F d, Y') : 'N/A' }}</dd>
                        
                        <dt class="col-sm-4">Next Date of Issue:</dt>
                        <dd class="col-sm-8">{{ $standard->next_date_of_issue ? \Carbon\Carbon::parse($standard->next_date_of_issue)->format('F d, Y') : 'N/A' }}</dd>
                    </dl>
                </div>

                <div class="col-lg-4 border-start">
                    <h5 class="text-primary fw-semibold mb-3">Attached Documents</h5>
                    
                    @if($standard->standard_file)
                        <p class="mb-2"><strong>Main Standard Document:</strong></p>
                        <a href="{{ asset($standard->standard_file) }}" target="_blank" class="btn btn-sm btn-outline-success mb-3">
                            <i class="fas fa-file-download me-2"></i>Download Standard File
                        </a>
                    @endif

                    @if($standard->other_documents && count(json_decode($standard->other_documents, true)) > 0)
                        <p class="mb-2"><strong>Other Documents:</strong></p>
                        <ul class="list-group">
                            @foreach(json_decode($standard->other_documents, true) as $file)
                                <li class="list-group-item list-group-item-action">
                                    <a href="{{ asset($file) }}" target="_blank" class="text-decoration-none">
                                        <i class="fas fa-link me-2"></i>{{ basename($file) }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                    
                    @if(!$standard->standard_file && !$standard->other_documents)
                        <p class="text-muted">No documents attached.</p>
                    @endif

                    <hr class="my-4">

                    <h5 class="text-primary fw-semibold mb-3">Actions</h5>
                    <div class="d-flex flex-column gap-2">
                        <a href="{{ route('standards.edit', $standard->id) }}" class="btn btn-warning">
                            <i class="fas fa-pencil-alt me-2"></i>Edit This Standard
                        </a>
                        <form action="{{ route('standards.destroy', $standard->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this standard?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="fas fa-trash-alt me-2"></i>Delete This Standard
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection