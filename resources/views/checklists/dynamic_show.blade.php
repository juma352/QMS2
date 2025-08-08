@extends('layouts.dashboard')

@section('title', 'Dynamic Checklist - ' . $auditChecklist->checklist->title)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title">{{ $auditChecklist->checklist->title }}</h4>
                            <p class="text-muted mb-0">Generated from audit: {{ $auditChecklist->audit->audit_name }}</p>
                        </div>
                        <div>
                            <span class="badge badge-{{ $auditChecklist->status == 'completed' ? 'success' : 'warning' }}">
                                {{ ucfirst($auditChecklist->status) }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Checklist Details</h5>
                            <p><strong>Description:</strong> {{ $auditChecklist->checklist->description }}</p>
                            <p><strong>Generated:</strong> {{ $auditChecklist->generated_at->format('M d, Y H:i') }}</p>
                            <p><strong>Due Date:</strong> {{ $auditChecklist->due_date ? $auditChecklist->due_date->format('M d, Y') : 'No due date' }}</p>
                        </div>
                        <div class="col-md-6">
                            <h5>Progress</h5>
                            <p><strong>Status:</strong> {{ $auditChecklist->status }}</p>
                            <p><strong>Progress:</strong> {{ $progressPercentage ?? 0 }}%</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
