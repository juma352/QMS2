<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\Subdivision;
use App\Models\Staff;
use App\Models\ProgramDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProgramController extends Controller
{
    public function index()
    {
        $programs = Program::with(['school.subdivision', 'facultyMember'])->latest()->get();
        return view('programs.index', compact('programs'));
    }

    public function create()
    {
        $subdivisions = Subdivision::with('schools.programs')->orderBy('name')->get();
        $staff = Staff::orderBy('first_name')->get();
        return view('programs.create', compact('subdivisions', 'staff'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'program_id' => [
                'required', 'exists:programs,id',
                Rule::unique('programs', 'id')->where(fn ($query) => $query->whereNotNull('faculty_member_id'))
            ],
            'faculty_member_id' => 'nullable|exists:staff,id',
            'program_abbr' => 'nullable|string|max:50',
            'license_date' => 'nullable|date',
            'appointment_date' => 'nullable|date',
            'documents' => 'nullable|array',
            'documents.*.name' => 'required_with:documents.*.file|string|max:255',
            'documents.*.file' => 'required_with:documents.*.name|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
        ], [
            'program_id.unique' => 'The selected program has already been configured. Please edit the existing entry.'
        ]);

        $program = Program::findOrFail($validated['program_id']);

        DB::transaction(function () use ($program, $request, $validated) {
            // ✅ CORRECTION: Use the $validated data array for security and consistency.
            $program->update($validated);

            if ($request->has('documents')) {
                foreach ($request->input('documents') as $index => $docData) {
                    if ($request->hasFile("documents.{$index}.file") && !empty($docData['name'])) {
                        $file = $request->file("documents.{$index}.file");
                        $filePath = $file->store('program_documents', 'public');
                        $program->documents()->create([
                            'document_name' => $docData['name'],
                            'file_path' => $filePath,
                        ]);
                    }
                }
            }
        });

        return redirect()->route('programs.index')->with('success', 'Program details saved successfully.');
    }

    public function show(Program $program)
    {
        $program->load(['school.subdivision', 'facultyMember', 'documents']);
        return view('programs.show', compact('program'));
    }

    public function edit(Program $program)
    {
        $program->load('documents'); // Load documents
        $staff = Staff::all();
        return view('programs.edit', compact('program', 'staff'));
    }

    public function update(Request $request, Program $program)
    {
        $validated = $request->validate([
            'faculty_member_id' => 'nullable|exists:staff,id',
            'program_abbr' => 'nullable|string|max:50',
            'license_date' => 'nullable|date',
            'appointment_date' => 'nullable|date',
            'documents' => 'nullable|array',
            'documents.*.name' => 'required_with:documents.*.file|string|max:255',
            'documents.*.file' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
            'existing_documents' => 'nullable|array',
            'existing_documents.*.name' => 'required|string|max:255',
            'deleted_documents' => 'nullable|array',
            'deleted_documents.*' => 'integer|exists:program_documents,id'
        ]);

        DB::transaction(function () use ($program, $request, $validated) {
            // ✅ CORRECTION: Use the $validated data array here as well.
            $program->update($validated);

            if ($request->filled('deleted_documents')) {
                foreach ($validated['deleted_documents'] as $docId) {
                    $document = ProgramDocument::find($docId);
                    if ($document && $document->program_id === $program->id) {
                        Storage::disk('public')->delete($document->file_path);
                        $document->delete();
                    }
                }
            }

            if ($request->filled('existing_documents')) {
                foreach ($validated['existing_documents'] as $id => $docData) {
                    $document = ProgramDocument::find($id);
                    if ($document && $document->program_id === $program->id) {
                        $document->update(['document_name' => $docData['name']]);
                    }
                }
            }

            if ($request->has('documents')) {
                foreach ($request->input('documents') as $index => $docData) {
                    if ($request->hasFile("documents.{$index}.file") && !empty($docData['name'])) {
                        $file = $request->file("documents.{$index}.file");
                        $filePath = $file->store('program_documents', 'public');
                        $program->documents()->create([
                            'document_name' => $docData['name'],
                            'file_path' => $filePath,
                        ]);
                    }
                }
            }
        });

        return redirect()->route('programs.index')->with('success', 'Program updated successfully.');
    }

    public function destroy(Program $program)
    {
        DB::transaction(function () use ($program) {
            foreach ($program->documents as $document) {
                if ($document->file_path) {
                    Storage::disk('public')->delete($document->file_path);
                }
            }
            $program->delete();
        });

        return redirect()->route('programs.index')->with('success', 'Program and all associated documents deleted successfully.');
    }
}
