<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Checklist;
use App\Models\ChecklistItem;
use App\Models\ChecklistSubmission;
use App\Models\Audit;

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
            \Log::error('Error in index delegation: ' . $e->getMessage(), ['exception' => $e]);
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
            \Log::error('Error in create delegation: ' . $e->getMessage(), ['exception' => $e]);
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
            \Log::error('Error in store delegation: ' . $e->getMessage(), ['exception' => $e]);
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
            return app(DynamicChecklistController::class)->show($checklist);
        } catch (\Exception $e) {
            \Log::error('Error in show delegation: ' . $e->getMessage(), ['exception' => $e, 'checklist_id' => $checklist->id]);
            return redirect()->back()->with('error', 'Failed to load checklist details. Please try again.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Checklist  $checklist
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function edit(Checklist $checklist)
    {
        try {
            if (!$checklist->audit) {
                return redirect()->back()->with('error', 'This checklist is not associated with an audit and cannot be edited this way.');
            }

            return app(DynamicChecklistController::class)->edit($checklist->audit);
        } catch (\Exception $e) {
            \Log::error('Error in edit delegation: ' . $e->getMessage(), ['exception' => $e, 'checklist_id' => $checklist->id]);
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
            \Log::error('Error in update delegation: ' . $e->getMessage(), ['exception' => $e, 'checklist_id' => $checklist->id]);
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
            \Log::error('Error in destroy delegation: ' . $e->getMessage(), ['exception' => $e, 'checklist_id' => $checklist->id]);
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
            \Log::error('Error in checklistsIndex delegation: ' . $e->getMessage(), ['exception' => $e]);
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
            \Log::error('Error in resultsIndex delegation: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->with('error', 'Failed to load results index. Please try again.');
        }
    }
}