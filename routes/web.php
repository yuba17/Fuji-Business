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

// Selector de modo de vista (individual / equipo)
Route::middleware(['auth'])->group(function () {
    Route::get('view-mode/{mode}', [App\Http\Controllers\ViewModeController::class, 'set'])
        ->whereIn('mode', ['individual', 'team'])
        ->name('view-mode.set');
});

// Dashboard Customization
Route::get('dashboards', [App\Http\Controllers\DashboardCustomizationController::class, 'index'])->name('dashboards.index');
Route::get('dashboards/customize/{dashboard?}', [App\Http\Controllers\DashboardCustomizationController::class, 'customize'])->name('dashboards.customize');
Route::post('dashboards', [App\Http\Controllers\DashboardCustomizationController::class, 'store'])->name('dashboards.store');
Route::delete('dashboards/{dashboard}', [App\Http\Controllers\DashboardCustomizationController::class, 'destroy'])->name('dashboards.destroy');

Route::middleware(['auth'])->group(function () {
    // Settings
    Route::redirect('settings', 'settings/profile');
    Route::get('settings/profile', Profile::class)->name('profile.edit');
    
    // Perfil Expandido
    Route::get('profile', [App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
    Route::get('profile/{user}', [App\Http\Controllers\ProfileController::class, 'showUser'])->name('profile.show.user');
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
    Route::post('tasks/{task}/attachments', [App\Http\Controllers\TaskController::class, 'uploadAttachment'])->name('tasks.attachments.store');
    Route::delete('tasks/{task}/attachments/{attachment}', [App\Http\Controllers\TaskController::class, 'deleteAttachment'])->name('tasks.attachments.destroy');
    Route::get('tasks/{task}/attachments/{attachment}/download', [App\Http\Controllers\TaskController::class, 'downloadAttachment'])->name('tasks.attachments.download');
    Route::post('tasks/{task}/comments', [App\Http\Controllers\TaskController::class, 'addComment'])->name('tasks.comments.store');
    Route::delete('tasks/{task}/comments/{comment}', [App\Http\Controllers\TaskController::class, 'deleteComment'])->name('tasks.comments.destroy');
    
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
    
    // Presentations
    Route::get('plans/{plan}/presentation', [App\Http\Controllers\PresentationController::class, 'show'])->name('plans.presentation');
    Route::get('plans/{plan}/presentation/pdf', [App\Http\Controllers\PresentationController::class, 'exportPdf'])->name('plans.presentation.pdf');
    Route::get('plans/{plan}/presentation/ppt', [App\Http\Controllers\PresentationController::class, 'exportPpt'])->name('plans.presentation.ppt');
    
    // Tags
    Route::get('tags', [App\Http\Controllers\TagController::class, 'index'])->name('tags.index');
    Route::get('tags/search', [App\Http\Controllers\TagController::class, 'search'])->name('tags.search');
    Route::get('tags/popular', [App\Http\Controllers\TagController::class, 'popular'])->name('tags.popular');
    Route::post('tags', [App\Http\Controllers\TagController::class, 'store'])->name('tags.store');
    Route::put('tags/{tag}', [App\Http\Controllers\TagController::class, 'update'])->name('tags.update');
    Route::delete('tags/{tag}', [App\Http\Controllers\TagController::class, 'destroy'])->name('tags.destroy');
    
    // Search
    Route::get('search', [App\Http\Controllers\SearchController::class, 'index'])->name('search.index');
    Route::get('search/tags', [App\Http\Controllers\SearchController::class, 'byTags'])->name('search.by-tags');
    
    // Scenarios
    Route::get('plans/{plan}/scenarios', [App\Http\Controllers\ScenarioController::class, 'index'])->name('scenarios.index');
    Route::get('plans/{plan}/scenarios/create', [App\Http\Controllers\ScenarioController::class, 'create'])->name('scenarios.create');
    Route::post('plans/{plan}/scenarios', [App\Http\Controllers\ScenarioController::class, 'store'])->name('scenarios.store');
    Route::get('plans/{plan}/scenarios/{scenario}', [App\Http\Controllers\ScenarioController::class, 'show'])->name('scenarios.show');
    Route::get('plans/{plan}/scenarios/compare', [App\Http\Controllers\ScenarioController::class, 'compare'])->name('scenarios.compare');
    Route::post('plans/{plan}/scenarios/{scenario}/apply', [App\Http\Controllers\ScenarioController::class, 'apply'])->name('scenarios.apply');
    Route::delete('plans/{plan}/scenarios/{scenario}', [App\Http\Controllers\ScenarioController::class, 'destroy'])->name('scenarios.destroy');

    // Equipos
    Route::get('teams/my', [App\Http\Controllers\TeamController::class, 'myTeam'])->name('teams.my');
    Route::get('teams/calendar', [App\Http\Controllers\TeamController::class, 'calendar'])->name('teams.calendar');
    Route::get('teams', [App\Http\Controllers\TeamController::class, 'index'])->name('teams.index');
});
