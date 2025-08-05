@extends('layouts.dashboard')

@section('title', 'Edit Checklist Submission')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-edit me-2"></i>
                        Edit Checklist Submission: {{ $submission->checklist->title }}
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('checklists.submissions.update', $submission->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="department_name" class="form-label">Department Name</label>
                            <input type="text" 
                                   class="form-control @error('department_name') is-invalid @enderror" 
                                   id="department_name" 
                                   name="department_name" 
                                   value="{{ old('department_name', $submission->department_name) }}" 
                                   required>
                            @error('department_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <h5>Checklist Items</h5>
                            <p class="text-muted">Please review and update your responses below.</p>
                        </div>

                        @foreach($submission->checklist->items->groupBy('section') as $section => $items)
                            <div class="mb-4">
                                <h6 class="fw-bold text-primary">{{ $section }}</h6>
                                <hr>
                                
                                @foreach($items as $item)
                                    @php
                                        $answer = $submission->answers->where('checklist_item_id', $item->id)->first();
                                    @endphp
                                    
                                    <div class="mb-3 border-start border-primary border-2 ps-3">
                                        <label class="form-label fw-semibold">{{ $item->item_text }}</label>
                                        
                                        <div class="rating-input mb-2">
                                            <label class="form-label">Rating (1-7):</label>
                                            <select class="form-select @error('ratings.' . $item->id) is-invalid @enderror" 
                                                    name="ratings[{{ $item->id }}]" 
                                                    required>
                                                @for($i = 1; $i <= 7; $i++)
                                                    <option value="{{ $i }}" {{ old('ratings.' . $item->id, $answer->rating ?? '') == $i ? 'selected' : '' }}>
                                                        {{ $i }} - {{ $i == 1 ? 'Poor' : ($i == 7 ? 'Excellent' : '') }}
                                                    </option>
                                                @endfor
                                            </select>
                                            @error('ratings.' . $item->id)
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="form-label">Comments (optional):</label>
                                            <textarea class="form-control @error('comments.' . $item->id) is-invalid @enderror" 
                                                      name="comments[{{ $item->id }}]" 
                                                      rows="2">{{ old('comments.' . $item->id, $answer->comments ?? '') }}</textarea>
                                            @error('comments.' . $item->id)
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('checklists.results.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Cancel
                            </a>
                            <div>
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="fas fa-save me-1"></i> Update Submission
                                </button>
                                <a href="{{ route('checklists.results.show', $submission->id) }}" class="btn btn-outline-primary">
                                    <i class="fas fa-eye me-1"></i> View Results
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
