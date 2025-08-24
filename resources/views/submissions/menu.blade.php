@extends('layouts.dashboard')

@section('title', 'My Submissions')

@section('content')
    <div class="dashboard-header">
        <h1>My Submissions</h1>
        <p>Select the type of submission results you would like to view.</p>
    </div>

    <div class="row mt-4">
        <div class="col-md-6 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body d-flex flex-column justify-content-between">
                    <h5 class="card-title text-primary">Internal Audits (Dynamic Checklists)</h5>
                    <p class="card-text">View results for checklists generated specifically for internal audits and assessments.</p>
                    <a href="{{ route('submissions.index') }}" class="btn btn-primary mt-3">
                        <i class="fas fa-clipboard-list me-2"></i> View Internal Audit Results
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body d-flex flex-column justify-content-between">
                    <h5 class="card-title text-success">External Audit Results (Medical Specialist Checklists)</h5>
                    <p class="card-text">Access results from standardized medical specialist checklists used for external evaluations.</p>
                    <a href="{{ route('checklists.medical-specialist.results.index') }}" class="btn btn-success mt-3">
                        <i class="fas fa-user-md me-2"></i> View External Audit Results
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body d-flex flex-column justify-content-between">
                    <h5 class="card-title text-info">Drafts</h5>
                    <p class="card-text">View and continue working on your saved drafts.</p>
                    <a href="{{ route('checklists.medical-specialist.drafts') }}" class="btn btn-info mt-3">
                        <i class="fas fa-edit me-2"></i> View Drafts
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
