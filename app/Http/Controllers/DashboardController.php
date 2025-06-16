<?php

namespace App\Http\Controllers;

use App\Models\Audit;
use App\Models\Checklist;
use App\Models\Program; // Assuming you have this model
use App\Models\Standard;
use App\Models\User; // Assuming staff are users
use App\Models\ChecklistSubmission; // <-- Add this
use Illuminate\Support\Facades\Auth; // <-- Add this
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Data for the summary cards (no change here)
        $summaryCards = [
            ['label' => 'Programs', 'count' => Program::count(), 'color' => '#159ed5', 'icon' => 'book', 'route' => '#'],
            ['label' => 'Education Staff', 'count' => User::count(), 'color' => '#00A79D', 'icon' => 'users', 'route' => '#'],
            ['label' => 'Standards', 'count' => Standard::count(), 'color' => '#F4A300', 'icon' => 'check', 'route' => route('standards.index')],
            ['label' => 'Audits', 'count' => Audit::count(), 'color' => '#D9534F', 'icon' => 'file-alt', 'route' => route('audits.index', ['type' => 'External'])],
        ];

        $checklists = Checklist::all();

        // --- NEW LOGIC ---
        // Get all submissions for the current user and key them by the checklist_id
        // for easy lookup in the view.
        $userSubmissions = ChecklistSubmission::where('user_id', Auth::id())
            ->get()
            ->keyBy('checklist_id');

        return view('dashboard', compact('summaryCards', 'checklists', 'userSubmissions'));
    }
}