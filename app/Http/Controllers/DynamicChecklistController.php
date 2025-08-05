<?php

namespace App\Http\Controllers;

use App\Models\Audit;
use App\Models\Checklist;
use App\Models\ChecklistItem;
use App\Models\AuditChecklist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DynamicChecklistController extends Controller
{
    /**
     * Show the form for creating a dynamic checklist for an audit
     */
    public function create(Audit $audit)
    {
        return view('audits.dynamic_checklist_form', compact('audit'));
    }

    /**
     * Store the dynamically generated checklist
     */
    public function store(Request $request, Audit $audit)
    {
        $request->validate([
            'sections' => 'required|array|min:1',
            'sections.*.name' => 'required|string|max:255',
            'sections.*.questions' => 'required|array|min:1',
            'sections.*.questions.*.text' => 'required|string|max:1000',
            'sections.*.questions.*.rating_type' => 'required|string|in:scale_1_5,scale_1_10,yes_no,pass_fail',
'sections.*.questions.*.rating_value' => 'nullable|integer|min:1|max:10',
        ]);

        try {
            DB::beginTransaction();

            // Create the checklist with the same name as the audit
            $checklist = Checklist::create([
                'slug' => 'audit-' . $audit->id . '-checklist',
                'title' => $audit->name,
                'description' => 'Dynamic checklist generated for audit: ' . $audit->name,
                'is_template' => false,
                'audit_id' => $audit->id,
            ]);

            // Create checklist items from user input
            $order = 1;
            foreach ($request->sections as $sectionData) {
                foreach ($sectionData['questions'] as $questionData) {
    ChecklistItem::create([
    'checklist_id' => $checklist->id,
    'section' => $sectionData['name'],
    'question_text' => $questionData['text'],
    'rating_type' => $questionData['rating_type'] ?? 'scale_1_5',
    'rating_value' => $questionData['rating_value'] ?? null,
    'rating_notes' => $questionData['rating_notes'] ?? null,
                    ]);
                }
            }

            // Link the checklist to the audit
            AuditChecklist::create([
                'audit_id' => $audit->id,
                'checklist_id' => $checklist->id,
                'generated_by' => auth()->id(),
                'generated_at' => now(),
            ]);

            DB::commit();

            return redirect()->route('checklists.index')
                ->with('success', 'Dynamic checklist successfully created and is now available in your checklist section!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error creating checklist: ' . $e->getMessage());
        }
    }

    /**
     * Edit existing dynamic checklist
     */
    public function edit(Audit $audit)
    {
        $checklist = $audit->checklist;
        if (!$checklist) {
            return redirect()->route('audits.checklist.create', $audit->id)
                ->with('info', 'No checklist exists for this audit. Create one now.');
        }

        return view('audits.dynamic_checklist_edit', compact('audit', 'checklist'));
    }

    /**
     * Update existing dynamic checklist
     */
    public function update(Request $request, Audit $audit)
    {
        $checklist = $audit->checklist;
        if (!$checklist) {
            return back()->with('error', 'No checklist found for this audit.');
        }

        $request->validate([
            'sections' => 'required|array|min:1',
            'sections.*.name' => 'required|string|max:255',
            'sections.*.questions' => 'required|array|min:1',
            'sections.*.questions.*.text' => 'required|string|max:1000',
        ]);

        try {
            DB::beginTransaction();

            // Delete existing items
            $checklist->items()->delete();

            // Create new items
            $order = 1;
            foreach ($request->sections as $sectionData) {
                foreach ($sectionData['questions'] as $questionData) {
                    ChecklistItem::create([
                        'checklist_id' => $checklist->id,
                        'section' => $sectionData['name'],
                        'question_text' => $questionData['text'],
                        'display_order' => $order++,
                        'is_placeholder' => false,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('audits.show', $audit->id)
                ->with('success', 'Checklist successfully updated!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error updating checklist: ' . $e->getMessage());
        }
    }
}
