@extends('layouts.dashboard')

@section('title', 'Dynamic Checklist - ' . $auditChecklist->checklist->title)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Checklist Header -->
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title">{{ $auditChecklist->checklist->title }}</h4>
                            <p class="text-muted mb-0">Generated from audit: {{ $auditChecklist->audit->audit_name }}</p>
                        </div>
                        <div>
                            <span class="badge badge-{{ $auditChecklist->status == 'completed' ? 'success' : ($auditChecklist->status == 'in_progress' ? 'info' : 'warning') }}">
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
                            <p><strong>Department:</strong> {{ $auditChecklist->department_name ?? 'All Departments' }}</p>
                        </div>
                        <div class="col-md-6">
                            <h5>Progress</h5>
                            <p><strong>Status:</strong> {{ ucfirst($auditChecklist->status) }}</p>
                            <p><strong>Progress:</strong> {{ $progressPercentage ?? 0 }}%</p>
                            <div class="progress mb-3">
                                <div class="progress-bar" role="progressbar" 
                                     style="width: {{ $progressPercentage ?? 0 }}%" 
                                     aria-valuenow="{{ $progressPercentage ?? 0 }}" 
                                     aria-valuemin="0" 
                                     aria-valuemax="100">
                                    {{ $progressPercentage ?? 0 }}%
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Checklist Items -->
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Checklist Items</h4>
                    <p class="text-muted">Complete each item below and submit when finished</p>
                </div>
                <div class="card-body">
                    <form id="checklistForm" method="POST" action="{{ route('checklists.store', $auditChecklist->checklist->id) }}">
                        @csrf
                        
                        @php
                            $currentSection = '';
                            $itemCount = 0;
                        @endphp
                        
                        @foreach($auditChecklist->checklist->items as $item)
                            @if($item->section != $currentSection)
                                @php $currentSection = $item->section; @endphp
                                <div class="section-header mt-4 mb-3">
                                    <h5 class="text-primary">{{ $item->section }}</h5>
                                    <hr>
                                </div>
                            @endif
                            
                            @php $itemCount++; @endphp
                            <div class="checklist-item mb-4" data-item-id="{{ $item->id }}">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label class="font-weight-bold">
                                                {{ $itemCount }}. {{ $item->question_text }}
                                            </label>
                                            @if($item->rating_notes)
                                                <small class="form-text text-muted">{{ $item->rating_notes }}</small>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Rating</label>
                                            <select name="items[{{ $item->id }}][rating]" 
                                                    class="form-control rating-select" 
                                                    required>
                                                <option value="">Select Rating</option>
                                                @if($item->rating_type == 'scale_1_5')
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <option value="{{ $i }}">{{ $i }} - {{ $i == 5 ? 'Excellent' : ($i == 4 ? 'Good' : ($i == 3 ? 'Average' : ($i == 2 ? 'Poor' : 'Very Poor'))) }}</option>
                                                    @endfor
                                                @elseif($item->rating_type == 'scale_1_7')
                                                    @for($i = 1; $i <= 7; $i++)
                                                        <option value="{{ $i }}">{{ $i }}</option>
                                                    @endfor
                                                @elseif($item->rating_type == 'scale_1_10')
                                                    @for($i = 1; $i <= 10; $i++)
                                                        <option value="{{ $i }}">{{ $i }}</option>
                                                    @endfor
                                                @elseif($item->rating_type == 'yes_no')
                                                    <option value="yes">Yes</option>
                                                    <option value="no">No</option>
                                                @elseif($item->rating_type == 'pass_fail')
                                                    <option value="pass">Pass</option>
                                                    <option value="fail">Fail</option>
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Comments/Remarks</label>
                                            <textarea name="items[{{ $item->id }}][comments]" 
                                                      class="form-control" 
                                                      rows="2" 
                                                      placeholder="Add any additional comments or remarks..."></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        
                        <div class="form-group mt-4">
                            <label for="overall_comments">Overall Comments</label>
                            <textarea name="overall_comments" 
                                      class="form-control" 
                                      rows="3" 
                                      placeholder="Add any overall comments about this checklist..."></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="submitted_by">Submitted By</label>
                            <input type="text" 
                                   class="form-control" 
                                   value="{{ auth()->user()->name }}" 
                                   readonly>
                        </div>
                        
                        <div class="form-group">
                            <label for="submission_date">Submission Date</label>
                            <input type="date" 
                                   name="submission_date" 
                                   class="form-control" 
                                   value="{{ now()->format('Y-m-d') }}" 
                                   required>
                        </div>
                        
                        <div class="text-right mt-4">
                            @if($auditChecklist->status != 'completed')
                                <button type="submit" 
                                        class="btn btn-success btn-lg" 
                                        onclick="return confirm('Are you sure you want to submit this checklist?')">
                                    <i class="fas fa-check"></i> Submit Checklist
                                </button>
                            @else
                                <span class="badge badge-success">
                                    <i class="fas fa-check-circle"></i> Already Submitted
                                </span>
                            @endif
                            
                            <a href="{{ route('dynamic-checklists.index') }}" 
                               class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back to Checklists
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="btn-group" role="group">
                                <a href="{{ route('dynamic-checklists.index') }}" 
                                   class="btn btn-secondary">
                                    <i class="fas fa-list"></i> Back to All Checklists
                                </a>
                                
                                <a href="{{ route('audits.show', $auditChecklist->audit->id) }}" 
                                   class="btn btn-info">
                                    <i class="fas fa-file-alt"></i> View Related Audit
                                </a>
                                
                                <a href="{{ route('dynamic-checklists.show', $auditChecklist->id) }}?print=true" 
                                   class="btn btn-primary" 
                                   target="_blank">
                                    <i class="fas fa-print"></i> Print Checklist
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Form validation
    document.getElementById('checklistForm').addEventListener('submit', function(e) {
        let allFilled = true;
        const requiredFields = document.querySelectorAll('.rating-select[required]');
        
        requiredFields.forEach(function(field) {
            if (!field.value) {
                allFilled = false;
                field.classList.add('is-invalid');
            } else {
                field.classList.remove('is-invalid');
            }
        });
        
        if (!allFilled) {
            e.preventDefault();
            alert('Please complete all required fields before submitting.');
            return false;
        }
        
        return true;
    });

    // Progress tracking
    document.querySelectorAll('.rating-select').forEach(function(select) {
        select.addEventListener('change', function() {
            updateProgress();
        });
    });

    function updateProgress() {
        const totalItems = document.querySelectorAll('.rating-select').length;
        const completedItems = document.querySelectorAll('.rating-select').length - 
                              document.querySelectorAll('.rating-select:invalid').length;
        
        const percentage = Math.round((completedItems / totalItems) * 100);
        
        // Update progress bar
        const progressBar = document.querySelector('.progress-bar');
        if (progressBar) {
            progressBar.style.width = percentage + '%';
            progressBar.textContent = percentage + '%';
        }
    }
</script>
@endpush
@endsection
