<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\Staff;
use App\Models\Subdivision;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Import Storage facade for file deletion

class ProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Eager load relationships for efficiency on the index page
        $programs = Program::with('school.subdivision', 'facultyMember')->latest()->get();
        return view('programs.index', compact('programs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Get all data needed for the dynamic dropdowns
        return view('programs.create', [
            'subdivisions' => Subdivision::with('schools.programs')->orderBy('name')->get(),
            'staff' => Staff::orderBy('first_name')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * This method "fills in the details" for a program shell created by the seeder.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'program_id' => 'required|exists:programs,id|unique:programs,id,' . $request->program_id . ',id,faculty_member_id,!=,NULL',
            'faculty_member_id' => 'nullable|exists:staff,id',
            'program_abbr' => 'nullable|string|max:50',
            'role' => 'nullable|string',
            'license_renewal_date' => 'nullable|date',
            'next_approval_date' => 'nullable|date',
            'license_document' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
            'approval_document' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
            'certificate' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
            'other_documents' => 'nullable|file|max:10240',
        ], [
            'program_id.unique' => 'The selected program has already been configured. Please edit the existing entry.'
        ]);

        // Find the program shell selected by the user
        $program = Program::findOrFail($validated['program_id']);

        // Prepare data for the update
        $updateData = $request->except(['_token', 'subdivision_id', 'school_id', 'program_id']);

        // Handle file uploads
        foreach (['license_document', 'approval_document', 'certificate', 'other_documents'] as $field) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                // Store file and get path
                $path = $file->store('documents', 'public');
                $updateData[$field] = $path;
            }
        }

        $program->update($updateData);

        return redirect()->route('programs.index')->with('success', 'Program details saved successfully.');
    }

    /**
     * Display the specified resource.
     */
     public function show(Program $program)
    {
        // Use ->load() to fetch the relationships for the given program.
        // 'school.subdivision' tells Laravel to get the school, AND the school's subdivision.
        $program->load('school.subdivision', 'facultyMember');

        return view('programs.show', compact('program'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Program $program)
    {
        $program->load('school.subdivision', 'facultyMember');

        return view('programs.edit', [
            'program' => $program,
            'subdivisions' => Subdivision::with('schools.programs')->orderBy('name')->get(),
            'staff' => Staff::orderBy('first_name')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Program $program)
    {
        $validated = $request->validate([
            // In an update, we don't need to re-validate the program_id
            'faculty_member_id' => 'nullable|exists:staff,id',
            'program_abbr' => 'nullable|string|max:50',
            'role' => 'nullable|string',
            'license_renewal_date' => 'nullable|date',
            'next_approval_date' => 'nullable|date',
            'license_document' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
            'approval_document' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
            'certificate' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
            'other_documents' => 'nullable|file|max:10240',
        ]);

        $updateData = $request->except(['_token', '_method', 'subdivision_id', 'school_id', 'program_id']);

        // Handle file uploads, replacing old files if new ones are provided
        foreach (['license_document', 'approval_document', 'certificate', 'other_documents'] as $field) {
            if ($request->hasFile($field)) {
                // Delete the old file if it exists
                if ($program->$field) {
                    Storage::disk('public')->delete($program->$field);
                }
                // Store the new file
                $path = $request->file($field)->store('documents', 'public');
                $updateData[$field] = $path;
            }
        }

        $program->update($updateData);

        return redirect()->route('programs.index')->with('success', 'Program updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Program $program)
    {
        // Delete all associated files from storage before deleting the record
        foreach (['license_document', 'approval_document', 'certificate', 'other_documents'] as $field) {
            if ($program->$field) {
                Storage::disk('public')->delete($program->$field);
            }
        }

        $program->delete();

        return redirect()->route('programs.index')->with('success', 'Program deleted successfully.');
    }
}
