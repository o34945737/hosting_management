<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController as CpanelLogin;
use App\Http\Controllers\RegisterTenantController;

Route::middleware('web')->group(function () {

    Route::get('/login', [CpanelLogin::class, 'show'])
        ->middleware('guest:central')
        ->name('central.login');

    Route::post('/login-store', [CpanelLogin::class, 'store'])
        ->middleware('guest:central')
        ->name('central.login.store');

    Route::post('/central/logout', [CpanelLogin::class, 'destroy'])
        ->middleware('auth:central')
        ->name('central.logout');

    Route::prefix('central')->name('central.')->middleware('auth:central')->group(function () {

        Route::get('/dashboard', fn() => view('page-users.dashboards.index'))
            ->name('dashboard');

        Route::get('multi-tenants/data', [RegisterTenantController::class, 'data'])
            ->name('multi-tenants.data');

        Route::post('multi-tenants/{multi_tenant}/add-admin', [RegisterTenantController::class, 'addAdmin'])
            ->name('multi-tenants.add-admin');

        Route::resource('multi-tenants', RegisterTenantController::class);
    });
});
