<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuditController;
use App\Http\Controllers\ChecklistController;
use App\Http\Controllers\CqiProjectController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\StandardController;
use App\Http\Controllers\UserManagementController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you define all of the routes for your application.
| It's loaded by the RouteServiceProvider and all routes are automatically
| assigned to the "web" middleware group.
|
*/

// --- PUBLIC AND AUTHENTICATION ROUTES ---
// These routes are accessible to everyone, including guests (users who are not logged in).

// The root URL of the site will redirect to the login page.
Route::get('/', fn () => redirect()->route('login'));

// Define the routes for displaying the login and registration forms.
// Naming the login route 'login' is crucial, as this is the default name
// Laravel's authentication middleware looks for when it needs to redirect an unauthenticated user.
Route::get('login', fn () => view('auth.login'))->name('login');
Route::get('register', fn () => view('auth.register'))->name('register');

// Define the routes that handle the form submissions for login and registration.
// These use the POST method.
Route::post('login', function (Request $request) {
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        // After a successful login, redirect the user to their intended destination,
        // or to the dashboard as a fallback.
        return redirect()->intended('dashboard');
    }

    // If login fails, redirect back to the previous page with an error message.
    return back()->withErrors(['email' => 'Invalid credentials.'])->withInput();
})->name('login.post');

Route::post('register', [RegisterController::class, 'store'])->name('register.post');


// --- AUTHENTICATED APPLICATION ROUTES ---
// All routes within this group are protected by the 'auth' middleware.
// This means a user MUST be logged in to access any of these routes.
Route::middleware('auth')->group(function () {

    // The main application dashboard.
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // The logout route is placed inside the 'auth' group because only a logged-in user can log out.
    // It is named 'logout' and does not have any prefixes.
    Route::post('logout', function (Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    })->name('logout');

    // --- RESOURCEFUL ROUTES ---
    // Route::resource() is a convenient way to create all the common routes for a resource
    // (index, create, store, show, edit, update, destroy).
    Route::resource('audits', AuditController::class);
    Route::resource('programs', ProgramController::class);
    Route::resource('standards', StandardController::class);
    Route::resource('staff', StaffController::class);

    // Expanded CQI Project Routes for clarity.
    // This group defines all routes under the '/cqi-projects' URL.
    // The name('cqi_projects.') prefix ensures all route names start with 'cqi_projects.'.
    Route::prefix('cqi-projects')->name('cqi_projects.')->group(function () {
        Route::get('/', [CqiProjectController::class, 'index'])->name('index'); // Name: cqi_projects.index
        Route::get('/create', [CqiProjectController::class, 'create'])->name('create'); // Name: cqi_projects.create
        Route::post('/', [CqiProjectController::class, 'store'])->name('store'); // Name: cqi_projects.store
        Route::get('/{cqi_project}', [CqiProjectController::class, 'show'])->name('show'); // Name: cqi_projects.show
        Route::get('/{cqi_project}/edit', [CqiProjectController::class, 'edit'])->name('edit'); // Name: cqi_projects.edit
        Route::put('/{cqi_project}', [CqiProjectController::class, 'update'])->name('update'); // Name: cqi_projects.update
        Route::delete('/{cqi_project}', [CqiProjectController::class, 'destroy'])->name('destroy'); // Name: cqi_projects.destroy
    });

    // --- CUSTOM ROUTES ---
    // These are routes that don't fit the standard resource controller pattern.

    // Checklist Routes
    Route::prefix('checklists')->name('checklists.')->group(function () {
        // Main index route for all checklists
        Route::get('/checklists', [ChecklistController::class, 'checklistsIndex'])->name('index');
        
        // Show individual checklist
        Route::get('/checklists/{checklist}', [ChecklistController::class, 'show'])->name('show');
        Route::post('/checklists/{checklist}', [ChecklistController::class, 'store'])->name('store');
        
        // Results routes
        Route::get('/results', [ChecklistController::class, 'resultsIndex'])->name('results.index');
        Route::get('/results/{submission}', [ChecklistController::class, 'resultsShow'])->name('results.show');
        
        // Department routes
        Route::get('/departments', [ChecklistController::class, 'departmentIndex'])->name('departments');
        Route::get('/departments/{department}', [ChecklistController::class, 'checklistsByDepartment'])->name('byDepartment');
    });

    // Report Routes
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/audits', [ReportController::class, 'auditReport'])->name('audits');
        Route::get('/standards', [ReportController::class, 'standardsReport'])->name('standards');
        Route::get('/programs', [ReportController::class, 'programsReport'])->name('programs');
        Route::get('/staff', [ReportController::class, 'staffReport'])->name('staff');
        Route::get('/cqi-projects', [ReportController::class, 'cqiProjectsReport'])->name('cqi-projects');
        Route::get('/checklist-submissions', [ReportController::class, 'checklistSubmissionsReport'])->name('checklist-submissions');
        Route::get('/{reportType}', [ReportController::class, 'generateReport'])->name('generate');
    });

    // --- USER MANAGEMENT (ADMINS ONLY) ---
    // This group is protected by the 'role:admin' middleware.
    // Make sure it points to the correct controller class.
    Route::middleware('role:admin')->group(function () {
        Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
        Route::get('/users/{user}/edit', [UserManagementController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserManagementController::class, 'update'])->name('users.update');
    });
});
