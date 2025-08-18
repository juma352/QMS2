<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\Staff;

use Illuminate\Support\Str;

class UserManagementController extends Controller
{
    /**
     * Display a listing of all users.
     */
    public function index()
    {
        // Fetch all users from the database, newest first, and paginate them.
        $users = User::latest()->paginate(15);
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        // Get all staff who are not already users
        $staff = \App\Models\Staff::whereNotIn('email', \App\Models\User::pluck('email'))->get();
        return view('users.create', compact('staff'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming request.
        $validated = $request->validate([
            'staff_id' => ['required', 'exists:staff,id'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'string', Rule::in(['admin', 'user'])],
        ]);

        // Find the staff member.
        $staff = \App\Models\Staff::findOrFail($validated['staff_id']);

        // Create the user.
        $user = User::create([
            'name' => $staff->first_name . ' ' . $staff->last_name,
            'email' => $staff->email,
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        return redirect()->route('users.index')
            ->with('success', 'User created successfully for ' . $user->name);
    }

    /**
     * Show the form for editing a specific user's role.
     */
    public function edit(User $user)
    {
        // Define the available roles for the dropdown in the view.
        $roles = ['admin', 'user'];
        return view('users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified user's role in the database.
     */
    public function update(Request $request, User $user)
    {
        // Validate the incoming request.
        $validated = $request->validate([
            'role' => ['required', 'string', Rule::in(['admin', 'user'])],
        ]);

        // Update the user's role with the validated data.
        $user->update($validated);

        // Redirect back to the user list with a success message.
        return redirect()->route('users.index')->with('success', 'User role updated successfully.');
    }

    /**
     * Reset the password for a given user.
     */
    public function resetPassword(User $user)
    {
        // Generate a new random password
        $newPassword = Str::random(10); // Generates a 10-character random string

        // Update the user's password
        $user->password = Hash::make($newPassword);
        $user->save();

        // Send email to the user with the new password
        // Assuming you have a Mailable class for this, e.g., new UserPasswordResetMail($newPassword)
        // For now, we'll use a simple Mail::raw for demonstration or if a dedicated Mailable isn't set up yet.
        Mail::raw("Your password has been reset. Your new password is: {$newPassword}", function ($message) use ($user) {
            $message->to($user->email)
                    ->subject('Your Password Has Been Reset');
        });

        return redirect()->route('users.index')
            ->with('success', 'Password for ' . $user->name . ' has been reset and sent to their email.');
    }
    public function deactivate($id)
{
    $user = User::findOrFail($id);
    $user->is_active = false;
    $user->save();

    return redirect()->back()->with('success', 'User deactivated successfully.');
}

public function activate($id)
{
    $user = User::findOrFail($id);
    $user->is_active = true;
    $user->save();

    return redirect()->back()->with('success', 'User activated successfully.');
}

}
