<?php

namespace App\Http\Controllers; // Correct namespace for controllers

use App\Models\Staff; // Import the Staff model
use Illuminate\Http\Request; // Import the Request class
use Illuminate\Validation\Rule; // Import for conditional unique rules, if needed later
use Illuminate\Support\Facades\Storage; // Recommended for file handling

class StaffController extends Controller // Class declaration, extend base Controller
{
    /**
     * Display a form to create a new staff member.
     */
    
    public function index()
    {
        $staff = Staff::latest()->get();
        return view('staff.index', compact('staff'));
    }

     public function create()
    {
        return view('staff.create');
    }

    /**
     * Store a newly created staff member in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // 1. Validation
        // Use a more explicit array for validation rules for readability
        $rules = [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'staff_number' => ['required', 'string', 'max:100', 'unique:staff,staff_number'], // Explicit column name
            'email' => ['required', 'email', 'unique:staff,email'], // Explicit column name
            'department' => ['nullable', 'string', 'max:255'],
            'license_number' => ['nullable', 'string', 'max:255'],
            'license_renewal_date' => ['nullable', 'date'],
            // File validation rules: max size (e.g., 5MB = 5120KB), allowed mimes
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
                // Use Laravel's Storage facade for better file management (e.g., public disk)
                // This will store files in storage/app/public/documents/staff and create a symlink to public/storage
                $path = $request->file($field)->store('documents/staff', 'public');
                $validatedData[$field] = $path; // Store the relative path in the database
            } else {
                // Ensure field is removed if no file was uploaded, to prevent empty string storage
                unset($validatedData[$field]);
            }
        }

        // 3. Create Staff Record
        Staff::create($validatedData);

        // 4. Redirect with Success Message
        // Using `route('staff.index')` is generally better than `back()` for consistency
        return redirect()->route('staff.index')->with('success', 'Staff member added successfully!');
    }

    // You might also have other methods here, like index, show, edit, update, destroy
    // public function index() { ... }
}