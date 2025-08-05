@extends('layouts.dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Edit Dynamic Checklist for Audit: {{ $audit->name }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('audits.checklist.update', $audit->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label class="form-label">Checklist Name (Auto-generated from Audit)</label>
                            <input type="text" class="form-control" value="{{ $audit->name }}" readonly>
                            <small class="text-muted">This will be the checklist name, matching your audit name</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Checklist Sections</label>
                            <div id="sections-container">
                                @php
                                    $sections = [];
                                    foreach($checklist->items as $item) {
                                        $sections[$item->section][] = $item;
                                    }
                                @endphp
                                
                                @foreach($sections as $sectionName => $items)
                                    <div class="section-item mb-3 border p-3 rounded">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <input type="text" name="sections[{{ $loop->index }}][name]" class="form-control" value="{{ $sectionName }}" placeholder="Section Name" required>
                                            </div>
                                            <div class="col-md-8">
                                                <textarea name="sections[{{ $loop->index }}][description]" class="form-control" placeholder="Section Description (optional)"></textarea>
                                            </div>
                                        </div>
                                        
                                        <div class="questions-container mt-2">
                                            @foreach($items as $item)
                                                <div class="question-item mb-2">
                                                    <div class="input-group">
                                                        <input type="text" name="sections[{{ $loop->parent->index }}][questions][{{ $loop->index }}][text]" class="form-control" value="{{ $item->question_text }}" placeholder="Question text" required>
                                                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeQuestion(this)">Remove</button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        
                                        <button type="button" class="btn btn-outline-primary btn-sm mt-2" onclick="addQuestion(this)">Add Question</button>
                                    </div>
                                @endforeach
                            </div>
                            
                            <button type="button" class="btn btn-primary mt-3" onclick="addSection()">Add Section</button>
                        </div>

                        <div class="mb-3">
                            <button type="submit" class="btn btn-success">Update Checklist</button>
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
let sectionIndex = {{ count($sections) }};

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
                <div class="input-group">
                    <input type="text" name="sections[${sectionIndex}][questions][0][text]" class="form-control" placeholder="Question text" required>
                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeQuestion(this)">Remove</button>
                </div>
            </div>
        </div>
        
        <button type="button" class="btn btn-outline-primary btn-sm mt-2" onclick="addQuestion(this)">Add Question</button>
    `;
    container.appendChild(sectionDiv);
    sectionIndex++;
}

function addQuestion(button) {
    const questionsContainer = button.previousElementSibling;
    const questionIndex = questionsContainer.children.length;
    const sectionIndex = Array.from(document.querySelectorAll('.section-item')).indexOf(button.closest('.section-item'));
    
    const questionDiv = document.createElement('div');
    questionDiv.className = 'question-item mb-2';
    questionDiv.innerHTML = `
        <div class="input-group">
            <input type="text" name="sections[${sectionIndex}][questions][${questionIndex}][text]" class="form-control" placeholder="Question text" required>
            <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeQuestion(this)">Remove</button>
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
