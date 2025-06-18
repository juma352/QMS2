@extends('layouts.dashboard')

@section('title', 'View CQI Project')

@section('content')
<div class="card shadow-sm border-0 rounded-3">
    <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between p-3 rounded-top-3">
        <div>
            <h2 class="h5 mb-0 fw-semibold">Project Details</h2>
            <p class="mb-0 small">{{ $cqiProject->project_name }}</p>
        </div>
        <div>
            {{-- CORRECTED ROUTES --}}
            <a href="{{ route('cqi_projects.index') }}" class="btn btn-outline-light btn-sm fw-medium">
                <i class="fas fa-list-ul me-2"></i>Back to List
            </a>
            <a href="{{ route('cqi_projects.edit', $cqiProject->id) }}" class="btn btn-light btn-sm fw-medium">
                <i class="fas fa-pencil-alt me-2"></i>Edit Project
            </a>
        </div>
    </div>

    <div class="card-body p-4">
        <div class="row g-4">
            {{-- Project Details Section --}}
            <div class="col-lg-6">
                <h3 class="h6 fw-bold border-bottom pb-2 mb-3">Core Information</h3>
                <dl class="row">
                    <dt class="col-sm-4">Project Leader:</dt>
                    <dd class="col-sm-8">{{ $cqiProject->project_leader ?? 'N/A' }}</dd>

                    <dt class="col-sm-4">Methodology:</dt>
                    <dd class="col-sm-8">{{ $cqiProject->methodology }}</dd>

                    <dt class="col-sm-4">Status:</dt>
                    <dd class="col-sm-8"><span class="badge text-capitalize bg-info">{{ $cqiProject->status }}</span></dd>
                    
                    <dt class="col-sm-4">Progress:</dt>
                    <dd class="col-sm-8">
                        <div class="progress" style="height: 20px;">
                            <div class="progress-bar" role="progressbar" style="width: {{ $cqiProject->initial_progress }}%;" aria-valuenow="{{ $cqiProject->initial_progress }}" aria-valuemin="0" aria-valuemax="100">
                                {{ $cqiProject->initial_progress }}%
                            </div>
                        </div>
                    </dd>
                </dl>
            </div>

            {{-- Descriptions Section --}}
            <div class="col-lg-6">
                 <h3 class="h6 fw-bold border-bottom pb-2 mb-3">Project Overview</h3>
                 <p><strong>Description:</strong><br>{{ $cqiProject->description ?? 'No description provided.' }}</p>
                 <p><strong>Mission:</strong><br>{{ $cqiProject->mission ?? 'No mission provided.' }}</p>
            </div>
            
            <div class="col-12"><hr></div>

            {{-- Detailed Fields Section --}}
            <div class="col-12">
                 <h3 class="h6 fw-bold border-bottom pb-2 mb-3">Detailed Plan</h3>
                 <div class="row g-4">
                     <div class="col-md-6">
                         <strong>Problem Statement:</strong>
                         <p class="text-muted">{{ $cqiProject->problem_statement ?? 'Not specified.' }}</p>
                     </div>
                     <div class="col-md-6">
                         <strong>SMART Goals:</strong>
                         <p class="text-muted">{{ $cqiProject->smart_goals ?? 'Not specified.' }}</p>
                     </div>
                     <div class="col-md-6">
                         <strong>Metrics to Track:</strong>
                         <p class="text-muted">{{ $cqiProject->metrics_to_track ?? 'Not specified.' }}</p>
                     </div>
                     <div class="col-md-6">
                         <strong>Data Collection Method:</strong>
                         <p class="text-muted">{{ $cqiProject->data_collection_method ?? 'Not specified.' }}</p>
                     </div>
                 </div>
            </div>
        </div>
    </div>
</div>
@endsection
