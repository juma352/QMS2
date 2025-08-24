<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Checklist;
use App\Models\ChecklistItem;
use App\Models\ChecklistSubmission;
use App\Models\Audit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ChecklistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        try {
            return app(DynamicChecklistController::class)->dynamic_index();
        } catch (\Exception $e) {
            Log::error('Error in index delegation: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->with('error', 'Failed to load checklists. Please try again.');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function create()
    {
        try {
            return app(DynamicChecklistController::class)->create(request('audit'));
        } catch (\Exception $e) {
            Log::error('Error in create delegation: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->with('error', 'Failed to load create form. Please try again.');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        try {
            return app(DynamicChecklistController::class)->store($request);
        } catch (\Exception $e) {
            Log::error('Error in store delegation: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->with('error', 'Failed to store checklist. Please try again.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Checklist  $checklist
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function show(Checklist $checklist)
    {
        try {
            $itemsBySection = $checklist->items->groupBy('section');
            return view('checklists.show', compact('checklist', 'itemsBySection'));
        } catch (\Exception $e) {
            Log::error('Error in show delegation: ' . $e->getMessage(), ['exception' => $e, 'checklist_id' => $checklist->id ?? null]);
            return redirect()->back()->with('error', 'Failed to load checklist details. Please try again.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Checklist  $checklist
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function edit(Request $request, Checklist $checklist)
    {
        try {
            if (!$checklist->audit) {
                return redirect()->back()->with('error', 'This checklist is not associated with an audit and cannot be edited this way.');
            }

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

            DB::transaction(function () use ($validated, $checklist) {
                $submission = ChecklistSubmission::create([
                    'checklist_id' => $checklist->id,
                    'user_id' => Auth::id(),
                    'department_name' => $validated['department_name'],
                    'status' => 'Completed',
                ]);
                // Note: Ratings and comments are not processed here; consider moving to DynamicChecklistController
            });

            return app(DynamicChecklistController::class)->edit($checklist->audit);
        } catch (\Exception $e) {
            Log::error('Error in edit delegation: ' . $e->getMessage(), ['exception' => $e, 'checklist_id' => $checklist->id ?? null]);
            return redirect()->back()->with('error', 'Failed to load edit form. Please try again.');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Checklist  $checklist
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Checklist $checklist)
    {
        try {
            return app(DynamicChecklistController::class)->update($request, $checklist);
        } catch (\Exception $e) {
            Log::error('Error in update delegation: ' . $e->getMessage(), ['exception' => $e, 'checklist_id' => $checklist->id ?? null]);
            return redirect()->back()->with('error', 'Failed to update checklist. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Checklist  $checklist
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function destroy(Checklist $checklist)
    {
        try {
            return app(DynamicChecklistController::class)->destroy($checklist);
        } catch (\Exception $e) {
            Log::error('Error in destroy delegation: ' . $e->getMessage(), ['exception' => $e, 'checklist_id' => $checklist->id ?? null]);
            return redirect()->back()->with('error', 'Failed to delete checklist. Please try again.');
        }
    }

    /**
     * Legacy checklists index
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function checklistsIndex()
    {
        try {
            return $this->index();
        } catch (\Exception $e) {
            Log::error('Error in checklistsIndex delegation: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->with('error', 'Failed to load legacy checklists. Please try again.');
        }
    }

    /**
     * Legacy results index
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function resultsIndex()
    {
        try {
            return app(DynamicChecklistController::class)->resultsIndex();
        } catch (\Exception $e) {
            Log::error('Error in resultsIndex delegation: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->with('error', 'Failed to load results index. Please try again.');
        }
    }

    /**
     * Print results of a submission.
     *
     * @param  \App\Models\ChecklistSubmission  $submission
     * @return \Illuminate\Http\Response
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
     * Edit a submission.
     *
     * @param  \App\Models\ChecklistSubmission  $submission
     * @return \Illuminate\Http\Response
     */
    public function editSubmission(ChecklistSubmission $submission)
    {
        if (Auth::id() !== $submission->user_id) {
            abort(403);
        }

        $submission->load(['checklist', 'answers.checklistItem']);
        return view('checklists.results_show', compact('submission'));
    }

    /**
     * Update a submission.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ChecklistSubmission  $submission
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function updateSubmission(Request $request, ChecklistSubmission $submission)
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
     *
     * @return \Illuminate\Http\Response
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
     *
     * @param  string  $department
     * @return \Illuminate\Http\Response
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
     *
     * @param  string  $department
     * @param  int  $checklist_id
     * @return \Illuminate\Http\Response
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
     *
     * @return \Illuminate\Http\Response
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
     *
     * @return \Illuminate\Http\Response
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