<?php

namespace App\Http\Controllers;

use App\Models\CqiProject;
use Illuminate\Http\Request;

class CqiProjectController extends Controller
{
    // Define options for dropdowns to be used in create and edit views
    private function getDropdownOptions()
    {
        return [
            'methodologies' => [
                'PDSA (Plan-Do-Study-Act)',
                'DMAIC (Define-Measure-Analyze-Improve-Control)',
                'Lean',
                'Six Sigma',
                'Other'
            ],
            'statuses' => [
                'Not Started',
                'In Progress',
                'Completed',
                'On Hold',
                'Cancelled'
            ]
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = CqiProject::latest()->paginate(10);
        return view('cqi_projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('cqi_projects.create', $this->getDropdownOptions());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'project_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'mission' => 'nullable|string',
            'project_leader' => 'nullable|string|max:255',
            'methodology' => 'required|string',
            'problem_statement' => 'nullable|string',
            'smart_goals' => 'nullable|string',
            'metrics_to_track' => 'nullable|string',
            'data_collection_method' => 'nullable|string',
            'initial_progress' => 'required|integer|min:0|max:100',
            'status' => 'required|string',
        ]);

        CqiProject::create($validatedData);

        return redirect()->route('cqi_projects.index')->with('message', 'CQI Project added successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(CqiProject $cqiProject)
    {
        return view('cqi_projects.show', compact('cqiProject'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CqiProject $cqiProject)
    {
        $options = $this->getDropdownOptions();
        return view('cqi_projects.edit', compact('cqiProject') + $options);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CqiProject $cqiProject)
    {
        $validatedData = $request->validate([
            'project_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'mission' => 'nullable|string',
            'project_leader' => 'nullable|string|max:255',
            'methodology' => 'required|string',
            'problem_statement' => 'nullable|string',
            'smart_goals' => 'nullable|string',
            'metrics_to_track' => 'nullable|string',
            'data_collection_method' => 'nullable|string',
            'initial_progress' => 'required|integer|min:0|max:100',
            'status' => 'required|string',
        ]);

        $cqiProject->update($validatedData);

        return redirect()->route('cqi_projects.index')->with('message', 'CQI Project updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CqiProject $cqiProject)
    {
        $cqiProject->delete();
        return redirect()->route('cqi_projects.index')->with('message', 'CQI Project deleted successfully.');
    }
}