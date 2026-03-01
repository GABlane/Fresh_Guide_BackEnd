<?php

use App\Http\Controllers\Admin\BuildingController;
use App\Http\Controllers\Admin\FacilityController;
use App\Http\Controllers\Admin\FloorController;
use App\Http\Controllers\Admin\OriginController;
use App\Http\Controllers\Admin\PublishController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\RouteController;
use App\Http\Controllers\Auth\AdminLoginController;
use Illuminate\Support\Facades\Route;

// ─── Auth ────────────────────────────────────────────────────────────────────

Route::get('/admin/login', [AdminLoginController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminLoginController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AdminLoginController::class, 'logout'])
    ->name('admin.logout')
    ->middleware('auth');

// ─── Protected Admin ─────────────────────────────────────────────────────────

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/', fn () => redirect()->route('admin.dashboard'));
        Route::get('/dashboard', fn () => view('admin.dashboard'))->name('dashboard');

        Route::resource('buildings', BuildingController::class)->except(['show']);
        Route::resource('floors',    FloorController::class)->except(['show']);
        Route::resource('rooms',     RoomController::class)->except(['show']);
        Route::resource('facilities', FacilityController::class)->except(['show']);
        Route::resource('origins',   OriginController::class)->except(['show']);
        Route::resource('routes',    RouteController::class)->except(['show']);

        Route::post('publish', [PublishController::class, 'publish'])->name('publish');
    });

// ─── Redirect root ───────────────────────────────────────────────────────────

Route::get('/', fn () => redirect('/admin'));
