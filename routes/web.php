<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController as CpanelLogin;
use App\Http\Controllers\RegisterTenantController;


Route::prefix('central')->name('central.')->group(function () {

    Route::get('/login', [CpanelLogin::class, 'show'])
        ->middleware('guest:central')
        ->name('login');

    Route::post('/login', [CpanelLogin::class, 'store'])
        ->middleware('guest:central')
        ->name('process-login');

    Route::post('/logout', [CpanelLogin::class, 'destroy'])
        ->middleware('auth:central')
        ->name('logout');

    Route::middleware('auth:central')->group(function () {

        /* Dashboard */
        Route::get('/dashboard', fn() => view('page-users.dashboards.index'))
            ->name('dashboard');

        Route::get('multi-tenants/data', [RegisterTenantController::class, 'data'])
            ->name('multi-tenants.data');
        Route::post('multi-tenants/{multi_tenant}/add-admin', [RegisterTenantController::class, 'addAdmin'])
            ->name('multi-tenants.add-admin');
        Route::resource('multi-tenants', RegisterTenantController::class);
    });
});
