<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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
}
