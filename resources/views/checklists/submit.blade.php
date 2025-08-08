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
                                            
                                            @if($item->rating_type == 'scale_1_5')
                                                <select name="items[{{ $item->id }}][rating]" 
                                                        class="form-control rating-select" 
                                                        required>
                                                    <option value="">Select Rating (1-5)</option>
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <option value="{{ $i }}" {{ isset($item->response->rating) && $item->response->rating == $i ? 'selected' : '' }}>
                                                            {{ $i }} - {{ $i == 1 ? 'Poor' : ($i == 2 ? 'Fair' : ($i == 3 ? 'Good' : ($i == 4 ? 'Very Good' : 'Excellent'))) }}
                                                        </option>
                                                    @endfor
                                                </select>
                                                
                                            @elseif($item->rating_type == 'scale_1_7')
                                                <select name="items[{{ $item->id }}][rating]" 
                                                        class="form-control rating-select" 
                                                        required>
                                                    <option value="">Select Rating (1-7)</option>
                                                    @for($i = 1; $i <= 7; $i++)
                                                        <option value="{{ $i }}" {{ isset($item->response->rating) && $item->response->rating == $i ? 'selected' : '' }}>
                                                            {{ $i }}
                                                        </option>
                                                    @endfor
                                                </select>
                                                
                                            @elseif($item->rating_type == 'scale_1_10')
                                                <select name="items[{{ $item->id }}][rating]" 
                                                        class="form-control rating-select" 
                                                        required>
                                                    <option value="">Select Rating (1-10)</option>
                                                    @for($i = 1; $i <= 10; $i++)
                                                        <option value="{{ $i }}" {{ isset($item->response->rating) && $item->response->rating == $i ? 'selected' : '' }}>
                                                            {{ $i }}
                                                        </option>
                                                    @endfor
                                                </select>
                                                
                                            @elseif($item->rating_type == 'yes_no')
                                                <select name="items[{{ $item->id }}][rating]" 
                                                        class="form-control rating-select" 
                                                        required>
                                                    <option value="">Select Response</option>
                                                    <option value="yes" {{ isset($item->response->rating) && $item->response->rating == 'yes' ? 'selected' : '' }}>Yes</option>
                                                    <option value="no" {{ isset($item->response->rating) && $item->response->rating == 'no' ? 'selected' : '' }}>No</option>
                                                </select>
                                                
                                            @elseif($item->rating_type == 'pass_fail')
                                                <select name="items[{{ $item->id }}][rating]" 
                                                        class="form-control rating-select" 
                                                        required>
                                                    <option value="">Select Result</option>
                                                    <option value="pass" {{ isset($item->response->rating) && $item->response->rating == 'pass' ? 'selected' : '' }}>Pass</option>
                                                    <option value="fail" {{ isset($item->response->rating) && $item->response->rating == 'fail' ? 'selected' : '' }}>Fail</option>
                                                </select>
                                                
                                            @else
                                                <input type="number" 
                                                       name="items[{{ $item->id }}][rating]" 
                                                       class="form-control" 
                                                       min="1" 
                                                       max="5" 
                                                       value="{{ $item->response->rating ?? '' }}"
                                                       required>
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
