<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Checklist;
use App\Models\ChecklistItem;
use App\Models\ChecklistSubmission;
use App\Models\Audit;
use Illuminate\Support\Facades\DB;

class ChecklistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Delegate to DynamicChecklistController
        return app(DynamicChecklistController::class)->dynamic_index();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Delegate to DynamicChecklistController
        return app(DynamicChecklistController::class)->create(request('audit'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Delegate to DynamicChecklistController
        return app(DynamicChecklistController::class)->store($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Checklist  $checklist
     * @return \Illuminate\Http\Response
     */
    public function show(Checklist $checklist)
    {
        // Delegate to DynamicChecklistController
        return app(DynamicChecklistController::class)->show($checklist);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Checklist  $checklist
     * @return \Illuminate\Http\Response
     */
    public function edit(Checklist $checklist)
    {
        // Ensure the checklist has an associated audit
        if (!$checklist->audit) {
            // Handle the case where there is no audit, maybe redirect back with an error
            return redirect()->back()->with('error', 'This checklist is not associated with an audit and cannot be edited this way.');
        }

        // Delegate to DynamicChecklistController, passing the associated audit
        return app(DynamicChecklistController::class)->edit($checklist->audit);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Checklist  $checklist
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Checklist $checklist)
    {
        // Delegate to DynamicChecklistController
        return app(DynamicChecklistController::class)->update($request, $checklist);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Checklist  $checklist
     * @return \Illuminate\Http\Response
     */
    public function destroy(Checklist $checklist)
    {
        // Delegate to DynamicChecklistController
        return app(DynamicChecklistController::class)->destroy($checklist);
    }

    /**
     * Legacy checklists index
     */
    public function checklistsIndex()
    {
        return $this->index();
    }

    /**
     * Legacy results index
     */
    public function resultsIndex()
    {
        return app(DynamicChecklistController::class)->resultsIndex();
    }
}
