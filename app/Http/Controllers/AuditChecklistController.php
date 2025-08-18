<?php

namespace App\Http\Controllers;

use App\Models\Audit;
use App\Services\ChecklistGeneratorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuditChecklistController extends Controller
{
    protected $checklistGenerator;

    public function __construct(ChecklistGeneratorService $checklistGenerator)
    {
        $this->checklistGenerator = $checklistGenerator;
    }

    /**
     * Generate checklist for an audit
     */
    public function generate(Request $request, Audit $audit)
    {
        $request->validate([
            'department_name' => 'required|string|max:255',
        ]);

        try {
            $auditChecklist = $this->checklistGenerator->generateChecklist(
                $audit,
                $request->department_name,
                Auth::id()
            );

            return redirect()->route('audits.checklist.show', [
                'audit' => $audit->id,
                'checklist' => $auditChecklist->checklist_id
            ])->with('success', 'Checklist generated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error generating checklist: ' . $e->getMessage());
        }
    }

    /**
     * Show the checklist customization interface
     */
    public function show(Audit $audit, Checklist $checklist)
    {
        $auditChecklist = \App\Models\AuditChecklist::where('audit_id', $audit->id)
            ->where('checklist_id', $checklist->id)
            ->firstOrFail();

        $itemsBySection = $checklist->items->groupBy('section');
        
        return view('audits.checklist.show', compact('audit', 'checklist', 'itemsBySection', 'auditChecklist'));
    }

    /**
     * Update checklist customization
     */
    public function update(Request $request, Audit $audit, Checklist $checklist)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.custom_text' => 'nullable|string',
            'items.*.notes' => 'nullable|string',
            'items.*.status' => 'required|in:pending,completed,in_progress,not_applicable',
        ]);

        try {
            foreach ($request->items as $itemId => $itemData) {
                $this->checklistGenerator->updateChecklistItemCustomization($itemId, $itemData);
            }

            return redirect()->route('audits.checklist.show', [
                'audit' => $audit->id,
                'checklist' => $checklist->id
            ])->with('success', 'Checklist updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error updating checklist: ' . $e->getMessage());
        }
    }

    /**
     * Complete checklist generation
     */
    public function complete(Request $request, Audit $audit, Checklist $checklist)
    {
        $auditChecklist = \App\Models\AuditChecklist::where('audit_id', $audit->id)
            ->where('checklist_id', $checklist->id)
            ->firstOrFail();

        $this->checklistGenerator->completeChecklist($auditChecklist);

        return redirect()->route('audits.show', $audit)
            ->with('success', 'Checklist completed successfully!');
    }
}
