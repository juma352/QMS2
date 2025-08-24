@extends('layouts.dashboard')

@section('title', 'All Standards')

@section('content')
<section class="standards-list">
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between p-3 rounded-top-3">
            <h2 class="h5 mb-0 fw-semibold">All Standards</h2>
            <a href="{{ route('standards.create') }}" class="btn btn-outline-light btn-sm fw-medium">
                <i class="fas fa-plus-circle me-2"></i>Add New Standard
            </a>
        </div>

        <div class="card-body p-4">
                        <form action="{{ route('standards.index') }}" method="GET" class="mb-4">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search by standard name or number..." value="{{ request('search') }}">
                    <button class="btn btn-primary" type="submit">Search</button>
                    @if(request('search'))
                        <a href="{{ route('standards.index') }}" class="btn btn-outline-secondary">Clear</a>
                    @endif
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Standard Name</th>
                            <th scope="col">Standard Number</th>
                            <th scope="col">Revision</th>
                            <th scope="col">Date of Issue</th>
                            <th scope="col"> Next Revision Date</th>
                            <th scope="col" class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($standards as $standard)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ $standard->standard_name }}</td>
                                <td>{{ $standard->standard_number }}</td>
                                <td>{{ $standard->revision_version ?? 'N/A' }}</td>
                                <td>{{ $standard->date_of_issue ? \Carbon\Carbon::parse($standard->date_of_issue)->format('d M, Y') : 'N/A' }}</td>
                                <td>{{ $standard->next_revision_date ? \Carbon\Carbon::parse($standard->next_revision_date)->format('d M, Y') : 'N/A' }}</td>
                                <td class="text-center">
                                    <div class="d-inline-flex gap-2">
                                        <a href="{{ route('standards.show', $standard->id) }}" class="btn btn-outline-info btn-sm" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('standards.edit', $standard->id) }}" class="btn btn-outline-warning btn-sm" title="Edit">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                        <form action="{{ route('standards.destroy', $standard->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this standard?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm" title="Delete">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="fas fa-folder-open fa-2x mb-2"></i>
                                    <p class="mb-0">No standards found. Please add a new standard.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($standards->hasPages())
                <div class="mt-4 d-flex justify-content-center">
                    {{ $standards->links() }}
                </div>
            @endif
        </div>
    </div>
</section>
@endsection