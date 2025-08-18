@extends('layouts.dashboard')

@section('title', 'Submit Checklist')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h4 class="card-title mb-0">Submit Checklist - {{ $auditChecklist->checklist->title }}</h4>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('checklists.submit', $auditChecklist->checklist->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5>Audit Details</h5>
                        <p><strong>Audit Name:</strong> {{ $auditChecklist->audit->audit_name }}</p>
                        <p><strong>Department:</strong> 
                            <input type="text" 
                                   name="department_name" 
                                   class="form-control" 
                                   value=""
                                   placeholder="Enter department name (e.g., HR, Finance, IT, etc.)"
                                   required>                        </p>
                        <p><strong>Progress:</strong> {{ $auditChecklist->progress_percentage }}%</p>
                    </div>
                </div>

                <!-- Questions Section -->
               <!-- Questions Section -->
<div class="questions-section mt-4">
    <h5 class="border-bottom pb-2">Rate Questions & Provide Responses</h5>

    @foreach($itemsBySection as $section => $items)
        <div class="section-block mt-4">
            <h6 class="fw-bold">{{ $section }}</h6>

            @foreach($items as $item)
                <div class="question-block border-bottom py-3">
                    <p class="mb-2"><strong>Q: {{ $item->question_text }}</strong></p>

                    <div class="response-details">
                        <div class="form-group mb-3">
                            <label class="form-label">Rating <span class="text-danger">*</span></label>

                            @php
                                $options = $item->getRatingOptions();
                                $selected = $item->response->rating ?? null;
                            @endphp

                            @if(in_array($item->rating_type, ['scale_1_5', 'scale_1_7', 'scale_1_10', 'custom']))
                                <select name="items[{{ $item->id }}][rating]" 
                                        class="form-control rating-select" 
                                        required>
                                    <option value="">Select Rating</option>
                                    @foreach($options as $value => $label)
                                        <option value="{{ $value }}" {{ $selected == $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>

                            @elseif($item->rating_type == 'yes_no')
                                <select name="items[{{ $item->id }}][rating]" 
                                        class="form-control rating-select" 
                                        required>
                                    <option value="">Select Response</option>
                                    <option value="1" {{ $selected == '1' ? 'selected' : '' }}>Yes</option>
                                    <option value="0" {{ $selected == '0' ? 'selected' : '' }}>No</option>
                                </select>

                            @elseif($item->rating_type == 'pass_fail')
                                <select name="items[{{ $item->id }}][rating]" 
                                        class="form-control rating-select" 
                                        required>
                                    <option value="">Select Result</option>
                                    <option value="1" {{ $selected == '1' ? 'selected' : '' }}>Pass</option>
                                    <option value="0" {{ $selected == '0' ? 'selected' : '' }}>Fail</option>
                                </select>

                            @else
                                <input type="number" 
                                       name="items[{{ $item->id }}][rating]" 
                                       class="form-control" 
                                       min="1" 
                                       max="5" 
                                       value="{{ $selected }}"
                                       required>
                            @endif

                            @if($selected)
                                <p class="mt-2 text-muted">
                                    Selected: <strong>{{ $item->getRatingLabel() }}</strong>
                                </p>
                            @endif
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label">Notes</label>
                            <textarea name="items[{{ $item->id }}][notes]" 
                                      class="form-control" 
                                      rows="2"
                                      placeholder="Add any additional notes...">{{ $item->response->notes ?? '' }}</textarea>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label">Evidence (Optional)</label>
                            <input type="file" 
                                   name="items[{{ $item->id }}][evidence]" 
                                   class="form-control">
                            @if(isset($item->response->evidence))
                                <small class="form-text text-muted">
                                    Current: <a href="{{ Storage::url($item->response->evidence) }}" target="_blank">View Evidence</a>
                                </small>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endforeach
</div>


                <!-- Final Comments -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="comments">Final Comments</label>
                            <textarea name="comments" id="comments" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                </div>

                <div class="alert alert-warning mt-4">
                    <i class="fas fa-exclamation-triangle"></i>
                    Please note: Once submitted, this checklist cannot be modified.
                </div>

                <div class="text-end mt-4">
                    <a href="{{ route('checklists.dynamic_index') }}" class="btn btn-secondary">Cancel</a>

                    <button type="submit" class="btn btn-success">
                        
                        <i class="fas fa-check"></i> Confirm Submission
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .question-block:last-child {
        border-bottom: none !important;
    }
    .response-details {
        margin-left: 1.5rem;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form validation
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const requiredFields = document.querySelectorAll('.rating-select[required]');
        let allFilled = true;
        
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
            alert('Please rate all questions before submitting.');
            return false;
        }
    });
    
    // Real-time validation
    document.querySelectorAll('.rating-select').forEach(function(select) {
        select.addEventListener('change', function() {
            if (this.value) {
                this.classList.remove('is-invalid');
            }
        });
    });
});
</script>
@endpush
