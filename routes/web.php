<?php

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\TwoFactor;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('dashboard', [App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    // Settings
    Route::redirect('settings', 'settings/profile');
    Route::get('settings/profile', Profile::class)->name('profile.edit');
    Route::get('settings/password', Password::class)->name('user-password.edit');
    Route::get('settings/appearance', Appearance::class)->name('appearance.edit');
    Route::get('settings/two-factor', TwoFactor::class)
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');

    // Plans
    Route::resource('plans', App\Http\Controllers\PlanController::class);
    Route::get('plans/{plan}/roadmap', [App\Http\Controllers\PlanController::class, 'roadmap'])->name('plans.roadmap');
    Route::get('plans/{plan}/versions', [App\Http\Controllers\PlanVersionController::class, 'index'])->name('plans.versions');
    Route::post('plans/{plan}/versions', [App\Http\Controllers\PlanVersionController::class, 'store'])->name('plans.versions.store');
    Route::get('plans/{plan}/versions/{version}', [App\Http\Controllers\PlanVersionController::class, 'show'])->name('plans.versions.show');
    Route::get('plans/{plan}/versions/{version1}/compare/{version2?}', [App\Http\Controllers\PlanVersionController::class, 'compare'])->name('plans.versions.compare');
    Route::post('plans/{plan}/versions/{version}/restore', [App\Http\Controllers\PlanVersionController::class, 'restore'])->name('plans.versions.restore');
    
    // KPIs
    Route::resource('kpis', App\Http\Controllers\KpiController::class);
    Route::get('kpis/{kpi}/history', [App\Http\Controllers\KpiHistoryController::class, 'index'])->name('kpis.history.index');
    Route::post('kpis/{kpi}/history', [App\Http\Controllers\KpiHistoryController::class, 'store'])->name('kpis.history.store');
    Route::delete('kpis/{kpi}/history/{history}', [App\Http\Controllers\KpiHistoryController::class, 'destroy'])->name('kpis.history.destroy');
    
    // Tasks
    Route::get('tasks/kanban', [App\Http\Controllers\TaskController::class, 'kanban'])->name('tasks.kanban');
    Route::resource('tasks', App\Http\Controllers\TaskController::class);
    
    // Risks
    Route::resource('risks', App\Http\Controllers\RiskController::class);
    Route::get('risks/matrix', [App\Http\Controllers\RiskController::class, 'matrix'])->name('risks.matrix');
    Route::get('risks/corporate', [App\Http\Controllers\RiskController::class, 'corporate'])->name('risks.corporate');
    Route::resource('risks.mitigation-actions', App\Http\Controllers\RiskMitigationActionController::class)->except(['index', 'show']);
    
    // Decisions
    Route::resource('decisions', App\Http\Controllers\DecisionController::class);
    
    // Clients
    Route::resource('clients', App\Http\Controllers\ClientController::class);
    
    // Projects
    Route::resource('projects', App\Http\Controllers\ProjectController::class);
    
    // Milestones (nested under plans)
    Route::resource('plans.milestones', App\Http\Controllers\MilestoneController::class)->except(['index']);
    Route::get('plans/{plan}/milestones', [App\Http\Controllers\MilestoneController::class, 'index'])->name('plans.milestones.index');
});
