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
     * Show the blank form for filling out a checklist.
     * Redirects to results if already completed.
     */
    public function show(Checklist $checklist)
    {
        $existingSubmission = ChecklistSubmission::where('user_id', Auth::id())
            ->where('checklist_id', $checklist->id)
            ->first();

        if ($existingSubmission) {
            return redirect()->route('checklists.results.show', $existingSubmission->id)
                ->with('info', 'You have already completed this checklist. Here are your results.');
        }

        $itemsBySection = $checklist->items->groupBy('section');
        $subdivisions = \App\Models\Subdivision::all();
        return view('checklists.show', compact('checklist', 'itemsBySection', 'subdivisions'));
    }


    /**
     * Show the form for editing a checklist submission.
     */
    public function edit(ChecklistSubmission $submission)
    {
        // Authorization: Ensure the logged-in user owns this submission.
        if (Auth::id() !== $submission->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $submission->load(['checklist', 'answers.checklistItem']);
        return view('checklists.results_show', compact('submission')); // Reuse the same view
    }

    /**
     * Update an existing checklist submission.
     */
    public function update(Request $request, ChecklistSubmission $submission)
    {
        // Authorization: Ensure the logged-in user owns this submission.
        if (Auth::id() !== $submission->user_id) {
            abort(403, 'Unauthorized action.');
        }

        Log::info('--- Checklist Submission Update Start ---');
        $validated = $request->validate([
            'ratings' => 'required|array',
            'ratings.*' => 'required|integer|between:1,7',
            'comments' => 'nullable|array',
            'comments.*' => 'nullable|string',
        ]);
        Log::info('Validation Passed.');

        try {
            DB::transaction(function () use ($validated, $submission) {
                foreach ($validated['ratings'] as $itemId => $rating) {
                    $submission->answers()->where('checklist_item_id', $itemId)->update([
                        'rating' => $rating,
                        'comments' => $validated['comments'][$itemId] ?? null,
                    ]);
                }
                $submission->update(['status' => 'Completed']);
            });
        } catch (\Exception $e) {
            Log::error('Checklist Submission Update Failed: ' . $e->getMessage());
            return redirect()->back()->with('error', '\Error updating checklist.')->withInput();
        }

        Log::info('--- Checklist Submission Update End: Success ---');
        return redirect()->route('checklists.results.show', $submission->id)
            ->with('message', 'Submission updated successfully!');
    }

    /**
     * Store a new checklist submission.
     */
    public function store(Request $request, Checklist $checklist)
    {
        Log::info('--- Checklist Submission Start ---');
        $validated = $request->validate([
            'ratings' => 'required|array',
            'ratings.*' => 'required|integer|between:1,7',
            'comments' => 'nullable|array',
            'comments.*' => 'nullable|string',
            'subdivision_id' => 'required|exists:subdivisions,id',
        ]);
        Log::info('Validation Passed.');

        try {
            DB::transaction(function () use ($validated, $checklist) {
                $submission = ChecklistSubmission::create([
                    'checklist_id' => $checklist->id,
                    'user_id' => Auth::id(),
                    'subdivision_id' => $validated['subdivision_id'],
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

        Log::info('--- Checklist Submission End: Success ---');
        return redirect()->route('dashboard')->with('message', $checklist->title . ' submitted successfully!');
    }

    /**
     * NEW: Display a list of the user's completed checklist submissions.
     */
    public function resultsIndex(Request $request)
    {
        $query = ChecklistSubmission::where('user_id', Auth::id())
            ->with(['checklist', 'subdivision']);

        // Filter by subdivision if provided
        if ($request->has('subdivision') && $request->subdivision) {
            $query->where('subdivision_id', $request->subdivision);
        }

        $submissions = $query->latest()->paginate(10);
        $subdivisions = \App\Models\Subdivision::all();

        return view('checklists.results_index', compact('submissions', 'subdivisions'));
    }

    /**
     * NEW: Show the detailed results of a single submission.
     */
    public function resultsShow(ChecklistSubmission $submission)
    {
        // Authorization: Ensure the logged-in user owns this submission.
        if (Auth::id() !== $submission->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $submission->load(['checklist', 'answers.checklistItem']);
        return view('checklists.results_show', compact('submission'));
    }
}
