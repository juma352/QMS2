@csrf
{{-- This hidden input tells the form whether it's for an 'Internal' or 'External' audit --}}
<input type="hidden" name="audit_type" value="{{ $auditType ?? $audit->audit_type }}">

<div class="row g-3">
    {{-- Row 1: Audit Name & Number --}}
    <div class="col-md-6">
        <label for="audit_name" class="form-label">Audit Name <span class="text-danger">*</span></label>
        <input type="text" name="audit_name" id="audit_name" class="form-control" value="{{ old('audit_name', $audit->audit_name ?? '') }}" required>
    </div>
    <div class="col-md-6">
        <label for="audit_number" class="form-label">Audit Number</label>
        <input type="text" name="audit_number" id="audit_number" class="form-control" value="{{ old('audit_number', $audit->audit_number ?? '') }}">
    </div>

    {{-- Row 2: Issuing Authority & Auditor --}}
    <div class="col-md-6">
        <label for="issuing_authority" class="form-label">Issuing Authority</label>
        <select name="issuing_authority" id="issuing_authority" class="form-select">
            <option value="">Select Issuing Authority</option>
            @foreach($issuing_authorities as $authority)
                <option value="{{ $authority }}" {{ (old('issuing_authority', $audit->issuing_authority ?? '')) == $authority ? 'selected' : '' }}>{{ $authority }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label for="auditor" class="form-label">Auditor</label>
        <input type="text" name="auditor" id="auditor" class="form-control" value="{{ old('auditor', $audit->auditor ?? '') }}">
    </div>

    {{-- Row 3: Standard & Status --}}
    <div class="col-md-6">
        <label for="standard_id" class="form-label">Standard</label>
        <select name="standard_id" id="standard_id" class="form-select">
            <option value="">Select Standard</option>
            @foreach($standards as $standard)
                <option value="{{ $standard->id }}" {{ (old('standard_id', $audit->standard_id ?? '')) == $standard->id ? 'selected' : '' }}>
                    {{ $standard->standard_name }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
        <select name="status" id="status" class="form-select" required>
            @foreach($statuses as $status)
                <option value="{{ $status }}" {{ (old('status', $audit->status ?? '')) == $status ? 'selected' : '' }}>{{ $status }}</option>
            @endforeach
        </select>
    </div>

    {{-- Row 4: Dates --}}
    <div class="col-md-6">
        <label for="date_conducted" class="form-label">Date Conducted</label>
        <input type="date" name="date_conducted" id="date_conducted" class="form-control" value="{{ old('date_conducted', isset($audit->date_conducted) ? $audit->date_conducted->format('Y-m-d') : '') }}">
    </div>
    <div class="col-md-6">
        <label for="next_audit_date" class="form-label">Next Audit Date</label>
        <input type="date" name="next_audit_date" id="next_audit_date" class="form-control" value="{{ old('next_audit_date', isset($audit->next_audit_date) ? $audit->next_audit_date->format('Y-m-d') : '') }}">
    </div>

    {{-- Row 5: Findings --}}
    <div class="col-12">
        <label for="findings" class="form-label">Findings</label>
        <textarea name="findings" id="findings" class="form-control" rows="4">{{ old('findings', $audit->findings ?? '') }}</textarea>
    </div>

    {{-- Row 6: Corrective Actions --}}
    <div class="col-12">
        <label for="corrective_actions" class="form-label">Corrective Actions</label>
        <textarea name="corrective_actions" id="corrective_actions" class="form-control" rows="4">{{ old('corrective_actions', $audit->corrective_actions ?? '') }}</textarea>
    </div>

    {{-- Row 7: File Uploads --}}
    <div class="col-md-6">
        <label for="audit_report" class="form-label">Audit Report</label>
        <input type="file" name="audit_report" id="audit_report" class="form-control">
    </div>
    <div class="col-md-6">
        <label for="supporting_documents" class="form-label">Supporting Documents</label>
        <input type="file" name="supporting_documents[]" id="supporting_documents" class="form-control" multiple>
    </div>
</div>