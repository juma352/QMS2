<?php

namespace App\Http\Controllers;

use App\Models\Checklist;
use App\Models\ChecklistSubmission;
use App\Models\ChecklistProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class MedicalSpecialistChecklistController extends Controller
{
    protected $steps = [
        1 => 'Administrative Information',
        2 => 'Governance & Management',
        3 => 'Academic Programme',
        4 => 'Physical Infrastructure',
        5 => 'Faculty/Trainers',
        6 => 'Student Welfare & Support',
        7 => 'Programme Monitoring & Evaluation',
        8 => 'Research & Innovation'
    ];

    public function start()
    {
        $checklist = Checklist::where('title', 'like', '%Medical Specialist%')
            ->orWhere('title', 'like', '%Paediatric Surgery%')
            ->first();

        if (!$checklist) {
            $checklist = $this->createMedicalSpecialistChecklist();
        }

        $submission = ChecklistSubmission::create([
            'checklist_id' => $checklist->id,
            'user_id' => Auth::id(),
            'status' => 'draft',
            'department_name' => 'Medical Specialist Training'
        ]);

        $progress = ChecklistProgress::create([
            'checklist_submission_id' => $submission->id,
            'current_step' => 1,
            'completed_steps' => [],
            'is_draft' => true
        ]);

        return redirect()->route('checklists.medical-specialist.step', ['submission' => $submission->id, 'step' => 1]);
    }

    public function showStep(ChecklistSubmission $submission, $step)
    {
        // $this->authorize('update', $submission);
        
        $progress = ChecklistProgress::where('checklist_submission_id', $submission->id)->first();
        
        if (!$progress || $step < 1 || $step > 8) {
            abort(404);
        }

        $stepData = $progress->draft_data[$step] ?? [];
        $questions = $this->getStepQuestions($step);

        return view('checklists.medical-specialist.step', [
            'submission' => $submission,
            'step' => $step,
            'stepTitle' => $this->steps[$step],
            'questions' => $questions,
            'stepData' => $stepData,
            'progress' => $progress,
            'steps' => $this->steps
        ]);
    }

    public function saveStep(Request $request, ChecklistSubmission $submission, $step)
    {
        // $this->authorize('update', $submission);
        
        $request->validate([
            'data' => 'required|array',
            'action' => 'required|in:save,next,previous,submit',
            'uploads.*' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:2048' // Example validation
        ]);

        $progress = ChecklistProgress::where('checklist_submission_id', $submission->id)->first();
        
        $draftData = $progress->draft_data ?? [];
        $draftData[$step] = $request->data;

        if ($request->hasFile('uploads')) {
            foreach ($request->file('uploads') as $questionId => $file) {
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('documents/medical_specialist_uploads', $filename, 'public');
                $draftData[$step][$questionId . '_upload'] = $path;
            }
        }
        
        $progress->draft_data = $draftData;
        $progress->last_saved_at = now();

        if ($request->action === 'next') {
            $completed = $progress->completed_steps ?? [];
            if (!in_array($step, $completed)) {
                $completed[] = $step;
                $progress->completed_steps = $completed;
            }
            $progress->current_step = min($step + 1, 8);
        } elseif ($request->action === 'previous') {
            $completed = $progress->completed_steps ?? [];
            if (!in_array($step, $completed)) {
                $completed[] = $step;
                $progress->completed_steps = $completed;
            }
            $progress->current_step = max($step - 1, 1);
        } elseif ($request->action === 'submit') {
            return $this->submit($submission);
        }

        $progress->save();

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Draft saved at ' . now()->format('H:i:s')]);
        }

        return redirect()->route('checklists.medical-specialist.step', [
            'submission' => $submission->id, 
            'step' => $progress->current_step
        ]);
    }

    public function saveDraft(Request $request, ChecklistSubmission $submission)
    {
        // $this->authorize('update', $submission);
        
        $progress = ChecklistProgress::where('checklist_submission_id', $submission->id)->first();
        $progress->last_saved_at = now();
        $progress->save();

        return response()->json(['success' => true, 'message' => 'Draft saved successfully']);
    }

    public function resume(ChecklistSubmission $submission)
    {
        // $this->authorize('update', $submission);
        
        if (is_null($submission) || is_null($submission->id)) {
            Log::error('Resume: ChecklistSubmission not found or ID is null.', ['submission_id_from_route' => request()->route('submission'), 'submission_object' => $submission]);
            return redirect()->route('checklists.medical-specialist.drafts')->with('error', 'The draft you are trying to resume could not be found.');
        }

        Log::info('Attempting to resume submission', ['submission_id' => $submission->id]);

        $progress = ChecklistProgress::where('checklist_submission_id', $submission->id)->first();
        
        if (is_null($progress)) {
            Log::warning('ChecklistProgress record not found for submission. Creating a new one.', ['submission_id' => $submission->id]);
            $progress = ChecklistProgress::create([
                'checklist_submission_id' => $submission->id,
                'current_step' => 1,
                'completed_steps' => [],
                'is_draft' => true
            ]);
        }

        return redirect()->route('checklists.medical-specialist.step', [
            'submission' => $submission->id,
            'step' => $progress->current_step
        ]);
    }

   public function submit(ChecklistSubmission $submission)
{
    $progress = ChecklistProgress::where('checklist_submission_id', $submission->id)->first();

    if (count($progress->completed_steps ?? []) < 7) {
        return back()->with('error', 'Please complete all sections before submitting.');
    }

    DB::transaction(function () use ($submission, $progress) {
        // Mark submission as completed
        $submission->update([
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);

        $progress->update(['is_draft' => false]);

        // Save answers into SubmissionAnswer
        foreach ($progress->draft_data as $step => $answers) {
            foreach ($answers as $questionId => $value) {

                $data = [
                    'rating' => null, // Keep rating as null for medical specialist checklists
                    'notes' => null,
                    'value' => null, // Initialize new 'value' field
                ];

                if (is_array($value)) {
                    $data['value'] = json_encode($value); // Store arrays as JSON in 'value'
                } else {
                    $data['value'] = $value; // Store other types directly in 'value'
                }

                \App\Models\SubmissionAnswer::updateOrCreate(
                    [
                        'checklist_submission_id' => $submission->id,
                        'question_key' => $questionId, // use config question id
                    ],
                    $data
                );
            }
        }
    });

    return redirect()->route('checklists.medical-specialist.success', $submission->id)
        ->with('success', 'Checklist submitted successfully!');
}


    protected function getStepQuestions($step)
    {
        return config('medical_specialist_checklist.questions.' . $step, []);
    }

    protected function createMedicalSpecialistChecklist()
    {
        return Checklist::create([
            'title' => $title = 'Medical Specialist Training Institution Checklist',
            'slug' => Str::slug($title),
            'description' => 'Comprehensive checklist for medical specialist training institutions',
            'type' => 'medical_specialist',
            'is_active' => true
        ]);
    }

    public function drafts()
    {
        $drafts = ChecklistSubmission::where('user_id', Auth::id())
            ->where('status', 'draft')
            ->with('progress')
            ->get();

        return view('checklists.medical-specialist.drafts', ['drafts' => $drafts]);
    }

    public function destroyDraft(ChecklistSubmission $submission)
    {
        // $this->authorize('delete', $submission);

        DB::transaction(function () use ($submission) {
            $submission->progress()->delete();
            $submission->delete();
        });

        return redirect()->route('checklists.medical-specialist.drafts')->with('success', 'Draft deleted successfully.');
    }

    public function results(ChecklistSubmission $submission)
    {
        // $this->authorize('view', $submission);

        $submission->load(['checklist', 'user', 'answers']); // No need for checklistItem here

        $progress = ChecklistProgress::where('checklist_submission_id', $submission->id)->firstOrFail();
        $allQuestions = collect($this->steps)->mapWithKeys(function ($title, $step) {
            return [$step => $this->getStepQuestions($step)];
        });

        // Create a map of question_key to SubmissionAnswer for easy lookup
        $answersMap = $submission->answers->keyBy('question_key');

        return view('checklists.medical-specialist.results', [
            'submission' => $submission,
            'progress' => $progress,
            'steps' => $this->steps,
            'allQuestions' => $allQuestions,
            'answersMap' => $answersMap, // Pass the answers map to the view
        ]);
    }
    
}
