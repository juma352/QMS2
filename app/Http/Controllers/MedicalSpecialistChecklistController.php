<?php

namespace App\Http\Controllers;

use App\Models\Checklist;
use App\Models\ChecklistSubmission;
use App\Models\ChecklistProgress;
use App\Models\Program;
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

    public function start(): \Illuminate\Http\RedirectResponse
    {
        $slug = Str::slug('Medical Specialist Training Institution Checklist');
        $checklist = Checklist::firstOrNew(['slug' => $slug]);

        try {
            if (!$checklist->exists) {
                $checklist->title = 'Medical Specialist Training Institution Checklist';
                $checklist->type = 'medical_specialist';
                $checklist->description = 'Comprehensive checklist for medical specialist training institutions based on COSECSA standards';
                $checklist->is_active = true;
                $checklist->save();
            }

            $submission = ChecklistSubmission::withoutGlobalScopes()->create([
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

            return redirect()->route('checklists.medical-specialist.step', [
                'submission' => $submission->id,
                'step' => 1
            ]);
        } catch (\Exception $e) {
            Log::error('Error in start method: ' . $e->getMessage(), [
                'exception' => $e,
                'checklist_id' => $checklist->id ?? 'N/A',
                'user_id' => Auth::id() ?? 'N/A'
            ]);
            return redirect()->route('checklists.medical-specialist.drafts')
                ->with('error', 'Failed to start checklist. Please try again.');
        }
    }

    public function showStep(int $submissionId, int $step)
    {
        $submission = ChecklistSubmission::find($submissionId);
        if (!$submission) {
            return redirect()->route('checklists.medical-specialist.drafts')
                ->with('error', 'Checklist submission not found.');
        }

        $this->authorize('update', $submission);

        $progress = ChecklistProgress::where('checklist_submission_id', $submission->id)->first();
        if (!$progress) {
            return redirect()->route('checklists.medical-specialist.drafts')
                ->with('error', 'Checklist progress not found.');
        }

        if ($step < 1 || $step > count($this->steps)) {
            abort(404);
        }

        $stepData = $progress->draft_data[$step] ?? [];
        $questions = $this->getStepQuestions($step);

        foreach ($questions as $key => $question) {
            if (isset($question['type']) && $question['type'] === 'select_dynamic') {
                $modelClass = $question['model'] ?? null;
                $displayColumn = $question['display_column'] ?? null;

                if ($modelClass && $displayColumn && class_exists($modelClass) && in_array($displayColumn, (new $modelClass)->getFillable())) {
                    $options = $modelClass::pluck($displayColumn, 'id')->all();
                    $questions[$key]['options'] = $options;
                } else {
                    Log::warning('Invalid model or column for dynamic select', [
                        'model' => $modelClass,
                        'column' => $displayColumn,
                        'step' => $step,
                    ]);
                    $questions[$key]['options'] = [];
                }
            }
        }

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

    public function saveStep(Request $request, int $submissionId, int $step)
    {
        $submission = ChecklistSubmission::find($submissionId);
        if (!$submission) {
            return redirect()->route('checklists.medical-specialist.drafts')
                ->with('error', 'Checklist submission not found.');
        }

        $this->authorize('update', $submission);

        $progress = ChecklistProgress::where('checklist_submission_id', $submission->id)->first();
        if (!$progress) {
            return redirect()->route('checklists.medical-specialist.drafts')
                ->with('error', 'Checklist progress not found.');
        }

        if ($step < 1 || $step > count($this->steps)) {
            abort(404);
        }

        $questions = $this->getStepQuestions($step);
        $rules = [];
        foreach ($questions as $question) {
            $rules['data.' . $question['id']] = $question['required'] ? 'required' : 'nullable';
            if ($question['type'] === 'text') {
                $rules['data.' . $question['id']] .= '|string|max:1000';
            } elseif ($question['type'] === 'select_dynamic') {
                $rules['data.' . $question['id']] .= '|exists:' . (new $question['model'])->getTable() . ',id';
            }
        }

        $request->validate($rules);

        $draftData = $progress->draft_data ?? [];
        $draftData[$step] = $request->data;

        if ($request->hasFile('uploads')) {
            $request->validate([
                'uploads.*' => 'file|mimes:pdf,doc,docx,jpg,png|max:2048',
            ]);

            foreach ($request->file('uploads') as $questionId => $file) {
                if ($file->isValid()) {
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $path = $file->storeAs('documents/medical_specialist_uploads', $filename, 'public');
                    $draftData[array_key_exists($step, $draftData) ? $step : 'default'][$questionId . '_upload'] = $path;
                }
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
            $progress->current_step = min($step + 1, count($this->steps));
        } elseif ($request->action === 'previous') {
            $completed = $progress->completed_steps ?? [];
            if (!in_array($step, $completed)) {
                $completed[] = $step;
                $progress->completed_steps = $completed;
            }
            $progress->current_step = max($step - 1, 1);
        } elseif ($request->action === 'submit') {
            return $this->submit($submissionId);
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

    public function saveDraft(Request $request, int $submissionId)
    {
        $submission = ChecklistSubmission::find($submissionId);
        if (!$submission) {
            return response()->json(['success' => false, 'message' => 'Checklist submission not found.'], 404);
        }

        $this->authorize('update', $submission);

        $progress = ChecklistProgress::where('checklist_submission_id', $submission->id)->first();
        if (!$progress) {
            return response()->json(['success' => false, 'message' => 'Checklist progress not found.'], 404);
        }

        $progress->last_saved_at = now();
        $progress->save();

        return response()->json(['success' => true, 'message' => 'Draft saved successfully']);
    }

    public function resume(int $submissionId)
    {
        $submission = ChecklistSubmission::find($submissionId);
        if (!$submission) {
            return redirect()->route('checklists.medical-specialist.drafts')
                ->with('error', 'Checklist submission not found.');
        }

        $this->authorize('update', $submission);

        $progress = ChecklistProgress::where('checklist_submission_id', $submission->id)->first();

        if (!$progress) {
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

    public function submit(int $submissionId)
    {
        $submission = ChecklistSubmission::find($submissionId);
        if (!$submission) {
            return redirect()->route('checklists.medical-specialist.drafts')
                ->with('error', 'Checklist submission not found.');
        }

        Log::info('Attempting to submit medical specialist checklist.', [
            'submission_id' => $submission->id,
            'checklist_id' => $submission->checklist_id,
        ]);

        $this->authorize('update', $submission);

        $progress = ChecklistProgress::where('checklist_submission_id', $submission->id)->first();
        if (!$progress) {
            return back()->with('error', 'Checklist progress not found.');
        }

        if (count($progress->completed_steps ?? []) < count($this->steps) - 1) {
            return back()->with('error', 'Please complete all sections before submitting.');
        }

        try {
            DB::transaction(function () use ($submission, $progress) {
                $submission->update([
                    'status' => 'submitted',
                    'submitted_at' => now(),
                ]);

                $progress->update(['is_draft' => false]);

                foreach ($progress->draft_data as $step => $answers) {
                    foreach ($answers as $questionId => $value) {
                        $data = [
                            'rating' => null,
                            'notes' => null,
                            'value' => is_array($value) ? json_encode($value) : $value,
                        ];

                        \App\Models\SubmissionAnswer::updateOrCreate(
                            [
                                'checklist_submission_id' => $submission->id,
                                'question_key' => $questionId,
                            ],
                            $data
                        );
                    }
                }
            });

            return redirect()->route('checklists.medical-specialist.success', $submission->id)
                ->with('success', 'Checklist submitted successfully!');
        } catch (\Exception $e) {
            Log::error('Error submitting checklist: ' . $e->getMessage(), [
                'submission_id' => $submission->id,
                'user_id' => Auth::id(),
            ]);
            return back()->with('error', 'An error occurred while submitting the checklist. Please try again.');
        }
    }

    public function results(int $submissionId)
    {
        $submission = ChecklistSubmission::find($submissionId);
        if (!$submission) {
            return redirect()->route('submissions.index')
                ->with('error', 'Checklist submission not found.');
        }

        $this->authorize('view', $submission);

        try {
            if (!$submission->checklist) {
                Log::warning('Checklist not found for submission. Creating a default checklist.', [
                    'submission_id' => $submission->id,
                    'checklist_id' => $submission->checklist_id,
                ]);
                $checklist = Checklist::firstOrCreate(
                    ['slug' => Str::slug('Medical Specialist Training Institution Checklist')], [
                        'title' => 'Medical Specialist Training Institution Checklist',
                        'type' => 'medical_specialist',
                        'description' => 'Comprehensive checklist for medical specialist training institutions based on COSECSA standards',
                        'is_active' => true,
                    ]
                );
                $submission->checklist_id = $checklist->id;
                $submission->save();
            }

            $submission->load(['checklist', 'user', 'answers']);
            $progress = ChecklistProgress::where('checklist_submission_id', $submission->id)->first();
            if (!$progress) {
                return redirect()->route('submissions.index')
                    ->with('error', 'Checklist progress not found.');
            }

            $allQuestions = collect($this->steps)->mapWithKeys(function ($title, $step) {
                return [$step => $this->getStepQuestions($step)];
            });

            $answersMap = $submission->answers->keyBy('question_key');
            $programs = Program::all();

            return view('checklists.medical-specialist.results', [
                'submission' => $submission,
                'progress' => $progress,
                'steps' => $this->steps,
                'allQuestions' => $allQuestions,
                'answersMap' => $answersMap,
                'programs' => $programs,
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading checklist results: ' . $e->getMessage(), [
                'submission_id' => $submission->id,
                'user_id' => Auth::id(),
                'exception' => $e,
            ]);
            return redirect()->route('submissions.index')
                ->with('error', 'Failed to load checklist results. Please try again.');
        }
    }

    public function duplicate(Request $request, int $submissionId)
    {
        $submission = ChecklistSubmission::find($submissionId);
        if (!$submission) {
            return back()->with('error', 'Checklist submission not found.');
        }

        $this->authorize('update', $submission);

        $request->validate([
            'new_program_id' => 'required|exists:programs,id',
        ]);

        $newProgramId = $request->input('new_program_id');
        $originalProgress = $submission->progress;
        $originalProgramId = $originalProgress->draft_data[1]['programs'] ?? null;

        try {
            $newSubmission = DB::transaction(function () use ($submission, $originalProgress, $newProgramId, $originalProgramId) {
                $newSubmission = $submission->replicate();
                $newSubmission->user_id = Auth::id();
                $newSubmission->status = 'draft';
                $newSubmission->created_at = now();
                $newSubmission->updated_at = now();
                $newSubmission->submitted_at = null;

                $newProgram = Program::find($newProgramId);
                $newSubmission->department_name = $newProgram->program_name;

                if ($originalProgramId == $newProgramId) {
                    $newSubmission->department_name .= ' (Duplicate ' . now()->format('Y-m-d H:i:s') . ')';
                }

                $newSubmission->save();

                $newProgressData = $originalProgress->draft_data;
                $newProgressData[1]['programs'] = $newProgramId;

                ChecklistProgress::create([
                    'checklist_submission_id' => $newSubmission->id,
                    'current_step' => 1,
                    'completed_steps' => [],
                    'is_draft' => true,
                    'draft_data' => $newProgressData,
                ]);

                return $newSubmission;
            });

            return redirect()->route('checklists.medical-specialist.step', [
                'submission' => $newSubmission->id,
                'step' => 1
            ])->with('success', 'Audit duplicated successfully. You are now editing the new draft.');
        } catch (\Exception $e) {
            Log::error('Error duplicating checklist: ' . $e->getMessage(), [
                'submission_id' => $submission->id,
                'user_id' => Auth::id(),
                'new_program_id' => $newProgramId,
            ]);
            return back()->with('error', 'Failed to duplicate checklist. Please try again.');
        }
    }

    public function resultsIndex(): \Illuminate\View\View
    {
        $checklist = Checklist::firstOrCreate(
            ['slug' => Str::slug('Medical Specialist Training Institution Checklist')],
            [
                'title' => 'Medical Specialist Training Institution Checklist',
                'description' => 'Comprehensive checklist for medical specialist training institutions based on COSECSA standards',
                'type' => 'medical_specialist',
                'is_active' => true,
            ]
        );

        $submissions = ChecklistSubmission::whereHas('checklist', function ($query) {
            $query->where('type', 'medical_specialist');
        })->with(['checklist', 'user'])->latest()->paginate(15);

        return view('submissions.medical_specialist_results.index', compact('submissions'));
    }

    public function drafts()
    {
        $drafts = ChecklistSubmission::where('user_id', Auth::id())
            ->where('status', 'draft')
            ->whereHas('checklist', function ($query) {
                $query->where('type', 'medical_specialist');
            })
            ->with('progress') // Eager load progress to get last saved date
            ->latest('updated_at')
            ->get();

        return view('checklists.medical-specialist.drafts', compact('drafts'));
    }

    public function destroyDraft(ChecklistSubmission $submission)
    {
        $this->authorize('delete', $submission);

        try {
            DB::transaction(function () use ($submission) {
                $submission->progress()->delete();
                $submission->answers()->delete();
                $submission->delete();
            });

            return redirect()->route('checklists.medical-specialist.drafts')->with('success', 'Draft deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Error deleting draft: ' . $e->getMessage(), [
                'submission_id' => $submission->id,
                'user_id' => Auth::id(),
            ]);
            return back()->with('error', 'Failed to delete draft. Please try again.');
        }
    }

    protected function getStepQuestions(int $step): array
    {
        return config('medical_specialist_checklist.questions.' . $step, []);
    }
}
