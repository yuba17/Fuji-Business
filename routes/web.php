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
    
    // KPIs
    Route::resource('kpis', App\Http\Controllers\KpiController::class);
    
    // Tasks
    Route::resource('tasks', App\Http\Controllers\TaskController::class);
    
    // Risks
    Route::resource('risks', App\Http\Controllers\RiskController::class);
    
    // Decisions
    Route::resource('decisions', App\Http\Controllers\DecisionController::class);
    
    // Clients
    Route::resource('clients', App\Http\Controllers\ClientController::class);
    
    // Projects
    Route::resource('projects', App\Http\Controllers\ProjectController::class);
});
