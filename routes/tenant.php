<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use App\Http\Controllers\Auth\TenantLoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\StudioController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\AttendancesController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;

Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->name('tenant.')->group(function () {

    Route::middleware('guest:web')->group(function () {
        Route::get('/login', [TenantLoginController::class, 'show'])
            ->name('login');

        Route::post('/login', [TenantLoginController::class, 'store'])
            ->name('login.store');
    });

    Route::post('/logout', [TenantLoginController::class, 'destroy'])
        ->middleware('auth:web')
        ->name('logout');

    Route::middleware('auth:web')->group(function () {

        Route::post('/logout', [TenantLoginController::class, 'destroy'])
            ->name('logout');

        Route::get('/', fn() => view('page-users.dashboards.index'))
            ->name('dashboard');

        Route::get('roles/data', [RolesController::class, 'data'])->name('roles.data');
        Route::resource('roles', RolesController::class);

        Route::get('users/data', [UserController::class, 'data'])->name('users.data');
        Route::get('users/roles-options', [UserController::class, 'rolesOptions'])
            ->name('users.roles-options');
        Route::resource('users', UserController::class);

        Route::get('studios/data', [StudioController::class, 'data'])->name('studios.data');
        Route::resource('studios', StudioController::class);

        Route::get('brands/data', [BrandController::class, 'data'])->name('brands.data');
        Route::resource('brands', BrandController::class);

        Route::get('schedules/data', [ScheduleController::class, 'data'])->name('schedules.data');
        Route::get('schedules/hosts-options', [ScheduleController::class, 'hostsOptions'])
            ->name('schedules.hosts-options');
        Route::get('schedules/brands-options', [ScheduleController::class, 'brandsOptions'])
            ->name('schedules.brands-options');
        Route::get('schedules/studios-options', [ScheduleController::class, 'studiosOptions'])
            ->name('schedules.studios-options');
        Route::post('schedules/import/preview', [ScheduleController::class, 'preview'])->name('schedules.import.preview');
        Route::post('schedules/import/commit',  [ScheduleController::class, 'commit'])->name('schedules.import.commit');
        Route::get('schedules/import/last-preview', [ScheduleController::class, 'lastPreview'])->name('schedules.import.last-preview'); // optional
        Route::resource('schedules', ScheduleController::class);


        Route::get('attendances/data', [AttendancesController::class, 'data'])->name('attendances.data');
        Route::resource('attendances', AttendancesController::class)->only(['index', 'show']);

        Route::get('reports', [ReportController::class, 'index'])->name('reports.index');

        Route::get('settings/attendance', [SettingController::class, 'attendance'])->name('settings.attendance');
        Route::post('settings/attendance', [SettingController::class, 'updateAttendance'])->name('settings.attendance.update');
    });
});
