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

class ReportController extends Controller
{
    /**
     * Display the main reports landing page.
     */
    public function index()
    {
        $reports = [
            // The routes now point to the specific report methods below
            ['name' => 'Audit Report', 'desc' => 'Summary of all internal/external audits with findings.', 'route' => 'reports.audits', 'icon' => 'fas fa-search-dollar'],
            ['name' => 'Standards Report', 'desc' => 'Registry of standards with version history and compliance status.', 'route' => 'reports.standards', 'icon' => 'fas fa-file-contract'],
            ['name' => 'Programs Report', 'desc' => 'Overview of educational programs with accreditation timelines.', 'route' => 'reports.programs', 'icon' => 'fas fa-graduation-cap'],
            ['name' => 'Staff Report', 'desc' => 'Staff directory with licenses, qualifications, and renewal dates.', 'route' => 'reports.staff', 'icon' => 'fas fa-user-tie'],
            ['name' => 'CQI Projects Report', 'desc' => 'Continuous improvement initiatives with progress metrics.', 'route' => 'reports.cqi-projects', 'icon' => 'fas fa-chart-line'],
            ['name' => 'Checklist Submissions', 'desc' => 'Compliance checklist submissions history and status.', 'route' => 'reports.checklist-submissions', 'icon' => 'fas fa-clipboard-list'],
        ];
        return view('reports.index', compact('reports'));
    }

    /**
     * A private helper method to apply date filters to a query.
     */
    private function applyDateFilters(Request $request, $query, $dateColumn = 'created_at')
    {
        if ($request->filled('from') && $request->filled('to')) {
            $from = Carbon::parse($request->from)->startOfDay();
            $to = Carbon::parse($request->to)->endOfDay();
            $query->whereBetween($dateColumn, [$from, $to]);
        }
        return $query;
    }

    /**
     * Generate the report for Audits.
     */
    public function auditReport(Request $request)
    {
        $query = Audit::query()->with('standard');
        $filteredQuery = $this->applyDateFilters($request, $query, 'date_conducted');
        
        $audits = $filteredQuery->orderBy('date_conducted', 'desc')->get();
        
        $internalAudits = $audits->where('audit_type', 'Internal');
        $externalAudits = $audits->where('audit_type', 'External');

        // Points to the specific reports/audits.blade.php view
        return view('reports.audits', compact('internalAudits', 'externalAudits'));
    }
    
    /**
     * Generate the report for Standards.
     */
    public function standardsReport(Request $request)
    {
        $query = Standard::query();
        $filteredQuery = $this->applyDateFilters($request, $query, 'date_of_issue');
        
        // CORRECTED: Sorts by the correct column 'standard_name'
        $standards = $filteredQuery->orderBy('standard_name', 'asc')->get();

        // Points to the specific reports/standards.blade.php view
        return view('reports.standards', compact('standards'));
    }

    /**
     * Generate the report for Programs.
     */
    public function programsReport(Request $request)
    {
        $query = Program::query();
        $filteredQuery = $this->applyDateFilters($request, $query, 'created_at');

        // CORRECTED: Sorts by the correct column 'program_name'
        $programs = $filteredQuery->orderBy('program_name', 'asc')->get();

        // Points to the specific reports/programs.blade.php view
        return view('reports.programs', compact('programs'));
    }

    /**
     * Generate the report for Staff.
     */
    public function staffReport(Request $request)
    {
        $query = Staff::query();
        $filteredQuery = $this->applyDateFilters($request, $query, 'created_at');

        // CORRECTED: Sorts by the correct column 'first_name'
        $staff = $filteredQuery->orderBy('first_name', 'asc')->get();

        // Points to the specific reports/staff.blade.php view
        return view('reports.staff', compact('staff'));
    }

    /**
     * Generate the report for CQI Projects.
     */
    public function cqiProjectsReport(Request $request)
    {
        $query = CqiProject::query();
        $filteredQuery = $this->applyDateFilters($request, $query, 'created_at');

        // CORRECTED: Sorts by the correct column 'project_name'
        $cqiProjects = $filteredQuery->orderBy('project_name', 'asc')->get();

        // Points to the specific reports/cqi-projects.blade.php view
        return view('reports.cqi-projects', compact('cqiProjects'));
    }

    /**
     * Generate the report for Checklist Submissions.
     * CORRECTED: Method name typo is fixed.
     */
    public function checklistSubmissionsReport(Request $request)
    {
        $query = ChecklistSubmission::query()->with(['user', 'checklist']);
        $filteredQuery = $this->applyDateFilters($request, $query, 'created_at');
        
        $submissions = $filteredQuery->latest()->get();

        // Points to the specific reports/checklist-submissions.blade.php view
        return view('reports.checklist-submissions', compact('submissions'));
    }
}
