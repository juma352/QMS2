<?php

namespace App\Http\Controllers;

use App\Models\Audit;
use App\Models\Standard;
use App\Models\Checklist;
use App\Models\AuditChecklist;
use App\Services\ChecklistGeneratorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AuditController extends Controller
{
    // Common dropdown options
    private function getFormOptions()
    {
        return [
            'standards' => Standard::select('id', 'standard_name')->get(),
            'issuing_authorities' => ['MOH', 'NCK', 'TVETA', 'Internal Committee', 'Other'],
            'statuses' => ['Pending', 'In Progress', 'Completed', 'Cancelled'],
        ];
    }

    public function index(Request $request)
    {
        $request->validate(['type' => 'required|in:Internal,External']);
        $auditType = $request->type;
        $audits = Audit::where('audit_type', $auditType)->with('standard')->latest()->paginate(10);
        return view('audits.index', compact('audits', 'auditType'));
    }

    public function create(Request $request)
    {
        $request->validate(['type' => 'required|in:Internal,External']);
        $auditType = $request->type;
        $options = $this->getFormOptions();
        return view('audits.create', compact('auditType') + $options);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'audit_type' => 'required|in:Internal,External',
            'audit_name' => 'required|string|max:255',
            'audit_number' => 'nullable|string|max:255',
            'issuing_authority' => 'nullable|string|max:255',
            'auditor' => 'nullable|string|max:255',
            'standard_id' => 'nullable|exists:standards,id',
            'status' => 'required|string|max:255',
            'date_conducted' => 'nullable|date',
            'next_audit_date' => 'nullable|date',
            'audit_report' => 'nullable|file|mimes:pdf,docx,jpg,png|max:5120',
            'supporting_documents.*' => 'nullable|file|mimes:pdf,docx,jpg,png|max:5120',
        ]);

        $data = $request->except(['audit_report', 'supporting_documents']);

        if ($request->hasFile('audit_report')) {
            $data['audit_report_path'] = $request->file('audit_report')->store('audit_reports', 'public');
        }

        if ($request->hasFile('supporting_documents')) {
            $paths = [];
            foreach ($request->file('supporting_documents') as $file) {
                $paths[] = $file->store('supporting_documents', 'public');
            }
            $data['supporting_documents_paths'] = $paths;
        }

        $audit = Audit::create($data);

        // Always generate checklist and redirect to dynamic checklist after audit form submission
        // Use 'General Department' as default since department_name is not collected in audit form
        return $this->generateChecklistAndRedirect($audit, 'General Department');
    }

    public function show(Audit $audit)
    {
        $audit->load('checklists');
        return view('audits.show', compact('audit'));
    }

    public function edit(Audit $audit)
    {
        $options = $this->getFormOptions();
        return view('audits.edit', compact('audit') + $options);
    }

    public function update(Request $request, Audit $audit)
    {
        $validated = $request->validate([
            'audit_type' => 'required|in:Internal,External',
            'audit_name' => 'required|string|max:255',
            'audit_number' => 'nullable|string|max:255',
            'issuing_authority' => 'nullable|string|max:255',
            'auditor' => 'nullable|string|max:255',
            'standard_id' => 'nullable|exists:standards,id',
            'status' => 'required|string|max:255',
            'date_conducted' => 'nullable|date',
            'next_audit_date' => 'nullable|date',
            'findings' => 'nullable|string',
            'corrective_actions' => 'nullable|string',
            'audit_report' => 'nullable|file|mimes:pdf,docx,jpg,png|max:5120',
            'supporting_documents.*' => 'nullable|file|mimes:pdf,docx,jpg,png|max:5120',
        ]);

        $data = $request->except(['audit_report', 'supporting_documents']);

        if ($request->hasFile('audit_report')) {
            if ($audit->audit_report_path) Storage::disk('public')->delete($audit->audit_report_path);
            $data['audit_report_path'] = $request->file('audit_report')->store('audit_reports', 'public');
        }

        if ($request->hasFile('supporting_documents')) {
            $paths = [];
            foreach ($request->file('supporting_documents') as $file) {
                $paths[] = $file->store('supporting_documents', 'public');
            }
            $data['supporting_documents_paths'] = $paths;
        }

        $audit->update($data);

        // If audit status changed to completed, generate checklist
        if ($request->status === 'Completed' && $audit->wasChanged('status')) {
            return $this->generateChecklistAndRedirect($audit, $request->department_name ?? 'General Department');
        }

        return redirect()->route('audits.index', ['type' => $audit->audit_type])
                         ->with('message', 'Audit updated successfully.');
    }

    public function destroy(Audit $audit)
    {
        if ($audit->audit_report_path) Storage::disk('public')->delete($audit->audit_report_path);
        if ($audit->supporting_documents_paths) {
            foreach ($audit->supporting_documents_paths as $path) {
                Storage::disk('public')->delete($path);
            }
        }
        $auditType = $audit->audit_type;
        $audit->delete();

        return redirect()->route('audits.index', ['type' => $auditType])
                         ->with('message', 'Audit deleted successfully.');
    }

    /**
     * Generate checklist for audit and redirect to checklist view
     */
    private function generateChecklistAndRedirect(Audit $audit, string $departmentName)
    {
        $checklistGenerator = new ChecklistGeneratorService();
        
        // Generate dynamic checklist based on audit
        $checklist = $checklistGenerator->generateFromAudit($audit, $departmentName, auth()->id());
        
        // Create audit-checklist relationship
        $auditChecklist = AuditChecklist::create([
            'audit_id' => $audit->id,
            'checklist_id' => $checklist->id,
            'department_name' => $departmentName,
            'status' => 'pending',
        ]);

        return redirect()->route('checklists.show', $checklist->id)
            ->with('message', 'Audit saved successfully! Dynamic checklist has been generated.');
    }

    /**
     * Generate checklist from audit form
     */
    public function generateChecklist(Request $request, Audit $audit)
    {
        $request->validate([
            'department_name' => 'required|string|max:255',
        ]);

        return $this->generateChecklistAndRedirect($audit, $request->department_name);
    }

    /**
     * Show audit completion page with redirect to checklist
     */
    public function completed(Audit $audit)
    {
        $latestChecklist = $audit->checklists()->latest()->first();
        
        return view('audits.completed', [
            'audit' => $audit,
            'checklist' => $latestChecklist
        ]);
    }
}
