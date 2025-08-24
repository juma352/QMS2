<?php

namespace App\Http\Controllers;

use App\Models\Audit;
use App\Models\Standard;
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
        $auditNumber = 'AUD-' . date('Ymd') . '-' . substr(str_shuffle('0123456789'), 0, 4);
        $audit = new Audit(['audit_number' => $auditNumber]);
        return view('audits.create', compact('auditType', 'audit') + $options);
    }

    public function store(Request $request)
    {
        $options = $this->getFormOptions();
        $validated = $request->validate([
            'audit_type' => 'required|in:Internal,External',
            'audit_name' => 'required|string|max:255',
            'audit_number' => 'nullable|string|max:255',
            'issuing_authority' => 'nullable|in:' . implode(',', $options['issuing_authorities']),
            'other_issuing_authority' => 'required_if:issuing_authority,Other|string|nullable|max:255',
            'auditor' => 'nullable|string|max:255',
            'standard_id' => 'nullable|exists:standards,id',
            'status' => 'required|in:' . implode(',', $options['statuses']),
            'date_conducted' => 'nullable|date',
            'next_audit_date' => 'nullable|date|after_or_equal:date_conducted',
            'audit_report' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:5120',
            'supporting_documents.*' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:5120',
        ]);

        $data = $request->except(['audit_report', 'supporting_documents']);
        $data['other_issuing_authority'] = $request->input('issuing_authority') === 'Other' ? $request->input('other_issuing_authority') : null;
        $data['issuing_authority'] = $request->input('issuing_authority') === 'Other' ? $request->input('other_issuing_authority') : $request->input('issuing_authority');

        if ($request->hasFile('audit_report')) {
            $data['audit_report'] = $request->file('audit_report')->store('audit_reports', 'public');
        }

        if ($request->hasFile('supporting_documents')) {
            $paths = [];
            foreach ($request->file('supporting_documents') as $file) {
                $paths[] = $file->store('supporting_documents', 'public');
            }
            $data['supporting_documents'] = json_encode($paths);
        }

        $audit = Audit::create($data);

        return redirect()->route('audits.checklist.create', $audit->id)
                         ->with('message', 'Audit added successfully. Please create your dynamic checklist.');
    }

    public function show(Audit $audit)
    {
        return view('audits.show', compact('audit'));
    }

    public function edit(Audit $audit)
    {
        $options = $this->getFormOptions();
        $auditType = $audit->audit_type;
        return view('audits.edit', compact('audit', 'auditType') + $options);
    }

    public function update(Request $request, Audit $audit)
    {
        $options = $this->getFormOptions();
        $validated = $request->validate([
            'audit_type' => 'required|in:Internal,External',
            'audit_name' => 'required|string|max:255',
            'audit_number' => 'nullable|string|max:255',
            'issuing_authority' => 'nullable|in:' . implode(',', $options['issuing_authorities']),
            'other_issuing_authority' => 'required_if:issuing_authority,Other|string|nullable|max:255',
            'auditor' => 'nullable|string|max:255',
            'standard_id' => 'nullable|exists:standards,id',
            'status' => 'required|in:' . implode(',', $options['statuses']),
            'date_conducted' => 'nullable|date',
            'next_audit_date' => 'nullable|date|after_or_equal:date_conducted',
            'audit_report' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:5120',
            'supporting_documents.*' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:5120',
        ]);

        $data = $request->except(['audit_report', 'supporting_documents']);
        $data['other_issuing_authority'] = $request->input('issuing_authority') === 'Other' ? $request->input('other_iss gin_authority') : null;
        $data['issuing_authority'] = $request->input('issuing_authority') === 'Other' ? $request->input('other_issuing_authority') : $request->input('issuing_authority');

        if ($request->hasFile('audit_report')) {
            if ($audit->audit_report) {
                Storage::disk('public')->delete($audit->audit_report);
            }
            $data['audit_report'] = $request->file('audit_report')->store('audit_reports', 'public');
        }

        if ($request->hasFile('supporting_documents')) {
            if ($audit->supporting_documents) {
                foreach (json_decode($audit->supporting_documents, true) as $path) {
                    Storage::disk('public')->delete($path);
                }
            }
            $paths = [];
            foreach ($request->file('supporting_documents') as $file) {
                $paths[] = $file->store('supporting_documents', 'public');
            }
            $data['supporting_documents'] = json_encode($paths);
        }

        $audit->update($data);

        return redirect()->route('audits.index', ['type' => $audit->audit_type])
                         ->with('message', 'Audit updated successfully.');
    }

    public function destroy(Audit $audit)
    {
        if ($audit->audit_report) {
            Storage::disk('public')->delete($audit->audit_report);
        }
        if ($audit->supporting_documents) {
            foreach (json_decode($audit->supporting_documents, true) as $path) {
                Storage::disk('public')->delete($path);
            }
        }
        $auditType = $audit->audit_type;
        $audit->delete();

        return redirect()->route('audits.index', ['type' => $auditType])
                         ->with('message', 'Audit deleted successfully.');
    }
}