<?php

use Illuminate\Support\Facades\Route;

// MIDDLEWARE
use App\Http\Middleware\Authenticate;
use App\Http\Middleware\NoCache;
use App\Http\Middleware\UpdateLastSeen;
// CONTROLLER
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InstitutionController;
use App\Http\Controllers\MstDepartmentController;
use App\Http\Controllers\MstDropdownController;
use App\Http\Controllers\MstPositionController;
use App\Http\Controllers\MstRuleController;
use App\Http\Controllers\MstStaffController;
use App\Http\Controllers\MstUserController;


// LOGIN
Route::get('/', [AuthController::class, 'login'])->name('login');
Route::post('auth/login', [AuthController::class, 'postlogin'])->name('postlogin')->middleware("throttle:5,2");
// LOGOUT
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/expired-logout', [AuthController::class, 'expiredlogout'])->name('expiredlogout');

// LOGGED IN
Route::middleware([Authenticate::class, NoCache::class, UpdateLastSeen::class])->group(function () {
    // DASHBOARD
    Route::controller(DashboardController::class)->group(function () {
        Route::prefix('dashboard')->group(function () {
            Route::get('/', 'index')->name('dashboard');
            Route::post('/', 'switchTheme')->name('switchTheme');
        });
    });
    // RULE CONFIGURATION
    Route::controller(MstRuleController::class)->group(function () {
        Route::prefix('rule')->group(function () {
            Route::get('/', 'index')->name('rule.index');
            Route::post('/store', 'store')->name('rule.store');
            Route::post('/update/{id}', 'update')->name('rule.update');
            Route::post('/delete/{id}', 'delete')->name('rule.delete');
        });
    });
    // DROPDOWN CONFIGURATION
    Route::controller(MstDropdownController::class)->group(function () {
        Route::prefix('dropdown')->group(function () {
            Route::get('/', 'index')->name('dropdown.index');
            Route::post('/store', 'store')->name('dropdown.store');
            Route::post('/update/{id}', 'update')->name('dropdown.update');
            Route::post('/disable/{id}', 'disable')->name('dropdown.disable');
            Route::post('/enable/{id}', 'enable')->name('dropdown.enable');
        });
    });
    // USER CONFIGURATION
    Route::controller(MstUserController::class)->group(function () {
        Route::prefix('user')->group(function () {
            Route::get('/', 'index')->name('user.index');
            Route::post('/store', 'store')->name('user.store');
        });
    });

    // INSTITUTION MANAGEMENT
    Route::controller(InstitutionController::class)->group(function () {
        Route::prefix('institution')->group(function () {
            Route::get('/', 'index')->name('institution.index');
        });
    });
    // DEPARTMENT MANAGEMENT
    Route::controller(MstDepartmentController::class)->group(function () {
        Route::prefix('department')->group(function () {
            Route::get('/', 'index')->name('department.index');
            Route::post('/store', 'store')->name('department.store');
            Route::post('/update/{id}', 'update')->name('department.update');
            Route::post('/disable/{id}', 'disable')->name('department.disable');
            Route::post('/enable/{id}', 'enable')->name('department.enable');
        });
    });
    // POSITION MANAGEMENT
    Route::controller(MstPositionController::class)->group(function () {
        Route::prefix('position')->group(function () {
            Route::get('/', 'index')->name('position.index');
            Route::post('/store', 'store')->name('position.store');
            Route::post('/update/{id}', 'update')->name('position.update');
            Route::post('/disable/{id}', 'disable')->name('position.disable');
            Route::post('/enable/{id}', 'enable')->name('position.enable');
        });
    });
    // STAFF MANAGEMENT
    Route::controller(MstStaffController::class)->group(function () {
        Route::prefix('staff')->group(function () {
            Route::get('/', 'index')->name('staff.index');
            Route::post('/store', 'store')->name('staff.store');
        });
    });


    // AUDIT LOG
    Route::controller(AuditLogController::class)->group(function () {
        Route::prefix('auditlog')->group(function () {
            Route::get('/', 'index')->name('auditlog.index');
        });
    });
});
