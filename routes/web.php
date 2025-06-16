<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\StandardController;
use App\Http\Controllers\CqiProjectController;
use App\Http\Controllers\AuditController;
use App\Http\Controllers\ChecklistController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ResultController; // <-- ADD THIS LINE

Route::get('/', function () {
    return view('auth.login');
});

// POST login route
Route::post('/login', function (Request $request) {
    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect('/dashboard');
    }

    return back()->with('error', 'Invalid credentials.');
})->name('login');

// Show register page
Route::get('/register', function () {
    return view('auth.register');
})->name('register');

// Handle register form submission
Route::post('/register', [RegisterController::class, 'store'])->name('register');

// Logout route
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->name('logout');

// Dashboard route
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth'])->name('dashboard');

// Audit routes
Route::resource('audits', AuditController::class)->middleware('auth');

// Program resource routes
Route::resource('programs', ProgramController::class)->middleware('auth');

// Staff routes
Route::middleware('auth')->group(function () {
    Route::get('/staff/create', [StaffController::class, 'create'])->name('staff.create');
    Route::post('/staff', [StaffController::class, 'store'])->name('staff.store');
    Route::get('/staff', [StaffController::class, 'index'])->name('staff.index');
});

// Standard resource routes
Route::resource('standards', StandardController::class)->middleware('auth');

// CQI Project individual routes
Route::middleware('auth')->group(function () {
    Route::get('/cqi-projects', [CqiProjectController::class, 'index'])->name('cqi-projects.index');
    Route::get('/cqi-projects/create', [CqiProjectController::class, 'create'])->name('cqi-projects.create');
    Route::post('/cqi-projects', [CqiProjectController::class, 'store'])->name('cqi-projects.store');
    Route::get('/cqi-projects/{cqiProject}', [CqiProjectController::class, 'show'])->name('cqi-projects.show');
    Route::get('/cqi-projects/{cqiProject}/edit', [CqiProjectController::class, 'edit'])->name('cqi-projects.edit');
    Route::put('/cqi-projects/{cqiProject}', [CqiProjectController::class, 'update'])->name('cqi-projects.update');
    Route::delete('/cqi-projects/{cqiProject}', [CqiProjectController::class, 'destroy'])->name('cqi-projects.destroy');
});


Route::middleware(['auth'])->group(function () {
    // Routes for filling out a checklist
    Route::get('/checklists/{checklist:slug}', [ChecklistController::class, 'show'])->name('checklists.show');
    Route::post('/checklists/{checklist}', [ChecklistController::class, 'store'])->name('checklists.store');

    // Routes for viewing checklist results, all handled by ChecklistController
    Route::get('/checklists/results', [ChecklistController::class, 'resultsIndex'])->name('checklists.results.index');
    Route::get('/checklists/results/{submission}', [ChecklistController::class, 'resultsShow'])->name('checklists.results.show');
});