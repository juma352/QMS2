<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $staff = Staff::latest()->get();
        return view('staff.index', compact('staff'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('staff.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'staff_number' => ['required', 'string', 'max:100', 'unique:staff,staff_number'],
            'email' => ['required', 'email', 'unique:staff,email'],
            'department' => ['nullable', 'string', 'max:255'],
            'license_number' => ['nullable', 'string', 'max:255'],
            'license_renewal_date' => ['nullable', 'date'],
            'license_document' => ['nullable', 'file', 'mimes:pdf,doc,docx,jpg,jpeg,png', 'max:5120'],
            'appointment_letter' => ['nullable', 'file', 'mimes:pdf,doc,docx,jpg,jpeg,png', 'max:5120'],
            'cv' => ['nullable', 'file', 'mimes:pdf,doc,docx,jpg,jpeg,png', 'max:5120'],
            'short_course_certificate' => ['nullable', 'file', 'mimes:pdf,doc,docx,jpg,jpeg,png', 'max:5120'],
            'other_certificate' => ['nullable', 'file', 'mimes:pdf,doc,docx,jpg,jpeg,png', 'max:5120'],
        ];

        $validatedData = $request->validate($rules);

        $fileFields = [
            'license_document',
            'appointment_letter',
            'cv',
            'short_course_certificate',
            'other_certificate'
        ];

        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                $path = $request->file($field)->store('documents/staff', 'public');
                $validatedData[$field] = $path;
            }
        }

        Staff::create($validatedData);

        return redirect()->route('staff.index')->with('success', 'Staff member added successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Staff $staff)
    {
        // You can build a view page for a single staff member if needed.
        return view('staff.show', compact('staff'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Staff  $staff
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(Staff $staff)
    {
        return view('staff.edit', compact('staff'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Staff  $staff
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Staff $staff)
    {
        // 1. Validation Rules
        // Unique rule needs to ignore the current staff member's ID
        $rules = [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'staff_number' => ['required', 'string', 'max:100', Rule::unique('staff')->ignore($staff->id)],
            'email' => ['required', 'email', Rule::unique('staff')->ignore($staff->id)],
            'department' => ['nullable', 'string', 'max:255'],
            'license_number' => ['nullable', 'string', 'max:255'],
            'license_renewal_date' => ['nullable', 'date'],
            'license_document' => ['nullable', 'file', 'mimes:pdf,doc,docx,jpg,jpeg,png', 'max:5120'],
            'appointment_letter' => ['nullable', 'file', 'mimes:pdf,doc,docx,jpg,jpeg,png', 'max:5120'],
            'cv' => ['nullable', 'file', 'mimes:pdf,doc,docx,jpg,jpeg,png', 'max:5120'],
            'short_course_certificate' => ['nullable', 'file', 'mimes:pdf,doc,docx,jpg,jpeg,png', 'max:5120'],
            'other_certificate' => ['nullable', 'file', 'mimes:pdf,doc,docx,jpg,jpeg,png', 'max:5120'],
        ];

        $validatedData = $request->validate($rules);

        // 2. File Uploads
        $fileFields = [
            'license_document',
            'appointment_letter',
            'cv',
            'short_course_certificate',
            'other_certificate'
        ];

        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                // Delete the old file if it exists
                if ($staff->{$field}) {
                    Storage::disk('public')->delete($staff->{$field});
                }
                // Store the new file
                $path = $request->file($field)->store('documents/staff', 'public');
                $validatedData[$field] = $path;
            }
        }

        // 3. Update Staff Record
        $staff->update($validatedData);

        // 4. Redirect with Success Message
        return redirect()->route('staff.index')->with('success', 'Staff member details updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Staff $staff)
    {
        // Before deleting the record, delete associated files
        $fileFields = [
            'license_document',
            'appointment_letter',
            'cv',
            'short_course_certificate',
            'other_certificate'
        ];
        foreach ($fileFields as $field) {
            if ($staff->{$field}) {
                Storage::disk('public')->delete($staff->{$field});
            }
        }

        $staff->delete();

        return redirect()->route('staff.index')->with('success', 'Staff member deleted successfully.');
    }
}
