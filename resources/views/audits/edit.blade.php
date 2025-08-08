@extends('layouts.dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-edit me-2"></i>
                        Edit Dynamic Checklist
                    </h4>
                    <div>
                        <a href="{{ route('checklists.dynamic_index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>Back to List
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <form id="dynamicChecklistForm" action="{{ route('checklists.dynamic_update', ['id' => $checklist->id]) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Checklist Header -->
                        <div class="row mb-4">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Checklist Title *</label>
                                    <input type="text" 
                                           name="checklist_title" 
                                           class="form-control form-control-lg @error('checklist_title') is-invalid @enderror" 
                                           value="{{ old('checklist_title', $checklist->title) }}" 
                                           placeholder="Enter checklist title"
                                           required>
                                    @error('checklist_title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea name="checklist_description" 
                                              class="form-control @error('checklist_description') is-invalid @enderror" 
                                              rows="3" 
                                              placeholder="Brief description of this checklist">{{ old('checklist_description', $checklist->description) }}</textarea>
                                    @error('checklist_description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title">Audit Information</h6>
                                        <p class="mb-1"><strong>Audit:</strong> {{ $auditChecklist->audit->audit_name }}</p>
                                        <p class="mb-1"><strong>Type:</strong> {{ $auditChecklist->audit->audit_type ?? 'Standard' }}</p>
                                        <p class="mb-0"><strong>Created:</strong> {{ $checklist->created_at->format('M d, Y') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sections Management -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0">
                                    <i class="fas fa-layer-group me-2"></i>
                                    Checklist Sections
                                </h5>
                                <button type="button" class="btn btn-primary btn-sm" onclick="addSection()">
                                    <i class="fas fa-plus me-1"></i>Add Section
                                </button>
                            </div>

                            <div id="sections-container" class="sortable-container">
                                @foreach($itemsBySection as $sectionName => $items)
                                    <div class="section-card mb-3 border rounded" data-section-index="{{ $loop->index }}">
                                        <div class="section-header bg-light p-3 d-flex justify-content-between align-items-center">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-grip-vertical text-muted me-2 cursor-move"></i>
                                                <input type="text" 
                                                       name="sections[{{ $loop->index }}][name]" 
                                                       class="form-control form-control-sm section-name" 
                                                       value="{{ old("sections.{$loop->index}.name", $sectionName) }}" 
                                                       placeholder="Section Name"
                                                       required>
                                            </div>
                                            <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeSection(this)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>

                                        <div class="section-body p-3">
                                            <div class="questions-container">
                                                @foreach($items as $index => $item)
                                                    <div class="question-item mb-2">
                                                        <div class="input-group">
                                                            <input type="text" 
                                                                   name="sections[{{ $loop->parent->index }}][questions][{{ $loop->index }}][text]" 
                                                                   class="form-control" 
                                                                   value="{{ old("sections.{$loop->parent->index}.questions.{$loop->index}.text", $item->question_text) }}"
                                                                   placeholder="Question text" 
                                                                   required>
                                                            <select name="sections[{{ $loop->parent->index }}][questions][{{ $loop->index }}][rating_type]" 
                                                                    class="form-select" 
                                                                    required>
                                                                <option value="scale_1_5" {{ $item->rating_type == 'scale_1_5' ? 'selected' : '' }}>Scale 1-5</option>
                                                                <option value="scale_1_7" {{ $item->rating_type == 'scale_1_7' ? 'selected' : '' }}>Scale 1-7</option>
                                                                <option value="scale_1_10" {{ $item->rating_type == 'scale_1_10' ? 'selected' : '' }}>Scale 1-10</option>
                                                                <option value="yes_no" {{ $item->rating_type == 'yes_no' ? 'selected' : '' }}>Yes/No</option>
                                                                <option value="pass_fail" {{ $item->rating_type == 'pass_fail' ? 'selected' : '' }}>Pass/Fail</option>
                                                            </select>
                                                            <button type="button" class="btn btn-outline-danger" onclick="removeQuestion(this)">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <button type="button" class="btn btn-outline-primary btn-sm mt-2" onclick="addQuestion(this)">
                                                <i class="fas fa-plus me-1"></i>Add Question
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function addQuestion(button) {
    const questionsContainer = button.previousElementSibling;
    const sectionCard = button.closest('.section-card');
    const sectionIndex = sectionCard.dataset.sectionIndex;
    const questionIndex = questionsContainer.children.length;
    
    const questionDiv = document.createElement('div');
    questionDiv.className = 'question-item mb-2';
    questionDiv.innerHTML = `
        <div class="input-group">
            <input type="text" 
                   name="sections[${sectionIndex}][questions][${questionIndex}][text]" 
                   class="form-control" 
                   placeholder="Question text" 
                   required>
            <select name="sections[${sectionIndex}][questions][${questionIndex}][rating_type]" 
                    class="form-select" 
                    required>
                <option value="scale_1_5">Scale 1-5</option>
                <option value="scale_1_7">Scale 1-7</option>
                <option value="scale_1_10">Scale 1-10</option>
                <option value="yes_no">Yes/No</option>
                <option value="pass_fail">Pass/Fail</option>
            </select>
            <button type="button" class="btn btn-outline-danger" onclick="removeQuestion(this)">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    `;
    questionsContainer.appendChild(questionDiv);
}

function removeQuestion(button) {
    button.closest('.question-item').remove();
}

function addSection() {
    const container = document.getElementById('sections-container');
    const sectionIndex = container.children.length;
    
    const sectionDiv = document.createElement('div');
    sectionDiv.className = 'section-card mb-3 border rounded';
    sectionDiv.dataset.sectionIndex = sectionIndex;
    sectionDiv.innerHTML = `
        <div class="section-header bg-light p-3 d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <i class="fas fa-grip-vertical text-muted me-2 cursor-move"></i>
                <input type="text" 
                       name="sections[${sectionIndex}][name]" 
                       class="form-control form-control-sm section-name" 
                       placeholder="Section Name"
                       required>
            </div>
            <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeSection(this)">
                <i class="fas fa-trash"></i>
            </button>
        </div>
        <div class="section-body p-3">
            <div class="questions-container"></div>
            <button type="button" class="btn btn-outline-primary btn-sm mt-2" onclick="addQuestion(this)">
                <i class="fas fa-plus me-1"></i>Add Question
            </button>
        </div>
    `;
    container.appendChild(sectionDiv);
}

function removeSection(button) {
    button.closest('.section-card').remove();
}
</script>
@endpush