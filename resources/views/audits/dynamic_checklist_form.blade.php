@extends('layouts.dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Generate Dynamic Checklist for Audit: {{ $audit->name }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('audits.checklist.store', $audit->id) }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">CheckList Name</label>
                            <input type="text" name="audit_name" id="audit_name" class="form-control" value="{{ old('audit_name', $audit->audit_name ?? $audit->name ?? '') }}" required readonly>
                            <small class="text-muted">This will be the checklist name, matching your audit name</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Checklist Sections</label>
                            <div id="sections-container">
                                <div class="section-item mb-3 border p-3 rounded">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <input type="text" name="sections[0][name]" class="form-control" placeholder="Section Name (e.g., Documentation Review)" required>
                                        </div>
                                        <div class="col-md-8">
                                            <textarea name="sections[0][description]" class="form-control" placeholder="Section Description (optional)"></textarea>
                                        </div>
                                    </div>
                                    <div class="questions-container mt-2">
                                        <div class="question-item mb-2">
                                            <div class="input-group mb-2">
                                                <input type="text" name="sections[0][questions][0][text]" class="form-control" placeholder="Question text" required>
                                                <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeQuestion(this)">Remove</button>
                                            </div>
                                            <div class="rating-section mb-3">
                                                <label class="form-label">Rating Type</label>
                                                <select name="sections[0][questions][0][rating_type]" class="form-control">
                                                    <option value="scale_1_5">1-5 Scale</option>
                                                    <option value="scale_1_10">1-10 Scale</option>
                                                    <option value="yes_no">Yes/No</option>
                                                    <option value="pass_fail">Pass/Fail</option>
                                                </select>
                                                <label class="form-label mt-2">Rating Value</label>
                                                <input type="number" name="sections[0][questions][0][rating_value]" class="form-control" min="1" max="10">
                                                <label class="form-label mt-2">Rating Notes</label>
                                                <textarea name="sections[0][questions][0][rating_notes]" class="form-control" rows="2"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-outline-primary btn-sm mt-2" onclick="addQuestion(this)">Add Question</button>
                                </div>
                            </div>
                            <button type="button" class="btn btn-primary mt-3" onclick="addSection()">Add Section</button>
                           
                        </div>
                        
                        <div class="mb-3">
                            <button type="submit" class="btn btn-success">Generate Checklist</button>
                            <a href="{{ route('audits.show', $audit->id) }}" class="btn btn-secondary">Cancel</a>
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
    sectionDiv.className = 'section-item mb-3 border p-3 rounded';
    sectionDiv.innerHTML = `
        <div class="row">
            <div class="col-md-4">
                <input type="text" name="sections[${sectionIndex}][name]" class="form-control" placeholder="Section Name" required>
            </div>
            <div class="col-md-8">
                <textarea name="sections[${sectionIndex}][description]" class="form-control" placeholder="Section Description (optional)"></textarea>
            </div>
        </div>
        <div class="questions-container mt-2">
            <div class="question-item mb-2">
                <div class="input-group mb-2">
                    <input type="text" name="sections[${sectionIndex}][questions][0][text]" class="form-control" placeholder="Question text" required>
                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeQuestion(this)">Remove</button>
                </div>
                <div class="rating-section mb-3">
                    <label class="form-label">Rating Type</label>
                    <select name="sections[${sectionIndex}][questions][0][rating_type]" class="form-control">
                        <option value="scale_1_5">1-5 Scale</option>
                        <option value="scale_1_10">1-10 Scale</option>
                        <option value="yes_no">Yes/No</option>
                        <option value="pass_fail">Pass/Fail</option>
                    </select>
                    <label class="form-label mt-2">Rating Value</label>
                    <input type="number" name="sections[${sectionIndex}][questions][0][rating_value]" class="form-control" min="1" max="10">
                    <label class="form-label mt-2">Rating Notes</label>
                    <textarea name="sections[${sectionIndex}][questions][0][rating_notes]" class="form-control" rows="2"></textarea>
                </div>
            </div>
        </div>
        <button type="button" class="btn btn-outline-primary btn-sm mt-2" onclick="addQuestion(this)">Add Question</button>
        <button type="button" class="btn btn-outline-danger btn-sm mt-2" onclick="removeSection(this)">Remove Section</button>
    `;
    container.appendChild(sectionDiv);
    sectionIndex++;
}
function removeSection(button) {
    button.closest('.section-item').remove();
    
}

function addQuestion(button) {
    const sectionDiv = button.closest('.section-item');
    const questionsContainer = sectionDiv.querySelector('.questions-container');
    const sectionIdx = Array.from(document.querySelectorAll('.section-item')).indexOf(sectionDiv);
    const questionIndex = questionsContainer.querySelectorAll('.question-item').length;

    const questionDiv = document.createElement('div');
    questionDiv.className = 'question-item mb-2';
    questionDiv.innerHTML = `
        <div class="input-group mb-2">
            <input type="text" name="sections[${sectionIdx}][questions][${questionIndex}][text]" class="form-control" placeholder="Question text" required>
            <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeQuestion(this)">Remove</button>
        </div>
        <div class="rating-section mb-3">
            <label class="form-label">Rating Type</label>
            <select name="sections[${sectionIdx}][questions][${questionIndex}][rating_type]" class="form-control">
                <option value="scale_1_5">1-5 Scale</option>
                <option value="scale_1_10">1-10 Scale</option>
                <option value="yes_no">Yes/No</option>
                <option value="pass_fail">Pass/Fail</option>
            </select>
            <label class="form-label mt-2">Rating Value</label>
            <input type="number" name="sections[${sectionIdx}][questions][${questionIndex}][rating_value]" class="form-control" min="1" max="10">
            <label class="form-label mt-2">Rating Notes</label>
            <textarea name="sections[${sectionIdx}][questions][${questionIndex}][rating_notes]" class="form-control" rows="2"></textarea>
        </div>
    `;
    questionsContainer.appendChild(questionDiv);
}

function removeQuestion(button) {
    button.closest('.question-item').remove();
}
</script>
@endpush
@endsection
