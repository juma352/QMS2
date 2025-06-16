<?php

namespace App\Http\Controllers;

use App\Models\Program;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    public function index()
    {
        $programs = Program::latest()->get();
        return view('programs.index', compact('programs'));
    }

    public function create()
    {
        return view('programs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'program_name' => 'required|string|max:255',
            'program_abbr' => 'nullable|string|max:50',
            'subdivision' => 'nullable|string',
            'faculty_member' => 'nullable|string',
            'role' => 'nullable|string',
            'license_renewal_date' => 'nullable|date',
            'next_approval_date' => 'nullable|date',
            'license_document' => 'nullable|file',
            'approval_document' => 'nullable|file',
            'certificate' => 'nullable|file',
            'other_documents' => [
                'nullable',
                'file',
                function ($attribute, $value, $fail) use ($request) {
                    $file = $request->file('other_documents');

                    if (is_array($file)) {
                        $fail('Only one file is allowed.');
                        return;
                    }

                    if ($file && strtolower($file->getClientOriginalExtension()) === 'exe') {
                        $fail('Executable files are not allowed.');
                    }
                },
            ],
        ]);

        foreach (['license_document', 'approval_document', 'certificate', 'other_documents'] as $field) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('documents'), $filename);
                $validated[$field] = 'documents/' . $filename;
            }
        }

        Program::create($validated);

        return redirect()->route('programs.index')->with('success', 'Program created successfully.');
    }

    public function show(Program $program)
    {
        return view('programs.show', compact('program'));
    }

    public function edit(Program $program)
    {
        return view('programs.edit', compact('program'));
    }

    public function update(Request $request, Program $program)
    {
        $validated = $request->validate([
            'program_name' => 'required|string|max:255',
            'program_abbr' => 'nullable|string|max:50',
            'subdivision' => 'nullable|string',
            'faculty_member' => 'nullable|string',
            'role' => 'nullable|string',
            'license_renewal_date' => 'nullable|date',
            'next_approval_date' => 'nullable|date',
            'license_document' => 'nullable|file',
            'approval_document' => 'nullable|file',
            'certificate' => 'nullable|file',
            'other_documents' => [
                'nullable',
                'file',
                function ($attribute, $value, $fail) use ($request) {
                    $file = $request->file('other_documents');

                    if (is_array($file)) {
                        $fail('Only one file is allowed.');
                        return;
                    }

                    if ($file && strtolower($file->getClientOriginalExtension()) === 'exe') {
                        $fail('Executable files are not allowed.');
                    }
                },
            ],
        ]);

        foreach (['license_document', 'approval_document', 'certificate', 'other_documents'] as $field) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('documents'), $filename);
                $validated[$field] = 'documents/' . $filename;
            }
        }

        $program->update($validated);

        return redirect()->route('programs.index')->with('success', 'Program updated successfully.');
    }

    public function destroy(Program $program)
    {
        $program->delete();
        return redirect()->route('programs.index')->with('success', 'Program deleted successfully.');
    }
}
