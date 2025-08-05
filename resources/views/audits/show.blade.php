@extends('layouts.dashboard')

@section('title', 'Audit Details')

@section('content')
<div class="card shadow-sm border-0 rounded-3">
    <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between p-3">
        <h2 class="h5 mb-0 fw-semibold">
            {{ $audit->audit_type }} Audit: {{ $audit->audit_name }}
        </h2>
        <div>
            <a href="{{ route('audits.index', ['type' => $audit->audit_type]) }}" class="btn btn-outline-light btn-sm fw-medium">
                <i class="fas fa-arrow-left me-2"></i>Back to List
            </a>
            <a href="{{ route('audits.edit', $audit->id) }}" class="btn btn-light btn-sm fw-medium">
                <i class="fas fa-pencil-alt me-2"></i>Edit
            </a>
        </div>
    </div>

    <div class="card-body p-4">
        <div class="row">
            <div class="col-md-8">
                <h5 class="text-primary fw-semibold mb-3">Audit Information</h5>
                <dl class="row">
                    <dt class="col-sm-4">Audit Name:</dt>
                    <dd class="col-sm-8">{{ $audit->audit_name }}</dd>

                    <dt class="col-sm-4">Audit Number:</dt>
                    <dd class="col-sm-8">{{ $audit->audit_number ?? 'N/A' }}</dd>

                    <dt class="col-sm-4">Issuing Authority:</dt>
                    <dd class="col-sm-8">{{ $audit->issuing_authority ?? 'N/A' }}</dd>
                    
                    <dt class="col-sm-4">Auditor:</dt>
                    <dd class="col-sm-8">{{ $audit->auditor ?? 'N/A' }}</dd>

                    <dt class="col-sm-4">Standard:</dt>
                    <dd class="col-sm-8">{{ $audit->standard->standard_name ?? 'N/A' }}</dd>
                    
                    <dt class="col-sm-4">Status:</dt>
                    <dd class="col-sm-8"><span class="badge bg-info text-dark">{{ $audit->status }}</span></dd>

                    <dt class="col-sm-4">Date Conducted:</dt>
                    <dd class="col-sm-8">{{ $audit->date_conducted ? $audit->date_conducted->format('F d, Y') : 'N/A' }}</dd>

                    <dt class="col-sm-4">Next Audit Date:</dt>
                    <dd class="col-sm-8">{{ $audit->next_audit_date ? $audit->next_audit_date->format('F d, Y') : 'N/A' }}</dd>
                </dl>
                
                <hr class="my-4">
                
               

            </div>

            <div class="col-md-4 border-start">
                <h5 class="text-primary fw-semibold mb-3">Attached Documents</h5>
                
                @if($audit->audit_report_path)
                    <p class="mb-2"><strong>Audit Report:</strong></p>
                    <a href="{{ Storage::url($audit->audit_report_path) }}" target="_blank" class="btn btn-sm btn-outline-success mb-3">
                        <i class="fas fa-file-download me-2"></i>Download Report
                    </a>
                @else
                    <p class="text-muted small">No main audit report was uploaded.</p>
                @endif

                @if($audit->supporting_documents_paths && count($audit->supporting_documents_paths) > 0)
                    <p class="mb-2 mt-3"><strong>Supporting Documents:</strong></p>
                    <div class="list-group">
                        @foreach($audit->supporting_documents_paths as $path)
                            <a href="{{ Storage::url($path) }}" target="_blank" class="list-group-item list-group-item-action d-flex align-items-center">
                                <i class="fas fa-link me-2"></i>
                                {{ basename($path) }}
                            </a>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted small">No supporting documents were uploaded.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection