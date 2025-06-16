<?php

namespace App\Http\Controllers;

use App\Models\Audit;
use App\Models\CqiProject;
use App\Models\Program;
use App\Models\Staff;
use App\Models\Standard;
use App\Models\ChecklistSubmission;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ReportController extends Controller
{
    public function index()
    {
        $reports = [
            ['name' => 'Audit Report', 'desc' => 'Summary of all internal/external audits with findings and resolutions.', 'route' => 'reports.audits', 'icon' => 'fas fa-search-dollar'],
            ['name' => 'Standards Report', 'desc' => 'Registry of standards with version history and compliance status.', 'route' => 'reports.standards', 'icon' => 'fas fa-file-contract'],
            ['name' => 'Programs Report', 'desc' => 'Overview of educational programs with accreditation timelines.', 'route' => 'reports.programs', 'icon' => 'fas fa-graduation-cap'],
            ['name' => 'Staff Report', 'desc' => 'Staff directory with licenses, qualifications, and renewal dates.', 'route' => 'reports.staff', 'icon' => 'fas fa-user-tie'],
            ['name' => 'CQI Projects Report', 'desc' => 'Continuous improvement initiatives with progress metrics.', 'route' => 'reports.cqi-projects', 'icon' => 'fas fa-chart-line'],
            ['name' => 'Checklist Submissions', 'desc' => 'Compliance checklist submissions history and status.', 'route' => 'reports.checklist-submissions', 'icon' => 'fas fa-clipboard-list'],
        ];
        return view('reports.index', compact('reports'));
    }

    private function applyDateFilters(Request $request, $query, $dateColumn = 'created_at')
    {
        if ($request->filled('from') && $request->filled('to')) {
            $from = Carbon::parse($request->from)->startOfDay();
            $to = Carbon::parse($request->to)->endOfDay();
            Log::info('Applying date filters', [
                'from' => $from->toDateTimeString(),
                'to' => $to->toDateTimeString(),
                'column' => $dateColumn,
            ]);
            $query->whereBetween($dateColumn, [$from, $to]);
        } else {
            Log::warning('Date filters not applied', [
                'from' => $request->from,
                'to' => $request->to,
            ]);
        }
        return $query;
    }

    public function auditReport(Request $request)
    {
        Log::info('Audit report requested', $request->all());
        $query = Audit::query()->with('standard');
        $filteredQuery = $this->applyDateFilters($request, $query, 'date_conducted');
        
        $audits = $filteredQuery->get();
        Log::info('Audits retrieved', ['count' => $audits->count()]);
        
        $auditsByType = $audits->groupBy('audit_type');
        $internalAudits = $auditsByType->get('Internal', collect());
        $externalAudits = $auditsByType->get('External', collect());

        return view('reports.audits', compact('internalAudits', 'externalAudits'));
    }

    public function checklistSubmissionsReport(Request $request)
    {
        $query = ChecklistSubmission::query()->with(['user', 'checklist']);
        $filteredQuery = $this->applyDateFilters($request, $query, 'created_at');
        
        $submissions = $filteredQuery->latest()->get();
        return view('reports.checklist-submissions', compact('submissions'));
    }

    public function standardsReport(Request $request)
    {
        $query = Standard::query();
        $filteredQuery = $this->applyDateFilters($request, $query, 'date_of_issue');
        $standards = $filteredQuery->orderBy('standard_name')->get();

        return view('reports.standards', compact('standards'));
    }

    public function programsReport(Request $request)
    {
        $query = Program::query();
        $filteredQuery = $this->applyDateFilters($request, $query, 'created_at');
        $programs = $filteredQuery->orderBy('name')->get();

        return view('reports.programs', compact('programs'));
    }

    public function staffReport(Request $request)
    {
        $query = Staff::query();
        $filteredQuery = $this->applyDateFilters($request, $query, 'created_at');
        $staff = $filteredQuery->orderBy('name')->get();

        return view('reports.staff', compact('staff'));
    }

    public function cqiProjectsReport(Request $request)
    {
        $query = CqiProject::query();
        $filteredQuery = $this->applyDateFilters($request, $query, 'created_at');
        $cqiProjects = $filteredQuery->orderBy('title')->get();

        return view('reports.cqi-projects', compact('cqiProjects'));
    }
}