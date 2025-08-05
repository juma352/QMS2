<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DynamicChecklistController;

/*
|--------------------------------------------------------------------------
| Dynamic Checklist Routes
|--------------------------------------------------------------------------
|
| These routes handle the dynamic checklist generation functionality
| for internal audits, allowing users to create their own questions
| and linking checklists to audits.
|
*/

Route::middleware('auth')->group(function () {
    // Dynamic checklist routes for audits
    Route::get('audits/{audit}/checklist/create', [DynamicChecklistController::class, 'create'])
        ->name('audits.checklist.create');
    
    Route::post('audits/{audit}/checklist', [DynamicChecklistController::class, 'store'])
        ->name('audits.checklist.store');
    
    Route::get('audits/{audit}/checklist/edit', [DynamicChecklistController::class, 'edit'])
        ->name('audits.checklist.edit');
    
    Route::put('audits/{audit}/checklist', [DynamicChecklistController::class, 'update'])
        ->name('audits.checklist.update');
});
