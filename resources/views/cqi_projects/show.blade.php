@extends('layouts.dashboard')

@section('title', 'CQI Project Details')

@section('content')
<div class="card shadow-sm border-0 rounded-3">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center p-3">
        <h2 class="h5 mb-0 fw-semibold">Project: {{ $cqiProject->project_name }}</h2>
        <a href="{{ route('cqi-projects.index') }}" class="btn btn-outline-light btn-sm fw-medium">
            <i class="fas fa-arrow-left me-2"></i>Back to List
        </a>
    </div>
    <div class="card-body p-4">
        <div class="row">
            <div class="col-md-8">
                <dl class="row">
                    <dt class="col-sm-4">Project Leader:</dt>
                    <dd class="col-sm-8">{{ $cqiProject->project_leader ?? 'N/A' }}</dd>

                    <dt class="col-sm-4">Methodology:</dt>
                    <dd class="col-sm-8">{{ $cqiProject->methodology }}</dd>
                    
                    <dt class="col-sm-4">Description:</dt>
                    <dd class="col-sm-8">{{ $cqiProject->description ?? 'N/A' }}</dd>
                    
                    <dt class="col-sm-4">Mission:</dt>
                    <dd class="col-sm-8">{{ $cqiProject->mission ?? 'N/A' }}</dd>

                    <dt class="col-sm-4">Problem Statement:</dt>
                    <dd class="col-sm-8">{{ $cqiProject->problem_statement ?? 'N/A' }}</dd>

                    <dt class="col-sm-4">SMART Goals:</dt>
                    <dd class="col-sm-8">{{ $cqiProject->smart_goals ?? 'N/A' }}</dd>

                    <dt class="col-sm-4">Metrics to Track:</dt>
                    <dd class="col-sm-8">{{ $cqiProject->metrics_to_track ?? 'N/A' }}</dd>
                    
                    <dt class="col-sm-4">Data Collection Method:</dt>
                    <dd class="col-sm-8">{{ $cqiProject->data_collection_method ?? 'N/A' }}</dd>
                </dl>
            </div>
            <div class="col-md-4">
                <div class="card bg-light">
                    <div class="card-body">
                        <h5 class="card-title">Project Status</h5>
                        <p class="card-text">
                            <strong>Current Status:</strong> 
                            <span class="badge bg-info">{{ $cqiProject->status }}</span>
                        </p>
                        <p class="card-text">
                            <strong>Progress:</strong> {{ $cqiProject->initial_progress }}%
                        </p>
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" style="width: {{ $cqiProject->initial_progress }}%;" aria-valuenow="{{ $cqiProject->initial_progress }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-4 border-top pt-3 text-end">
            <a href="{{ route('cqi-projects.edit', $cqiProject->id) }}" class="btn btn-warning">Edit</a>
        </div>
    </div>
</div>
@endsection