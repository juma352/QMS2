@extends('layouts.dashboard')

@section('title', 'Add New Program')

@section('content')
    <div class="container-fluid py-4">
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-header bg-primary text-white p-3 rounded-top-3">
                <h2 class="h5 mb-0 fw-semibold">Add New Program</h2>
            </div>
            <div class="card-body">
                <form action="{{ route('programs.storeNew') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="subdivision_id" class="form-label">Division</label>
                        <select class="form-select" id="subdivision_id" name="subdivision_id">
                            <option selected disabled>Select Division</option>
                            @foreach($subdivisions as $subdivision)
                                <option value="{{ $subdivision->id }}">{{ $subdivision->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="school_id" class="form-label">Category</label>
                        <select class="form-select" id="school_id" name="school_id">
                            <option selected disabled>Select Category</option>
                            @foreach($schools as $school)
                                <option value="{{ $school->id }}" data-subdivision="{{ $school->subdivision_id }}">{{ $school->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="program_name" class="form-label">Program Name</label>
                        <input type="text" class="form-control" id="program_name" name="program_name" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Program</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        const schoolSelect = document.getElementById('school_id');
        const originalSchoolOptions = Array.from(schoolSelect.options);

        document.getElementById('subdivision_id').addEventListener('change', function () {
            const subdivisionId = this.value;
            
            // Clear existing options
            schoolSelect.innerHTML = '<option selected disabled>Select Category</option>';

            // Filter and add schools based on selected subdivision
            originalSchoolOptions.forEach(function(option) {
                if (option.dataset.subdivision == subdivisionId) {
                    schoolSelect.add(option.cloneNode(true));
                }
            });
        });
    </script>
@endsection
