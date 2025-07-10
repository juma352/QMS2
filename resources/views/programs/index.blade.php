@extends('layouts.dashboard')

@section('title', 'Programs List')

@section('content')
    <div class="container-fluid py-4">
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between p-3">
                {{-- Card Header: Title and Add Button --}}
                <h2 class="h5 mb-0 fw-semibold">All Programs</h2>
                <a href="{{ route('programs.create') }}" class="btn btn-outline-light btn-sm fw-medium">
                    <i class="fas fa-plus me-2"></i>Add New Program
                </a>
            </div>

            <div class="card-body">
                {{-- Success Message Alert --}}
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show rounded-3 border-0" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Program Name</th>
                            <th>Coordinator</th>
                            <th>Subdivision</th>
                            <th>Renewal Date</th>
                            <th>Approval Date</th>
                            <th class="text-end">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($programs as $program)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <div class="fw-bold">{{ $program->program_name }}</div>
                                    <small class="text-muted">{{ $program->program_abbr }}</small>
                                </td>
                                <td>
                                    {{-- Correctly display the faculty member's name --}}
                                    {{ $program->facultyMember ? $program->facultyMember->first_name . ' ' . $program->facultyMember->last_name : 'N/A' }}
                                </td>
                                <td>
                                    {{-- Correctly display the subdivision's name --}}
                                    {{ $program->school?->subdivision?->name ?? 'N/A' }}
                                </td>
                                <td>
                                    {{-- Format dates for readability --}}
                                    {{ $program->license_renewal_date ? \Carbon\Carbon::parse($program->license_renewal_date)->format('d M, Y') : 'N/A' }}
                                </td>
                                <td>
                                    {{ $program->next_approval_date ? \Carbon\Carbon::parse($program->next_approval_date)->format('d M, Y') : 'N/A' }}
                                </td>
                                <td class="text-end">
                                    {{-- Action Buttons --}}
                                    <div class="btn-group">
                                        <a href="{{ route('programs.show', $program->id) }}" class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('programs.edit', $program->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('programs.destroy', $program->id) }}" method="POST" onsubmit="return confirm('Are you sure?');" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">No programs found.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        .card {
            border-radius: 0.75rem;
        }
        .card-header {
            border-bottom: none;
            border-radius: 0.75rem 0.75rem 0 0;
        }
        .table {
            font-size: 0.9rem;
        }
        .table thead th {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.5px;
            color: #6c757d;
        }
        .table tbody td {
            vertical-align: middle;
        }
        .table-hover tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.02);
        }
        .btn-group .btn {
            border-radius: 0.3rem; /* Uniform radius */
        }
        .btn-group .btn:not(:last-child) {
            margin-right: 0.4rem;
        }
    </style>
@endsection
