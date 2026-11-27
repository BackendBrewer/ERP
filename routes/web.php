<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuperAdmin\AcademyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SuperAdmin\InstituteAdminController;
use App\Http\Controllers\Institute\AcademicSetupController;
use App\Http\Controllers\Institute\SubjectController;
use App\Http\Controllers\Institute\SubjectGroupController;
use App\Http\Controllers\Institute\AdmissionInquiryController;
use App\Http\Controllers\Institute\AdmissionController;
use App\Http\Controllers\Institute\StudentController;
use App\Http\Controllers\Institute\EnrollmentLifecycleController;

Route::get('/', function () {
    return view('welcome');
});

Route::view('/super-admin', 'super-admin.dashboard')->middleware(['auth', 'can:access-super-admin'])->name('super-admin.dashboard');

Route::prefix('super-admin')->name('super-admin.')->middleware(['auth', 'can:access-super-admin'])->group(function () {

    Route::view('/', 'super-admin.dashboard')->name('dashboard');

    Route::resource('academies', AcademyController::class)->only(['index', 'create', 'store']);

    Route::get('academies/{academy}/institute-admins/create', [InstituteAdminController::class, 'create'])->name('academies.institute-admins.create');

    Route::post('academies/{academy}/institute-admins', [InstituteAdminController::class, 'store'])->name('academies.institute-admins.store');

});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');

Route::get('/institute/dashboard', [DashboardController::class, 'institute'])->middleware(['auth', 'can:access-institute-admin'])->name('institute.dashboard');

Route::prefix('institute/academic-setup')->name('institute.academic-setup.')->middleware(['auth', 'can:access-institute-admin'])->group(function () {

    Route::get('/', [AcademicSetupController::class, 'index'])->name('index');

    Route::post('/classes', [AcademicSetupController::class, 'storeClass'])->name('classes.store');

    Route::post('/sections', [AcademicSetupController::class, 'storeSection'])->name('sections.store');
});

Route::prefix('institute')->name('institute.')->middleware(['auth', 'can:access-institute-admin'])->group(function () {

    Route::resource('subjects', SubjectController::class)->only(['index', 'store']);
    Route::resource('subject-groups', SubjectGroupController::class)->only(['index', 'store']);
    Route::resource('admission-inquiries', AdmissionInquiryController::class)->only(['index', 'store']);
    Route::get('admissions/create/{inquiry?}', [AdmissionController::class, 'create'])->whereNumber('inquiry')->name('admissions.create');
    Route::post('admissions/{inquiry?}', [AdmissionController::class, 'store'])->whereNumber('inquiry')->name('admissions.store');
    Route::get('students', [StudentController::class, 'index'])->name('students.index');
    Route::get('students/{student}', [StudentController::class, 'show'])->whereNumber('student')->name('students.show');
    Route::get('students/{student}/enrollment/{action}', [EnrollmentLifecycleController::class, 'create'])->whereNumber('student')->whereIn('action', ['transfer', 'withdraw', 'rejoin'])->name('students.enrollment.create');
    Route::post('students/{student}/enrollment/{action}', [EnrollmentLifecycleController::class, 'store'])->whereNumber('student')->whereIn('action', ['transfer', 'withdraw', 'rejoin'])->name('students.enrollment.store');
});

Route::middleware(['auth', 'can:access-institute-admin'])->group(function () {
    Route::get('/subject-groups', fn () => redirect()->route('institute.subject-groups.index'));
    Route::post('/subject-groups', [SubjectGroupController::class, 'store']);
});
