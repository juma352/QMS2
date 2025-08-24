<?php

namespace App\Http\Controllers;

use App\Models\Standard;
use Illuminate\Http\Request;

class StandardController extends Controller
{
    /**
     * Display a listing of standards.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Standard::query();

        if ($request->has('search') && $request->input('search') != '') {
            $searchTerm = $request->input('search');
            $query->where('standard_name', 'like', "%{$searchTerm}%")
                  ->orWhere('standard_number', 'like', "%{$searchTerm}%");
        }

        $standards = $query->orderBy('standard_name', 'asc')->paginate(10);

        return view('standards.index', compact('standards'));
    }

    /**
     * Show the form for creating a new standard.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('standards.create');
    }

    /**
     * Store a newly created standard in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $rules = [
            'standard_name' => ['required', 'string', 'max:255'],
            'standard_number' => ['required', 'string', 'max:100', 'unique:standards,standard_number'],
            'standard' => ['nullable', 'string'],
            'clause' => ['nullable', 'string', 'max:255'],
            'revision_version' => ['nullable', 'string', 'max:50'],
            'date_created' => ['nullable', 'date'],
            'date_of_issue' => ['nullable', 'date'],
            'next_revision_date' => ['nullable', 'date'],
            'next_date_of_issue' => ['nullable', 'date'],
            'standard_file' => ['nullable', 'file', 'mimes:pdf,docx,jpeg,png', 'max:5120'],
            'other_documents.*' => ['nullable', 'file', 'mimes:pdf,docx,jpeg,png', 'max:5120'],
        ];

        $validatedData = $request->validate($rules);

        if ($request->hasFile('standard_file')) {
            $file = $request->file('standard_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('documents/standards'), $filename);
            $validatedData['standard_file'] = 'documents/standards/' . $filename;
        }

        if ($request->hasFile('other_documents')) {
            $paths = [];
            foreach ($request->file('other_documents') as $file) {
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('documents/standards'), $filename);
                $paths[] = 'documents/standards/' . $filename;
            }
            $validatedData['other_documents'] = json_encode($paths);
        }

        Standard::create($validatedData);

        return redirect()->route('standards.index')->with('message', 'Standard added successfully.');
    }

    /**
     * Display the specified standard.
     *
     * @param  \App\Models\Standard  $standard
     * @return \Illuminate\View\View
     */
    public function show(Standard $standard)
    {
        return view('standards.show', compact('standard'));
    }

    

    /**
     * Show the form for editing the specified standard.
     *
     * @param  \App\Models\Standard  $standard
     * @return \Illuminate\View\View
     */
    public function edit(Standard $standard)
    {
        return view('standards.edit', compact('standard'));
    }

    /**
     * Update the specified standard in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Standard  $standard
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Standard $standard)
    {
        $rules = [
            'standard_name' => ['required', 'string', 'max:255'],
            'standard_number' => ['required', 'string', 'max:100', 'unique:standards,standard_number,' . $standard->id],
            'standard' => ['nullable', 'string'],
            'clause' => ['nullable', 'string', 'max:255'],
            'revision_version' => ['nullable', 'string', 'max:50'],
            'date_created' => ['nullable', 'date'],
            'date_of_issue' => ['nullable', 'date'],
            'next_revision_date' => ['nullable', 'date'],
            'next_date_of_issue' => ['nullable', 'date'],
            'standard_file' => ['nullable', 'file', 'mimes:pdf,docx,jpeg,png', 'max:5120'],
            'other_documents.*' => ['nullable', 'file', 'mimes:pdf,docx,jpeg,png', 'max:5120'],
        ];

        $validatedData = $request->validate($rules);

        if ($request->hasFile('standard_file')) {
            if ($standard->standard_file && file_exists(public_path($standard->standard_file))) {
                unlink(public_path($standard->standard_file));
            }
            $file = $request->file('standard_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('documents/standards'), $filename);
            $validatedData['standard_file'] = 'documents/standards/' . $filename;
        }

        if ($request->hasFile('other_documents')) {
            if ($standard->other_documents) {
                foreach (json_decode($standard->other_documents, true) as $oldFile) {
                    if (file_exists(public_path($oldFile))) {
                        unlink(public_path($oldFile));
                    }
                }
            }
            $paths = [];
            foreach ($request->file('other_documents') as $file) {
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('documents/standards'), $filename);
                $paths[] = 'documents/standards/' . $filename;
            }
            $validatedData['other_documents'] = json_encode($paths);
        }

        $standard->update($validatedData);

        return redirect()->route('standards.index')->with('message', 'Standard updated successfully.');
    }

    /**
     * Remove the specified standard from storage.
     *
     * @param  \App\Models\Standard  $standard
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Standard $standard)
    {
        if ($standard->standard_file && file_exists(public_path($standard->standard_file))) {
            unlink(public_path($standard->standard_file));
        }

        if ($standard->other_documents) {
            foreach (json_decode($standard->other_documents, true) as $file) {
                if (file_exists(public_path($file))) {
                    unlink(public_path($file));
                }
            }
        }

        $standard->delete();

        return redirect()->route('standards.index')->with('message', 'Standard deleted successfully.');
    }
}