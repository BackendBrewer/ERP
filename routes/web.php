<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\AcademyDashboardController;
use App\Http\Controllers\InstituteTeamController;
use App\Http\Controllers\InstituteAuditController;
use App\Http\Controllers\InstitutePermissionController;
use App\Http\Controllers\PlatformDashboardController;
use App\Http\Controllers\InstituteProfileController;
use App\Http\Controllers\InstituteRoleController;
use App\Http\Controllers\InstituteSelectionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::view('/', 'home')->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('/login', [AuthenticatedSessionController::class, 'store'])
        ->middleware('throttle:5,1')
        ->name('login.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/account', function (Request $request) {
        if ($request->user()->is_super_admin) {
            return redirect()->route('platform.dashboard');
        }

        return redirect()->route($request->user()->institutes()->exists() ? 'institutes.select' : 'onboarding.create');
    })->name('account');

    Route::middleware('super-admin')->prefix('platform')->name('platform.')->group(function () {
        Route::get('/dashboard', PlatformDashboardController::class)->name('dashboard');
    });

    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
    
    Route::get('/institutes/select', [InstituteSelectionController::class, 'index'])->name('institutes.select');
        
    Route::post('/institutes/select', [InstituteSelectionController::class, 'store'])->name('institutes.select.store');

    Route::get('/onboarding', [OnboardingController::class, 'create'])
        ->middleware('super-admin')
        ->name('onboarding.create');

    Route::post('/onboarding', [OnboardingController::class, 'store'])
        ->middleware(['super-admin', 'throttle:5,1'])
        ->name('onboarding.store');


    Route::middleware('tenant')->group(function () {

        Route::get('/workspace', AcademyDashboardController::class)
            ->middleware('permission:dashboard.view')
            ->name('workspace');

        Route::get('/institute/profile', [InstituteProfileController::class, 'show'])
            ->middleware('permission:institute.profile.view')
            ->name('institute.profile');

        Route::get('/roles', [InstituteRoleController::class, 'index'])
            ->middleware('permission:roles.view')
            ->name('roles.index');

        Route::get('/role-permissions', [InstitutePermissionController::class, 'index'])
            ->middleware('permission:roles.view')
            ->name('roles.permissions');

        Route::get('/team', [InstituteTeamController::class, 'index'])
            ->middleware('permission:staff.view')
            ->name('team.index');

        Route::post('/team', [InstituteTeamController::class, 'store'])
            ->middleware('permission:staff.create')
            ->name('team.store');

        Route::put('/team/{staffMember}/role', [InstituteTeamController::class, 'updateRole'])
            ->middleware('permission:staff.assign_role')
            ->name('team.role.update');

        Route::get('/audit', [InstituteAuditController::class, 'index'])
            ->middleware('permission:audit.view')
            ->name('audit.index');
    });

});
