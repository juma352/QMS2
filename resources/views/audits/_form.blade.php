@csrf
{{-- This hidden input tells the form whether it's for an 'Internal' or 'External' audit --}}
<input type="hidden" name="audit_type" value="{{ $auditType ?? $audit->audit_type }}">

<div class="row g-3">
    {{-- Row 1: Audit Name & Number --}}
    <div class="col-md-6">
        <label for="audit_name" class="form-label">Audit Name <span class="text-danger">*</span></label>
        <input type="text" name="audit_name" id="audit_name" class="form-control" value="{{ old('audit_name', $audit->audit_name ?? '') }}" required>
        @error('audit_name')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
    <div class="col-md-6">
        <label for="audit_number" class="form-label">Audit Number</label>
        <input type="text" name="audit_number" id="audit_number" class="form-control" value="{{ old('audit_number', $audit->audit_number ?? '') }}" readonly>
        @error('audit_number')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>

    {{-- Row 2: Issuing Authority & Auditor --}}
    <div class="col-md-6">
        <label for="issuing_authority" class="form-label">Issuing Authority</label>
        <select name="issuing_authority" id="issuing_authority" class="form-select" onchange="toggleOtherIssuingAuthority()">
            <option value="">Select Issuing Authority</option>
            @foreach($issuing_authorities as $authority)
                <option value="{{ $authority }}" {{ old('issuing_authority', $audit->issuing_authority ?? '') == $authority ? 'selected' : '' }}>{{ $authority }}</option>
            @endforeach
        </select>
        @error('issuing_authority')
            <span class="text-danger">{{ $message }}</span>
        @enderror
        <div id="other-issuing-authority-group" style="display: none;" class="mt-2">
            <label for="other_issuing_authority" class="form-label">Other Issuing Authority <span class="text-danger">*</span></label>
            <input type="text" name="other_issuing_authority" id="other_issuing_authority" class="form-control" value="{{ old('other_issuing_authority', $audit->other_issuing_authority ?? '') }}" placeholder="Please specify other authority">
            @error('other_issuing_authority')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <label for="auditor" class="form-label">Auditor</label>
        <input type="text" name="auditor" id="auditor" class="form-control" value="{{ old('auditor', $audit->auditor ?? '') }}">
        @error('auditor')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>

    {{-- Row 3: Standard & Status --}}
    <div class="col-md-6">
        <label for="standard_id" class="form-label">Standard</label>
        <select name="standard_id" id="standard_id" class="form-select">
            <option value="">Select Standard</option>
            @foreach($standards as $standard)
                <option value="{{ $standard->id }}" {{ old('standard_id', $audit->standard_id ?? '') == $standard->id ? 'selected' : '' }}>
                    {{ $standard->standard_name }}
                </option>
            @endforeach
        </select>
        @error('standard_id')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
    <div class="col-md-6">
        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
        <select name="status" id="status" class="form-select" required>
            @foreach($statuses as $status)
                <option value="{{ $status }}" {{ old('status', $audit->status ?? '') == $status ? 'selected' : '' }}>{{ $status }}</option>
            @endforeach
        </select>
        @error('status')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>

    {{-- Row 4: Dates --}}
    <div class="col-md-6">
        <label for="date_conducted" class="form-label">Date Conducted</label>
        <input type="date" name="date_conducted" id="date_conducted" class="form-control" value="{{ old('date_conducted', isset($audit->date_conducted) ? $audit->date_conducted->format('Y-m-d') : '') }}" onchange="updateNextAuditDate()">
        @error('date_conducted')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
    <div class="col-md-6">
        <label for="months_before_next" class="form-label">Months Before Next Audit</label>
        <select name="months_before_next" id="months_before_next" class="form-select" onchange="updateNextAuditDate()">
            <option value="">Select Months</option>
            <option value="3" {{ old('months_before_next', $audit->months_before_next ?? '') == '3' ? 'selected' : '' }}>3 Months</option>
            <option value="6" {{ old('months_before_next', $audit->months_before_next ?? '') == '6' ? 'selected' : '' }}>6 Months</option>
            <option value="9" {{ old('months_before_next', $audit->months_before_next ?? '') == '9' ? 'selected' : '' }}>9 Months</option>
            <option value="12" {{ old('months_before_next', $audit->months_before_next ?? '') == '12' ? 'selected' : '' }}>12 Months</option>
        </select>
        @error('months_before_next')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
    <div class="col-md-6">
        <label for="next_audit_date" class="form-label">Next Audit Date</label>
        <input type="date" name="next_audit_date" id="next_audit_date" class="form-control" value="{{ old('next_audit_date', isset($audit->next_audit_date) ? $audit->next_audit_date->format('Y-m-d') : '') }}" readonly>
        @error('next_audit_date')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
</div>

<script>
    function toggleOtherIssuingAuthority() {
        const issuingAuthoritySelect = document.getElementById('issuing_authority');
        const otherIssuingAuthorityGroup = document.getElementById('other-issuing-authority-group');
        otherIssuingAuthorityGroup.style.display = issuingAuthoritySelect.value === 'Other' ? 'block' : 'none';
    }

    function updateNextAuditDate() {
        const dateConductedInput = document.getElementById('date_conducted');
        const monthsBeforeNextSelect = document.getElementById('months_before_next');
        const nextAuditDateInput = document.getElementById('next_audit_date');

        if (dateConductedInput.value && monthsBeforeNextSelect.value) {
            const dateConducted = new Date(dateConductedInput.value);
            const monthsToAdd = parseInt(monthsBeforeNextSelect.value);
            const nextAuditDate = new Date(dateConducted);
            nextAuditDate.setMonth(dateConducted.getMonth() + monthsToAdd);

            // Format the date to YYYY-MM-DD
            const year = nextAuditDate.getFullYear();
            const month = String(nextAuditDate.getMonth() + 1).padStart(2, '0');
            const day = String(nextAuditDate.getDate()).padStart(2, '0');
            nextAuditDateInput.value = `${year}-${month}-${day}`;
        } else {
            nextAuditDateInput.value = ''; // Clear if either input is empty
        }
    }

    // Run on page load to handle old input or edit scenarios
    document.addEventListener('DOMContentLoaded', () => {
        toggleOtherIssuingAuthority();
        updateNextAuditDate();
    });
</script>