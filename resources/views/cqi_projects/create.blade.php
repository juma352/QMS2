@extends('layouts.dashboard')

@section('title', 'Add New CQI Project')

@section('content')
<div class="card shadow-sm border-0 rounded-3">
    <div class="card-header bg-primary text-white p-3">
        <h2 class="h5 mb-0 fw-semibold">Add New CQI Project</h2>
    </div>
    <div class="card-body p-4">
        <form method="POST" action="{{ route('cqi_projects.store') }}">
            @csrf
            <div class="row g-4">
                <div class="col-md-6">
                    <label for="project_name" class="form-label">Project Name <span class="text-danger">*</span></label>
                    <input type="text" name="project_name" id="project_name" class="form-control" value="{{ old('project_name') }}" required>
                    @error('project_name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="project_leader" class="form-label">Project Leader</label>
                    <input type="text" name="project_leader" id="project_leader" class="form-control" value="{{ old('project_leader') }}">
                    @error('project_leader')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-12">
                    <label for="description" class="form-label">Description</label>
                    <textarea name="description" id="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                    @error('description')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-12">
                    <label for="mission" class="form-label">Mission</label>
                    <textarea name="mission" id="mission" class="form-control" rows="3">{{ old('mission') }}</textarea>
                    @error('mission')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="methodology" class="form-label">Methodology <span class="text-danger">*</span></label>
                    <select name="methodology" id="methodology" class="form-select" required onchange="toggleCustomMethodology()">
                        @foreach($methodologies as $method)
                            <option value="{{ $method }}" {{ old('methodology') == $method ? 'selected' : '' }}>{{ $method }}</option>
                        @endforeach
                    </select>
                    @error('methodology')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-md-6" id="custom-methodology-group" style="display: none;">
                    <label for="custom_methodology" class="form-label">Custom Methodology <span class="text-danger">*</span></label>
                    <input type="text" name="custom_methodology" id="custom_methodology" class="form-control" value="{{ old('custom_methodology') }}">
                    @error('custom_methodology')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                    <select name="status" id="status" class="form-select" required>
                        @foreach($statuses as $status)
                            <option value="{{ $status }}" {{ old('status') == $status ? 'selected' : '' }}>{{ $status }}</option>
                        @endforeach
                    </select>
                    @error('status')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-12">
                    <label for="problem_statement" class="form-label">Problem Statement</label>
                    <textarea name="problem_statement" id="problem_statement" class="form-control" rows="3">{{ old('problem_statement') }}</textarea>
                    @error('problem_statement')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-12">
                    <label for="smart_goals" class="form-label">SMART Goals</label>
                    <textarea name="smart_goals" id="smart_goals" class="form-control" rows="3">{{ old('smart_goals') }}</textarea>
                    @error('smart_goals')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-12">
                    <label for="metrics_to_track" class="form-label">Metrics to Track</label>
                    <textarea name="metrics_to_track" id="metrics_to_track" class="form-control" rows="3">{{ old('metrics_to_track') }}</textarea>
                    @error('metrics_to_track')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-12">
                    <label for="data_collection_method" class="form-label">Data Collection Method</label>
                    <textarea name="data_collection_method" id="data_collection_method" class="form-control" rows="3">{{ old('data_collection_method') }}</textarea>
                    @error('data_collection_method')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="initial_progress" class="form-label">Initial Progress (%) <span class="text-danger">*</span></label>
                    <input type="number" name="initial_progress" id="initial_progress" class="form-control" value="{{ old('initial_progress', 0) }}" min="0" max="100" required>
                    @error('initial_progress')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="mt-4 text-end">
                <a href="{{ route('cqi_projects.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Save Project</button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleCustomMethodology() {
        const methodologySelect = document.getElementById('methodology');
        const customMethodologyGroup = document.getElementById('custom-methodology-group');
        customMethodologyGroup.style.display = methodologySelect.value === 'Other' ? 'block' : 'none';
    }

    // Run on page load to handle old input or edit scenarios
    document.addEventListener('DOMContentLoaded', toggleCustomMethodology);
</script>
@endsection