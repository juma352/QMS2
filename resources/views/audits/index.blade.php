@extends('layouts.dashboard')

@section('title', $auditType . ' Audits')

@section('content')
<div class="card shadow-sm border-0 rounded-3">
    <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between p-3">
        <h2 class="h5 mb-0 fw-semibold">{{ $auditType }} Audit List</h2>
        {{-- This button correctly passes the current type to the create page --}}
        <a href="{{ route('audits.create', ['type' => $auditType]) }}" class="btn btn-outline-light btn-sm fw-medium">
            <i class="fas fa-plus-circle me-2"></i>Add New {{ $auditType }} Audit
        </a>
    </div>

    <div class="card-body p-4">
        @if(session('message'))
            <div class="alert alert-success alert-dismissible fade show rounded-3 border-0" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th scope="col">Audit Name</th>
                        <th scope="col">Audit Number</th>
                        <th scope="col">Standard</th>
                        <th scope="col">Date Conducted</th>
                        <th scope="col">Next Audit Date</th>
                        <th scope="col">Status</th>
                        <th scope="col" class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- The @forelse loop is crucial. It shows data if it exists, or an "empty" message if not. --}}
                    @forelse ($audits as $audit)
                        <tr>
                            <td>{{ $audit->audit_name }}</td>
                            <td>{{ $audit->audit_number ?? 'N/A' }}</td>
                            {{-- We safely access the related standard's name --}}
                            <td>{{ $audit->standard->standard_name ?? 'N/A' }}</td>
                            <td>{{ $audit->date_conducted ? $audit->date_conducted->format('d M, Y') : 'N/A' }}</td>
                            <td>{{ $audit->next_audit_date ? $audit->next_audit_date->format('d M, Y') : 'N/A' }}</td>
                            <td><span class="badge bg-info text-dark">{{ $audit->status }}</span></td>
                            <td class="text-center">
                                <div class="d-inline-flex gap-2">
                                    <a href="{{ route('audits.show', $audit->id) }}" class="btn btn-outline-info btn-sm" title="View"><i class="fas fa-eye"></i></a>
                                    <a href="{{ route('audits.edit', $audit->id) }}" class="btn btn-outline-warning btn-sm" title="Edit"><i class="fas fa-pencil-alt"></i></a>
                                    {{-- <form action="{{ route('audits.destroy', $audit->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm" title="Delete"><i class="fas fa-trash-alt"></i></button>
                                    </form> --}}
                                </div>
                            </td>
                        </tr>
                    @empty
                        {{-- This message will show if the $audits collection is empty --}}
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="fas fa-folder-open fa-2x mb-2"></i>
                                <p class="mb-0">No {{ $auditType }} Audits found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($audits->hasPages())
            <div class="mt-4 d-flex justify-content-center">
                {{-- This renders pagination links and preserves the ?type=... query string --}}
                {{ $audits->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>
@endsection