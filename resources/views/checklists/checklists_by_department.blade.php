@extends('layouts.dashboard')

@section('title', 'Checklists Submitted by ' . $department)

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
        <h2 class="h5 mb-0">Checklists Submitted by {{ $department }}</h2>
    </div>
    <div class="card-body">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Checklist Title</th>
                    <th>Submissions</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($checklists as $entry)
                    <tr>
                        <td>{{ $entry->checklist->title }}</td>
                        <td>{{ $entry->submission_count }}</td>
                        <td class="text-center">
                            <a href="{{ route('checklists.submissionsByDepartmentChecklist', [
                                'department' => $department,
                                'checklist_id' => $entry->checklist_id
                            ]) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-eye me-1"></i> View Submissions
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
