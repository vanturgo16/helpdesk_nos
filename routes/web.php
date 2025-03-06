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
use App\Http\Controllers\MstDropdownController;
use App\Http\Controllers\MstRuleController;
use App\Http\Controllers\MstPrioritiesController;
use App\Http\Controllers\MstCategoryController;
use App\Http\Controllers\CreateTicketController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\MstSubCategoryController;
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
            Route::get('/datas', 'datas')->name('user.datas');
            Route::post('/store', 'store')->name('user.store');
            Route::get('/edit/{id}', 'edit')->name('user.edit');
            Route::post('/update/{id}', 'update')->name('user.update');
            Route::post('/reset/{id}', 'reset')->name('user.reset');
            Route::post('/activate/{id}', 'activate')->name('user.activate');
            Route::post('/deactivate/{id}', 'deactivate')->name('user.deactivate');
            Route::post('/delete/{id}', 'delete')->name('user.delete');
            Route::post('/check_email_employee', 'check_email')->name('user.check_email_employee');
        });
    });
    // MASTER Priority
    Route::controller(MstPrioritiesController::class)->group(function () {
        Route::prefix('priority')->group(function () {
            Route::get('/', 'index')->name('priority.index');
            Route::post('/store', 'store')->name('priority.store');
            Route::post('/update/{id}', 'update')->name('priority.update');
            Route::post('/disable/{id}', 'disable')->name('priority.disable');
            Route::post('/enable/{id}', 'enable')->name('priority.enable');
        });
    });
    // MASTER CATEGORY
    Route::controller(MstCategoryController::class)->group(function () {
        Route::prefix('category')->group(function () {
            Route::get('/', 'index')->name('category.index');
            Route::post('/store', 'store')->name('category.store');
            Route::post('/update/{id}', 'update')->name('category.update');
            Route::post('/disable/{id}', 'disable')->name('category.disable');
            Route::post('/enable/{id}', 'enable')->name('category.enable');
        });
    });
    // MASTER SUB CATEGORY
    Route::controller(MstSubCategoryController::class)->group(function () {
        Route::prefix('subcategory')->group(function () {
            Route::get('/', 'index')->name('subcategory.index');
            Route::post('/store', 'store')->name('subcategory.store');
            Route::post('/update/{id}', 'update')->name('subcategory.update');
            Route::post('/disable/{id}', 'disable')->name('subcategory.disable');
            Route::post('/enable/{id}', 'enable')->name('subcategory.enable');

            // Ajax
            Route::get('/get-subcategory/{id}', 'getSubcategory')->name('subcategory.getSubcategory');
            Route::get('/get-sla/{id}', 'getSLA')->name('subcategory.getSLA');
        });
    });

    
    // CREATE TICKET
    Route::controller(CreateTicketController::class)->group(function () {
        Route::prefix('create-ticket')->group(function () {
            Route::get('/', 'index')->name('createTicket.index');
            Route::post('/store', 'store')->name('createTicket.store');
        });
    });

    // INDEX TICKET
    Route::controller(TicketController::class)->group(function () {
        Route::prefix('ticket')->group(function () {
            Route::get('/', 'index')->name('ticket.index');
            Route::get('/datas', 'datas')->name('ticket.datas');
            Route::post('/store', 'store')->name('ticket.store');
            Route::get('/detail/{id}', 'detail')->name('ticket.detail');
            Route::get('/detail/assign/datas/{id}', 'assignDatas')->name('ticket.assign.datas');
            Route::get('/detail/log/datas/{id}', 'logDatas')->name('ticket.log.datas');
        });
    });


    // AUDIT LOG
    Route::controller(AuditLogController::class)->group(function () {
        Route::prefix('auditlog')->group(function () {
            Route::get('/', 'index')->name('auditlog.index');
        });
    });
});
