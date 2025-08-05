<?php

namespace App\Http\Controllers;

use App\Models\Checklist;
use App\Models\ChecklistSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ChecklistController extends Controller
{
    /**
     * Show the checklist form for submission.
     */
    public function show(Checklist $checklist)
    {
        $itemsBySection = $checklist->items->groupBy('section');
        return view('checklists.show', compact('checklist', 'itemsBySection'));
    }

    /**
     * Store a new checklist submission.
     */
    public function store(Request $request, Checklist $checklist)
    {
        $validated = $request->validate([
            'ratings' => 'required|array',
            'ratings.*' => 'required|integer|between:1,7',
            'comments' => 'nullable|array',
            'comments.*' => 'nullable|string',
            'department_name' => 'required|string|max:255',
        ]);

        $existingSubmission = ChecklistSubmission::where('checklist_id', $checklist->id)
            ->where('department_name', $validated['department_name'])
            ->first();

        if ($existingSubmission) {
            return redirect()->route('checklists.results.show', $existingSubmission->id)
                ->with('info', 'This department has already completed this checklist.');
        }

        try {
            DB::transaction(function () use ($validated, $checklist) {
                $submission = ChecklistSubmission::create([
                    'checklist_id' => $checklist->id,
                    'user_id' => Auth::id(),
                    'department_name' => $validated['department_name'],
                    'status' => 'Completed',
                ]);

                foreach ($validated['ratings'] as $itemId => $rating) {
                    $submission->answers()->create([
                        'checklist_item_id' => $itemId,
                        'rating' => $rating,
                        'comments' => $validated['comments'][$itemId] ?? null,
                    ]);
                }
            });
        } catch (\Exception $e) {
            Log::error('Checklist Submission Failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error saving checklist.')->withInput();
        }

        return redirect()->route('dashboard')->with('message', $checklist->title . ' submitted successfully!');
    }

    /**
     * Show detailed results of a submission.
     */
    public function resultsShow(ChecklistSubmission $submission)
    {
        if (Auth::id() !== $submission->user_id) {
            abort(403);
        }

        $submission->load(['checklist', 'answers.checklistItem']);
        return view('checklists.results_show', compact('submission'));
    }

    /**
     * Print results of a submission.
     */
    public function printResults(ChecklistSubmission $submission)
    {
        if (Auth::id() !== $submission->user_id) {
            abort(403);
        }

        $submission->load(['checklist', 'answers.checklistItem']);
        return view('checklists.results_print', compact('submission'));
    }

    /**
     * Edit a submission (optional).
     */
    public function edit(ChecklistSubmission $submission)
    {
        if (Auth::id() !== $submission->user_id) {
            abort(403);
        }

        $submission->load(['checklist', 'answers.checklistItem']);
        return view('checklists.results_show', compact('submission'));
    }

    /**
     * Update a submission (optional).
     */
    public function update(Request $request, ChecklistSubmission $submission)
    {
        if (Auth::id() !== $submission->user_id) {
            abort(403);
        }

        $validated = $request->validate([
            'ratings' => 'required|array',
            'ratings.*' => 'required|integer|between:1,7',
            'comments' => 'nullable|array',
            'comments.*' => 'nullable|string',
            'department_name' => 'required|string|max:255',
        ]);

        $existingSubmission = ChecklistSubmission::where('checklist_id', $submission->checklist_id)
            ->where('department_name', $validated['department_name'])
            ->where('id', '!=', $submission->id)
            ->first();

        if ($existingSubmission) {
            return redirect()->route('checklists.results.show', $existingSubmission->id)
                ->with('info', 'Another submission already exists for this department and checklist.');
        }

        try {
            DB::transaction(function () use ($validated, $submission) {
                $submission->update([
                    'department_name' => $validated['department_name'],
                    'status' => 'Completed',
                ]);

                foreach ($validated['ratings'] as $itemId => $rating) {
                    $submission->answers()->where('checklist_item_id', $itemId)->update([
                        'rating' => $rating,
                        'comments' => $validated['comments'][$itemId] ?? null,
                    ]);
                }
            });
        } catch (\Exception $e) {
            Log::error('Checklist Submission Update Failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error updating checklist.')->withInput();
        }

        return redirect()->route('checklists.results.show', $submission->id)
            ->with('message', 'Submission updated successfully!');
    }

    /**
     * View all departments that submitted checklists.
     */
    public function departmentIndex()
    {
        $departments = ChecklistSubmission::select('department_name')
            ->selectRaw('COUNT(*) as submission_count')
            ->groupBy('department_name')
            ->orderBy('department_name')
            ->get();

        return view('checklists.departments_index', compact('departments'));
    }

    /**
     * View checklists submitted by a specific department.
     */
    public function checklistsByDepartment($department)
    {
        $checklists = ChecklistSubmission::where('department_name', $department)
            ->with('checklist')
            ->select('checklist_id')
            ->selectRaw('COUNT(*) as submission_count')
            ->groupBy('checklist_id')
            ->get();

        return view('checklists.checklists_by_department', compact('checklists', 'department'));
    }

    /**
     * View submissions for a checklist by a department.
     */
    public function submissionsByDepartmentChecklist($department, $checklist_id)
    {
        $submissions = ChecklistSubmission::where('department_name', $department)
            ->where('checklist_id', $checklist_id)
            ->with('checklist')
            ->latest()
            ->get();

        return view('checklists.submissions_by_departments_checklist', compact('submissions', 'department'));
    }

    /**
     * Display a listing of checklists assigned to the authenticated user.
     */
    public function checklistsIndex()
    {
        $user = Auth::user();
        
        // Get all checklists that have been assigned to this user through submissions
        $userChecklists = Checklist::whereHas('submissions', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->with(['submissions' => function ($query) use ($user) {
            $query->where('user_id', $user->id);
        }])->get();

        // Get all submissions for the user to calculate stats
        $userSubmissions = ChecklistSubmission::where('user_id', $user->id)
            ->with('checklist')
            ->get();

        // Calculate statistics
        $completedChecklists = $userSubmissions->groupBy('checklist_id')->count();
        $pendingChecklists = 0; // This would need to be calculated based on your business logic
        
        // Get all checklists for display
        $checklists = Checklist::with(['submissions' => function ($query) use ($user) {
            $query->where('user_id', $user->id);
        }])->get();

        return view('checklists.index', compact(
            'checklists',
            'userSubmissions',
            'completedChecklists',
            'pendingChecklists'
        ));
    }

    /**
     * Display a listing of all checklist results for the authenticated user.
     */
    public function resultsIndex()
    {
        $user = Auth::user();
        
        $submissions = ChecklistSubmission::where('user_id', $user->id)
            ->with(['checklist', 'answers'])
            ->latest()
            ->get();

        return view('checklists.results_index', compact('submissions'));
    }

}
