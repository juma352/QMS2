@extends('layouts.dashboard')

@section('title', 'All Programs')

@section('content')
    <div class="container-fluid py-4">
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between p-3 rounded-top-3">
                <h2 class="h5 mb-0 fw-semibold">Manage Programs</h2>
                <a href="{{ route('programs.create') }}" class="btn btn-outline-light btn-sm fw-medium">
                    <i class="fas fa-plus-circle me-2"></i>Add New Program
                </a>
            </div>

            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show rounded-3 border-0" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                        <tr>
                            <th scope="col">Program Name</th>
                            <th scope="col">Abbreviation</th>
                            <th scope="col">School / Category</th>
                            <th scope="col">Subdivision</th>
                            <th scope="col">Head of Program</th>
                            <th scope="col" class="text-center">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($programs as $program)
                            {{-- We only show programs that have been fully configured --}}
                            @if($program->faculty_member_id || $program->program_abbr)
                                <tr>
                                    <td class="fw-medium">{{ $program->program_name }}</td>
                                    <td>
                                        <span class="badge bg-secondary-subtle text-secondary-emphasis rounded-pill">{{ $program->program_abbr ?? 'N/A' }}</span>
                                    </td>
                                    <td>{{ $program->school->name ?? 'N/A' }}</td>
                                    <td>{{ $program->school->subdivision->name ?? 'N/A' }}</td>
                                    <td>{{ $program->facultyMember->first_name ?? 'Not' }} {{ $program->facultyMember->last_name ?? 'Assigned' }}</td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group" aria-label="Program Actions">
                                            <a href="{{ route('programs.show', $program) }}" class="btn btn-sm btn-outline-info" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('programs.edit', $program) }}" class="btn btn-sm btn-outline-warning" title="Edit Program">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('programs.destroy', $program) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this program and all its documents?');" style="display: inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete Program">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">No configured programs found. <a href="{{ route('programs.create') }}">Add one now</a>.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
