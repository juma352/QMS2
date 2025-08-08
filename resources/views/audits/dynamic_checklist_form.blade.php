@extends('layouts.dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Generate Checklist for Audit: {{ $audit->audit_name }}</h4>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <h5 class="alert-heading"><i class="fas fa-exclamation-triangle me-2"></i>Please fix the following errors:</h5>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    @if(session('success'))
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                        </div>
                    @endif

                    <form action="{{ route('audits.checklist.store', $audit->id) }}" method="POST" id="checklistForm">
                        @csrf
                        <input type="hidden" name="audit_id" value="{{ $audit->id }}">

                        <div class="mb-3">
                            <label class="form-label">Checklist Name</label>
                            <input type="text" name="checklist_title" id="checklist_title" class="form-control" value="{{ old('checklist_title', $audit->audit_name) }}" required readonly>
                            <small class="text-muted">This will be the checklist name, matching your audit name</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Checklist Description</label>
                            <textarea name="checklist_description" id="checklist_description" class="form-control" rows="2" placeholder="Brief description of this checklist">{{ old('checklist_description', 'Dynamic checklist generated for audit: ' . $audit->audit_name) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Checklist Sections and Questions</label>
                            <div id="sections-container">
                                <!-- Initial section -->
                                <div class="section-item mb-4 border p-3 rounded" data-section-index="0">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Section Name</label>
                                            <input type="text" name="sections[0][name]" class="form-control" placeholder="e.g., Documentation Review" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Section Description (Optional)</label>
                                            <input type="text" name="sections[0][description]" class="form-control" placeholder="Brief description of this section">
                                        </div>
                                    </div>
                                    
                                    <div class="questions-container">
                                        <div class="question-item mb-3 border-start border-primary border-3 ps-3" data-question-index="0">
                                            <div class="row mb-2">
                                                <div class="col-md-8">
                                                    <label class="form-label">Question Text</label>
                                                    <textarea name="sections[0][questions][0][text]" class="form-control" rows="2" placeholder="Enter your question here..." required></textarea>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Rating Type</label>
                                                    <select name="sections[0][questions][0][rating_type]" class="form-control">
                                                        <option value="scale_1_5">1-5 Scale</option>
                                                        <option value="scale_1_7" selected>1-7 Scale</option>
                                                        <option value="scale_1_10">1-10 Scale</option>
                                                        <option value="yes_no">Yes/No</option>
                                                        <option value="pass_fail">Pass/Fail</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <label class="form-label">Additional Notes (Optional)</label>
                                                    <input type="text" name="sections[0][questions][0][notes]" class="form-control" placeholder="Any additional context or notes">
                                                </div>
                                                <div class="col-md-4 d-flex align-items-end">
                                                    <button type="button" class="btn btn-outline-danger btn-sm w-100" onclick="removeQuestion(this)">
                                                        <i class="fas fa-trash me-1"></i> Remove Question
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="section-actions mt-3">
                                        <button type="button" class="btn btn-outline-primary btn-sm me-2" onclick="addQuestion(this)">
                                            <i class="fas fa-plus me-1"></i> Add Question
                                        </button>
                                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeSection(this)">
                                            <i class="fas fa-trash me-1"></i> Remove Section
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <button type="button" class="btn btn-primary mt-3" onclick="addSection()">
                                <i class="fas fa-plus me-1"></i> Add New Section
                            </button>
                        </div>

                        <div class="mb-3 border-top pt-3">
                            <button type="submit" class="btn btn-success me-2">
                                <i class="fas fa-check me-1"></i> Generate Checklist
                            </button>
                            <a href="{{ route('audits.show', $audit->id) }}" class="btn btn-secondary">
                                <i class="fas fa-times me-1"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let sectionIndex = 1;

function addSection() {
    const container = document.getElementById('sections-container');
    const sectionDiv = document.createElement('div');
    sectionDiv.className = 'section-item mb-4 border p-3 rounded';
    sectionDiv.setAttribute('data-section-index', sectionIndex);
    
    sectionDiv.innerHTML = `
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Section Name</label>
                <input type="text" name="sections[${sectionIndex}][name]" class="form-control" placeholder="e.g., Process Evaluation" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Section Description (Optional)</label>
                <input type="text" name="sections[${sectionIndex}][description]" class="form-control" placeholder="Brief description of this section">
            </div>
        </div>
        
        <div class="questions-container">
            <div class="question-item mb-3 border-start border-primary border-3 ps-3" data-question-index="0">
                <div class="row mb-2">
                    <div class="col-md-8">
                        <label class="form-label">Question Text</label>
                        <textarea name="sections[${sectionIndex}][questions][0][text]" class="form-control" rows="2" placeholder="Enter your question here..." required></textarea>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Rating Type</label>
                        <select name="sections[${sectionIndex}][questions][0][rating_type]" class="form-control">
                            <option value="scale_1_5">1-5 Scale</option>
                            <option value="scale_1_7" selected>1-7 Scale</option>
                            <option value="scale_1_10">1-10 Scale</option>
                            <option value="yes_no">Yes/No</option>
                            <option value="pass_fail">Pass/Fail</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8">
                        <label class="form-label">Additional Notes (Optional)</label>
                        <input type="text" name="sections[${sectionIndex}][questions][0][notes]" class="form-control" placeholder="Any additional context or notes">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="button" class="btn btn-outline-danger btn-sm w-100" onclick="removeQuestion(this)">
                            <i class="fas fa-trash me-1"></i> Remove Question
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="section-actions mt-3">
            <button type="button" class="btn btn-outline-primary btn-sm me-2" onclick="addQuestion(this)">
                <i class="fas fa-plus me-1"></i> Add Question
            </button>
            <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeSection(this)">
                <i class="fas fa-trash me-1"></i> Remove Section
            </button>
        </div>
    `;
    
    container.appendChild(sectionDiv);
    sectionIndex++;
}

function removeSection(button) {
    const sectionsContainer = document.getElementById('sections-container');
    const sectionItems = sectionsContainer.querySelectorAll('.section-item');
    
    // Prevent removing the last section
    if (sectionItems.length <= 1) {
        alert('You must have at least one section in your checklist.');
        return;
    }
    
    button.closest('.section-item').remove();
    updateSectionIndexes();
}

function addQuestion(button) {
    const sectionDiv = button.closest('.section-item');
    const questionsContainer = sectionDiv.querySelector('.questions-container');
    const sectionIdx = sectionDiv.getAttribute('data-section-index');
    const questionItems = questionsContainer.querySelectorAll('.question-item');
    const questionIndex = questionItems.length;

    const questionDiv = document.createElement('div');
    questionDiv.className = 'question-item mb-3 border-start border-primary border-3 ps-3';
    questionDiv.setAttribute('data-question-index', questionIndex);
    
    questionDiv.innerHTML = `
        <div class="row mb-2">
            <div class="col-md-8">
                <label class="form-label">Question Text</label>
                <textarea name="sections[${sectionIdx}][questions][${questionIndex}][text]" class="form-control" rows="2" placeholder="Enter your question here..." required></textarea>
            </div>
            <div class="col-md-4">
                <label class="form-label">Rating Type</label>
                <select name="sections[${sectionIdx}][questions][${questionIndex}][rating_type]" class="form-control">
                    <option value="scale_1_5">1-5 Scale</option>
                    <option value="scale_1_7" selected>1-7 Scale</option>
                    <option value="scale_1_10">1-10 Scale</option>
                    <option value="yes_no">Yes/No</option>
                    <option value="pass_fail">Pass/Fail</option>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8">
                <label class="form-label">Additional Notes (Optional)</label>
                <input type="text" name="sections[${sectionIdx}][questions][${questionIndex}][notes]" class="form-control" placeholder="Any additional context or notes">
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="button" class="btn btn-outline-danger btn-sm w-100" onclick="removeQuestion(this)">
                    <i class="fas fa-trash me-1"></i> Remove Question
                </button>
            </div>
        </div>
    `;
    
    questionsContainer.appendChild(questionDiv);
}

function removeQuestion(button) {
    const questionsContainer = button.closest('.questions-container');
    const questionItems = questionsContainer.querySelectorAll('.question-item');
    
    // Prevent removing the last question in a section
    if (questionItems.length <= 1) {
        alert('Each section must have at least one question.');
        return;
    }
    
    button.closest('.question-item').remove();
    updateQuestionIndexes(questionsContainer);
}

function updateSectionIndexes() {
    const sections = document.querySelectorAll('.section-item');
    sections.forEach((section, index) => {
        section.setAttribute('data-section-index', index);
        
        // Update all input names in this section
        const inputs = section.querySelectorAll('input, textarea, select');
        inputs.forEach(input => {
            const name = input.getAttribute('name');
            if (name) {
                const newName = name.replace(/sections\[\d+\]/, `sections[${index}]`);
                input.setAttribute('name', newName);
            }
        });
        
        updateQuestionIndexes(section.querySelector('.questions-container'));
    });
}

function updateQuestionIndexes(questionsContainer) {
    const questions = questionsContainer.querySelectorAll('.question-item');
    const sectionIndex = questionsContainer.closest('.section-item').getAttribute('data-section-index');
    
    questions.forEach((question, index) => {
        question.setAttribute('data-question-index', index);
        
        // Update all input names in this question
        const inputs = question.querySelectorAll('input, textarea, select');
        inputs.forEach(input => {
            const name = input.getAttribute('name');
            if (name) {
                const newName = name.replace(/questions\[\d+\]/, `questions[${index}]`);
                input.setAttribute('name', newName);
            }
        });
    });
}

// Form validation before submission
document.getElementById('checklistForm').addEventListener('submit', function(e) {
    const sections = document.querySelectorAll('.section-item');
    
    if (sections.length === 0) {
        e.preventDefault();
        alert('Please add at least one section to your checklist.');
        return;
    }
    
    let hasValidQuestions = false;
    sections.forEach(section => {
        const questions = section.querySelectorAll('.question-item');
        if (questions.length > 0) {
            hasValidQuestions = true;
        }
    });
    
    if (!hasValidQuestions) {
        e.preventDefault();
        alert('Please add at least one question to your checklist.');
        return;
    }
    
    // Show loading state
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Generating Checklist...';
    submitBtn.disabled = true;
    
    // Re-enable button after 10 seconds as fallback
    setTimeout(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    }, 10000);
});
</script>
@endpush
@endsection