<?php

namespace App\Http\Controllers;

use App\Models\Audit;
use App\Models\Checklist;
use App\Models\ChecklistItem;
use App\Models\AuditChecklist;
use App\Models\ChecklistSubmission;
use App\Models\SubmissionAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;


class DynamicChecklistController extends Controller
{
    /**
     * Show the form for creating a dynamic checklist for an audit
     */
    public function create(Audit $audit)
    {
        // Check if audit already has a checklist
        $existingChecklist = $audit->checklist;
        if ($existingChecklist) {
            return redirect()->route('audits.checklist.edit', $audit->id)
                ->with('info', 'This audit already has a checklist. You can edit it here.');
        }

        return view('audits.dynamic_checklist_form', compact('audit'));
    }

    /**
     * Store the dynamically generated checklist
     */
    public function store(Request $request)
    {
        // Enhanced validation
        $validated = $request->validate([
            'checklist_title' => 'required|string|max:255',
            'checklist_description' => 'nullable|string|max:1000',
            'audit_id' => 'required|exists:audits,id',
            'sections' => 'required|array|min:1',
            'sections.*.name' => 'required|string|max:255',
            'sections.*.description' => 'nullable|string|max:500',
            'sections.*.questions' => 'required|array|min:1',
            'sections.*.questions.*.text' => 'required|string|max:1000',
            'sections.*.questions.*.rating_type' => 'required|string|in:scale_1_5,scale_1_7,scale_1_10,yes_no,pass_fail',
            'sections.*.questions.*.notes' => 'nullable|string|max:500',
        ], [
            'sections.required' => 'You must add at least one section.',
            'sections.*.name.required' => 'Each section must have a name.',
            'sections.*.questions.required' => 'Each section must have at least one question.',
            'sections.*.questions.*.text.required' => 'Each question must have text.',
            'sections.*.questions.*.rating_type.required' => 'Each question must have a rating type.',
        ]);

        $audit = Audit::findOrFail($validated['audit_id']);

        try {
            DB::beginTransaction();

            // Create the checklist
            $checklist = Checklist::create([
                'slug' => Str::slug($validated['checklist_title']) . '-' . $audit->id . '-' . time(),
                'title' => $validated['checklist_title'],
                'description' => $validated['checklist_description'] ?? 'Dynamic checklist generated for audit: ' . $audit->audit_name,
                'is_template' => false,
                'audit_id' => $audit->id,
                'generated_by' => auth()->id(),
                'generated_at' => now(),
            ]);

            // Create checklist items from user input
            $displayOrder = 1;
            foreach ($validated['sections'] as $sectionData) {
                foreach ($sectionData['questions'] as $questionData) {
                    ChecklistItem::create([
                        'checklist_id' => $checklist->id,
                        'section' => $sectionData['name'],
                        'question_text' => $questionData['text'],
                        'rating_type' => $questionData['rating_type'],
                        'rating_notes' => $questionData['notes'] ?? null,
                        'display_order' => $displayOrder++,
                        'is_placeholder' => false,
                    ]);
                }
            }

            // Create the audit checklist relationship
            AuditChecklist::create([
                'audit_id' => $audit->id,
                'checklist_id' => $checklist->id,
                'generated_by' => auth()->id(),
                'generated_at' => now(),
            ]);

            DB::commit();

            Log::info('Dynamic checklist created successfully', [
                'audit_id' => $audit->id,
                'checklist_id' => $checklist->id,
                'user_id' => auth()->id(),
                'sections_count' => count($validated['sections']),
                'total_questions' => collect($validated['sections'])->sum(fn($section) => count($section['questions']))
            ]);

            return redirect()->route('checklists.dynamic_index')
                ->with('success', 'Checklist "' . $checklist->title . '" has been successfully created! You can now find it in your checklists and start working on it.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Dynamic checklist creation failed', [
                'audit_id' => $audit->id,
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()
                ->with('error', 'Error creating checklist: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Edit existing dynamic checklist
     */
  public function edit($id)
{
    $checklist = Checklist::with(['items', 'audit'])->findOrFail($id);
    
    // Ensure this is a dynamic checklist (has audit relationship)
    $auditChecklist = AuditChecklist::where('checklist_id', $checklist->id)->first();
    if (!$auditChecklist) {
        return redirect()->route('checklists.dynamic_index')
            ->with('error', 'This checklist cannot be edited.');
    }

    // Get the audit from the checklist
    $audit = $checklist->audit;
    if (!$audit) {
        return redirect()->route('checklists.dynamic_index')
            ->with('error', 'This checklist is not associated with an audit.');
    }

    // Group items by section for the view
    $itemsBySection = $checklist->items->groupBy('section');
    
    return view('checklists.edit', compact('checklist', 'auditChecklist', 'itemsBySection', 'audit'));
}

/**
 * Update existing dynamic checklist
 */
public function update(Request $request, $id)
{
    $checklist = Checklist::findOrFail($id);
    
    $validated = $request->validate([
        'checklist_title' => 'required|string|max:255',
        'checklist_description' => 'nullable|string|max:1000',
        'sections' => 'required|array|min:1',
        'sections.*.name' => 'required|string|max:255',
        'sections.*.description' => 'nullable|string|max:500',
        'sections.*.questions' => 'required|array|min:1',
        'sections.*.questions.*.text' => 'required|string|max:1000',
        'sections.*.questions.*.rating_type' => 'required|string|in:scale_1_5,scale_1_7,scale_1_10,yes_no,pass_fail',
        'sections.*.questions.*.notes' => 'nullable|string|max:500',
    ]);

    try {
        DB::beginTransaction();

        // Update checklist basic info
        $checklist->update([
            'title' => $validated['checklist_title'],
            'description' => $validated['checklist_description'] ?? $checklist->description,
        ]);

        // Delete existing items
        $checklist->items()->delete();

        // Create new items
        $displayOrder = 1;
        foreach ($validated['sections'] as $sectionData) {
            foreach ($sectionData['questions'] as $questionData) {
                ChecklistItem::create([
                    'checklist_id' => $checklist->id,
                    'section' => $sectionData['name'],
                    'question_text' => $questionData['text'],
                    'rating_type' => $questionData['rating_type'],
                    'rating_notes' => $questionData['notes'] ?? null,
                    'display_order' => $displayOrder++,
                    'is_placeholder' => false,
                ]);
            }
        }

        DB::commit();

        Log::info('Dynamic checklist updated successfully', [
            'checklist_id' => $checklist->id,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('checklists.dynamic_index')
            ->with('success', 'Checklist "' . $checklist->title . '" has been successfully updated!');

    } catch (\Exception $e) {
        DB::rollBack();
        
        Log::error('Dynamic checklist update failed', [
            'checklist_id' => $checklist->id,
            'user_id' => auth()->id(),
            'error' => $e->getMessage()
        ]);
        
        return back()
            ->with('error', 'Error updating checklist: ' . $e->getMessage())
            ->withInput();
    }
}


    /**
     * Display a listing of dynamic checklists
     */
    public function dynamic_index()
    {
        $user = auth()->user();
        
        // Get dynamic checklists (AuditChecklist relationships)
        $dynamicChecklists = \App\Models\AuditChecklist::with(['audit', 'checklist', 'checklist.items'])
            ->latest()
            ->get();

        // Calculate progress for each checklist
        foreach ($dynamicChecklists as $auditChecklist) {
            $checklist = $auditChecklist->checklist;
            if ($checklist) {
                $latestSubmission = $checklist->submissions()->latest('submitted_at')->first();

                if ($latestSubmission) {
                    $answeredQuestions = $latestSubmission->answers()->count();
                    $totalQuestions = $checklist->items()->count();
                    $progressPercentage = ($totalQuestions > 0) ? ($answeredQuestions / $totalQuestions) * 100 : 0;
                    $auditChecklist->progress_percentage = round($progressPercentage);
                } else {
                    $auditChecklist->progress_percentage = 0;
                }
            } else {
                $auditChecklist->progress_percentage = 0;
            }
        }

        $medicalSpecialistChecklist = \App\Models\Checklist::with('submissions.answers', 'items')
            ->where('slug', 'medical-specialist-checklist')->first();

        $staticSubmissionId = null;
        if ($medicalSpecialistChecklist) {
            $latestSubmission = $medicalSpecialistChecklist->submissions()->latest('submitted_at')->first();

            if ($latestSubmission) {
                $staticSubmissionId = $latestSubmission->id;
                $answeredQuestions = $latestSubmission->answers()->count();
                $totalQuestions = $medicalSpecialistChecklist->items()->count();
                $progressPercentage = ($totalQuestions > 0) ? ($answeredQuestions / $totalQuestions) * 100 : 0;
                $medicalSpecialistChecklist->progress_percentage = round($progressPercentage);
            } else {
                $medicalSpecialistChecklist->progress_percentage = 0;
            }
        }

        return view('checklists.dynamic_index', compact('dynamicChecklists', 'medicalSpecialistChecklist', 'staticSubmissionId'));
    }

    /**
     * Display a listing of dynamic checklists (legacy method)
     * @deprecated Use dynamic_index() instead
     */
    public function checklistsIndex()
    {
        return $this->dynamic_index();
    }

    /**
     * Display a specific dynamic checklist
     */
    public function showComplete($id)
    {
        $checklist = \App\Models\Checklist::with(['audit', 'items'])
            ->whereNotNull('audit_id')
            ->findOrFail($id);

        $itemsBySection = $checklist->items->groupBy('section');
        
        return view('checklists.dynamic_show_complete', compact('checklist', 'itemsBySection'));
    }

    /**
     * Show the form for submitting a new checklist for an audit.
     */
    public function show_submit(Checklist $checklist)
    {
        $checklist->load(['items', 'audit']);
        
        // Get the audit checklist relationship
        $auditChecklist = AuditChecklist::where('checklist_id', $checklist->id)->firstOrFail();
        
        $itemsBySection = $checklist->items->groupBy('section');

        return view('checklists.submit', compact('checklist', 'auditChecklist', 'itemsBySection'));
    }

    /**
     * Show the form for submitting a checklist (alias for show_submit)
     */
    public function submit($id)
    {
        $checklist = Checklist::findOrFail($id);
        return $this->show_submit($checklist);
    }
    public function submitChecklist(Request $request, $id)
    {
        $auditChecklist = AuditChecklist::where('checklist_id', $id)->first();
        
        if (!$auditChecklist) {
            return redirect()
                ->route('checklists.dynamic_index')
                ->with('error', 'Checklist not found. It may have been deleted or does not exist.');
        }
        
        // Add debugging to see what data is received
        Log::info('Checklist submission received', [
            'checklist_id' => $id,
            'all_data' => $request->all(),
            'files' => $request->allFiles()
        ]);

        $validated = $request->validate([
            'department_name' => 'required|string|max:255',
            'items' => 'required|array',
            'items.*.rating' => 'required|string',
            'items.*.notes' => 'nullable|string|max:1000',
            'items.*.comments' => 'nullable|string|max:1000',
            'items.*.evidence' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048', // Max 2MB
        ]);

        try {
            DB::beginTransaction();
        
            // Update AuditChecklist - fix the array_merge issue
            $auditChecklist->update([
                'status' => 'completed',
                'submitted_at' => now(),
                'submitted_by' => auth()->id()
            ]);
            
            // Create ChecklistSubmission record
            $submission = ChecklistSubmission::create([
                'checklist_id' => $id,
                'user_id' => auth()->id(),
                'department_name' => $validated['department_name'] ?? null,
                'status' => 'completed',
                'submitted_at' => now()
            ]);

            // Save answers for each checklist item
            $items = $validated['items'];
            foreach ($items as $itemId => $itemData) {
                // Ensure itemId is valid
                if (!is_numeric($itemId)) {
                    Log::warning('Invalid item ID in submission', ['item_id' => $itemId]);
                    continue;
                }

                $evidencePath = null;
                
                // Handle file uploads properly
                if ($request->hasFile("items.{$itemId}.evidence")) {
                    $file = $request->file("items.{$itemId}.evidence");
                    if ($file->isValid()) {
                        $evidencePath = $file->store('evidence', 'public');
                    }
                }

                SubmissionAnswer::create([
                    'checklist_submission_id' => $submission->id,
                    'checklist_item_id' => $itemId,
                    'rating' => $itemData['rating'],
                    'notes' => $itemData['notes'] ?? null,
                    'comments' => $itemData['comments'] ?? null,
                    'evidence' => $evidencePath,
                ]);
            }

            DB::commit();

            Log::info('Checklist submission successful', [
                'checklist_id' => $id,
                'submission_id' => $submission->id,
                'user_id' => auth()->id()
            ]);

            return redirect()->route('checklists.results.index')
                ->with('success', 'Checklist has been successfully submitted!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checklist submission failed', [
                'checklist_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->with('error', 'Error submitting checklist: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display a listing of all submissions
     */
    public function resultsIndex()
    {
        $submissions = \App\Models\ChecklistSubmission::with(['checklist', 'user'])
            ->latest()
            ->paginate(15);

        return view('submissions.results.index', compact('submissions'));
    }

    /**
     * Display individual submission details
     */
    public function resultsShow(\App\Models\ChecklistSubmission $submission)
    {
        $submission->load(['checklist', 'user', 'answers']);

        // Check if this is the Medical Specialist Checklist
        if ($submission->checklist->type === 'medical_specialist') {
            $progress = \App\Models\ChecklistProgress::where('checklist_submission_id', $submission->id)->firstOrFail();
            
            $steps = [
                1 => 'Administrative Information',
                2 => 'Governance & Management',
                3 => 'Academic Programme',
                4 => 'Physical Infrastructure',
                5 => 'Faculty/Trainers',
                6 => 'Student Welfare & Support',
                7 => 'Programme Monitoring & Evaluation',
                8 => 'Research & Innovation'
            ];

            $allQuestions = collect($steps)->mapWithKeys(function ($title, $step) {
                return [$step => config('medical_specialist_checklist.questions.' . $step, [])];
            });

            $answersMap = $submission->answers->keyBy('question_key');

            return view('checklists.medical-specialist.results', compact('submission', 'progress', 'steps', 'allQuestions', 'answersMap'));
        }

        // Use the default view for all other checklist results
        $submission->load(['answers.checklistItem']);
        return view('submissions.results.show', compact('submission'));
    }
    public function destroy($id)
{
    $submission = ChecklistSubmission::findOrFail($id);
    $submission->delete();

    return redirect()->back()->with('success', 'Submission deleted successfully.');
}


    /**
     * Print submission details
     */
    public function printSubmission(\App\Models\ChecklistSubmission $submission)
    {
        $submission->load(['checklist', 'user', 'answers.checklistItem']);
        
        return view('submissions.results.print', compact('submission'));
    }
    

}