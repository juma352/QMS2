@extends('layouts.dashboard')

@section('title', 'Edit CQI Project')

@section('content')
<div class="card shadow-sm border-0 rounded-3">
    <div class="card-header bg-primary text-white p-3">
        <h2 class="h5 mb-0 fw-semibold">Edit CQI Project: {{ $cqiProject->project_name }}</h2>
    </div>
    <div class="card-body p-4">
        {{-- CORRECTED ROUTE --}}
        <form method="POST" action="{{ route('cqi_projects.update', $cqiProject->id) }}">
            @csrf
            @method('PUT')
            <div class="row g-4">
                <div class="col-md-6">
                    <label for="project_name" class="form-label">Project Name <span class="text-danger">*</span></label>
                    <input type="text" name="project_name" id="project_name" class="form-control" value="{{ old('project_name', $cqiProject->project_name) }}" required>
                </div>
                <div class="col-md-6">
                    <label for="project_leader" class="form-label">Project Leader</label>
                    <input type="text" name="project_leader" id="project_leader" class="form-control" value="{{ old('project_leader', $cqiProject->project_leader) }}">
                </div>
                <div class="col-12">
                    <label for="description" class="form-label">Description</label>
                    <textarea name="description" id="description" class="form-control" rows="3">{{ old('description', $cqiProject->description) }}</textarea>
                </div>
                <div class="col-12">
                    <label for="mission" class="form-label">Mission</label>
                    <textarea name="mission" id="mission" class="form-control" rows="3">{{ old('mission', $cqiProject->mission) }}</textarea>
                </div>
                <div class="col-md-6">
                    <label for="methodology" class="form-label">Methodology <span class="text-danger">*</span></label>
                    <select name="methodology" id="methodology" class="form-select" required>
                        @foreach($methodologies as $method)
                            <option value="{{ $method }}" {{ old('methodology', $cqiProject->methodology) == $method ? 'selected' : '' }}>{{ $method }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                    <select name="status" id="status" class="form-select" required>
                        @foreach($statuses as $status)
                            <option value="{{ $status }}" {{ old('status', $cqiProject->status) == $status ? 'selected' : '' }}>{{ $status }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12">
                    <label for="problem_statement" class="form-label">Problem Statement</label>
                    <textarea name="problem_statement" id="problem_statement" class="form-control" rows="3">{{ old('problem_statement', $cqiProject->problem_statement) }}</textarea>
                </div>
                <div class="col-12">
                    <label for="smart_goals" class="form-label">SMART Goals</label>
                    <textarea name="smart_goals" id="smart_goals" class="form-control" rows="3">{{ old('smart_goals', $cqiProject->smart_goals) }}</textarea>
                </div>
                <div class="col-12">
                    <label for="metrics_to_track" class="form-label">Metrics to Track</label>
                    <textarea name="metrics_to_track" id="metrics_to_track" class="form-control" rows="3">{{ old('metrics_to_track', $cqiProject->metrics_to_track) }}</textarea>
                </div>
                <div class="col-12">
                    <label for="data_collection_method" class="form-label">Data Collection Method</label>
                    <textarea name="data_collection_method" id="data_collection_method" class="form-control" rows="3">{{ old('data_collection_method', $cqiProject->data_collection_method) }}</textarea>
                </div>
                <div class="col-md-6">
                    <label for="initial_progress" class="form-label">Initial Progress (%) <span class="text-danger">*</span></label>
                    <input type="number" name="initial_progress" id="initial_progress" class="form-control" value="{{ old('initial_progress', $cqiProject->initial_progress) }}" min="0" max="100" required>
                </div>
            </div>
            <div class="mt-4 text-end">
                {{-- CORRECTED ROUTE --}}
                <a href="{{ route('cqi_projects.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Project</button>
            </div>
        </form>
    </div>
</div>
@endsection
