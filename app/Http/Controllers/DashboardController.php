<?php

namespace App\Http\Controllers;

use App\Models\Audit;
use App\Models\Checklist;
use App\Models\ChecklistSubmission;
use App\Models\CqiProject;
use App\Models\Program;
use App\Models\Staff;
use App\Models\Standard;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // --- 1. Personalized Greeting ---
        $hour = date('H');
        $greeting = ($hour < 12) ? "Good morning" : (($hour < 18) ? "Good afternoon" : "Good evening");
        $userName = explode(' ', $user->name)[0]; // Get user's first name

        // --- 2. Checklist & Compliance Data ---
        $checklists = Checklist::all();
        $userSubmissions = ChecklistSubmission::where('user_id', $user->id)->get()->keyBy('checklist_id');
        $totalChecklists = $checklists->count();
        $completedChecklists = $userSubmissions->count();
        $pendingChecklists = $totalChecklists - $completedChecklists;
        $compliancePercentage = ($totalChecklists > 0) ? round(($completedChecklists / $totalChecklists) * 100) : 100;

        // --- 3. Data for Card Widgets ---

        // Row 1: 5 Main Stat Cards
        $topRowCards = [
            [
                'type' => 'stat', 'label' => 'Audits', 'value' => Audit::count(), 'icon' => 'clipboard-check',
                'color' => 'linear-gradient(45deg, #EF4444, #F87171)', 'route' => route('audits.index')
            ],
            [
                'type' => 'stat', 'label' => 'Programs', 'value' => Program::count(), 'icon' => 'graduation-cap',
                'color' => 'linear-gradient(45deg, #10B981, #34D399)', 'route' => route('programs.index')
            ],
            [
                'type' => 'stat', 'label' => 'Standards', 'value' => Standard::count(), 'icon' => 'file-alt',
                'color' => 'linear-gradient(45deg, #6366F1, #818CF8)', 'route' => route('standards.index')
            ],
            [
                'type' => 'stat', 'label' => 'Staff', 'value' => Staff::count(), 'icon' => 'users',
                'color' => 'linear-gradient(45deg, #F59E0B, #FBBE24)', 'route' => route('staff.index')
            ],
            [
                'type' => 'stat', 'label' => 'CQI Projects', 'value' => CqiProject::count(), 'icon' => 'chart-line',
                'color' => 'linear-gradient(45deg, #8B5CF6, #A78BFA)', 'route' => route('cqi_projects.index')
            ],
        ];

        // Row 2: 3 Secondary Info Cards
        $secondRowCards = [
            [
                'type' => 'info', 'label' => 'Your Compliance', 'value' => $compliancePercentage . '%', 'text' => "{$completedChecklists} of {$totalChecklists} checklists completed",
                'icon' => 'tasks', 'color' => 'linear-gradient(45deg, #22C55E, #4ADE80)', 'route' => '#checklists' // Anchor link
            ],
            [
                'type' => 'info_text_only', 'label' => 'Focus: ISO 9001:2015', 'text' => 'Emphasizes customer focus, leadership, and a process-based approach to quality.',
                'icon' => 'award', 'color' => 'linear-gradient(45deg, #06B6D4, #2DD4BF)', 'route' => '#'
            ],
            [
                'type' => 'info_text_only', 'label' => 'QMS Tip of the Day', 'text' => 'Focus on root cause analysis, not just treating the symptoms of a problem.',
                'icon' => 'lightbulb', 'color' => 'linear-gradient(45deg, #3B82F6, #60A5FA)', 'route' => '#'
            ]
        ];


        return view('dashboard', compact(
            'greeting',
            'userName',
            'topRowCards',          // Pass the first row of cards
            'secondRowCards',       // Pass the second row of cards
            'checklists',
            'userSubmissions',
            'completedChecklists',
            'pendingChecklists'
        ));
    }
}