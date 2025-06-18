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


// Home route
Route::get('/', fn () => view('auth.login'))->name('home');

// --- AUTHENTICATION ROUTES ---
Route::prefix('auth')->name('auth.')->group(function () {
    Route::get('/login', fn () => view('auth.login'))->name('login');
    Route::post('/login', function (Request $request) {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('dashboard');
        }

        return back()->withErrors(['email' => 'Invalid credentials.'])->withInput();
    })->name('login.post');

    Route::get('/register', fn () => view('auth.register'))->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.post');

    Route::post('/logout', function (Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    })->name('logout');
});

// --- AUTHENTICATED APPLICATION ROUTES ---
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // --- RESOURCEFUL ROUTES ---
    // Using Route::resource for simplicity where applicable
    Route::resource('audits', AuditController::class);
    Route::resource('programs', ProgramController::class);
    Route::resource('standards', StandardController::class);
    
    // Using a fully defined resource group for Staff
    Route::resource('staff', StaffController::class);
    
    // Expanded CQI Project Routes for clarity
    Route::prefix('cqi-projects')->name('cqi_projects.')->group(function () {
        Route::get('/', [CqiProjectController::class, 'index'])->name('index');
        Route::get('/create', [CqiProjectController::class, 'create'])->name('create');
        Route::post('/', [CqiProjectController::class, 'store'])->name('store');
        Route::get('/{cqi_project}', [CqiProjectController::class, 'show'])->name('show');
        Route::get('/{cqi_project}/edit', [CqiProjectController::class, 'edit'])->name('edit');
        Route::put('/{cqi_project}', [CqiProjectController::class, 'update'])->name('update');
        Route::delete('/{cqi_project}', [CqiProjectController::class, 'destroy'])->name('destroy');
    });


    // --- CUSTOM ROUTES ---

    // Checklist Routes
    Route::prefix('checklists')->name('checklists.')->group(function () {
        Route::get('/{checklist:slug}', [ChecklistController::class, 'show'])->name('show');
        Route::post('/{checklist}', [ChecklistController::class, 'store'])->name('store');
        Route::get('/results', [ChecklistController::class, 'resultsIndex'])->name('results.index');
        Route::get('/results/{submission}', [ChecklistController::class, 'resultsShow'])->name('results.show');
    });

    // Report Routes
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/audits', [ReportController::class, 'auditReport'])->name('audits');
        Route::get('/standards', [ReportController::class, 'standardsReport'])->name('standards');
        Route::get('/programs', [ReportController::class, 'programsReport'])->name('programs');
        Route::get('/staff', [ReportController::class, 'staffReport'])->name('staff');
        Route::get('/cqi-projects', [ReportController::class, 'cqiProjectsReport'])->name('cqi-projects');
        Route::get('/checklist-submissions', [ReportController::class, 'checklistSubmissionReport'])->name('checklist-submissions');
        Route::get('/{reportType}', [ReportController::class, 'generateReport'])->name('generate');
    });
});
