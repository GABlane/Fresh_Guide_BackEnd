<?php

use App\Http\Controllers\Api\Auth\AdminAuthController;
use App\Http\Controllers\Api\Auth\StudentAuthController;
use App\Http\Controllers\Api\Admin\BuildingController as AdminBuildingController;
use App\Http\Controllers\Api\Admin\FacilityController as AdminFacilityController;
use App\Http\Controllers\Api\Admin\FloorController as AdminFloorController;
use App\Http\Controllers\Api\Admin\OriginController as AdminOriginController;
use App\Http\Controllers\Api\Admin\PublishController;
use App\Http\Controllers\Api\Admin\RoomImageController as AdminRoomImageController;
use App\Http\Controllers\Api\Admin\RoomController as AdminRoomController;
use App\Http\Controllers\Api\Admin\RouteController as AdminRouteController;
use App\Http\Controllers\Api\SyncController;
use App\Http\Controllers\Api\User\OriginController as UserOriginController;
use App\Http\Controllers\Api\User\RoomController as UserRoomController;
use App\Http\Controllers\Api\User\ScheduleController as UserScheduleController;
use App\Http\Controllers\Api\User\RouteController as UserRouteController;
use Illuminate\Support\Facades\Route;

// ─── Public ──────────────────────────────────────────────────────────────────
Route::post('register',      [StudentAuthController::class, 'register']);
Route::post('admin/login',   [AdminAuthController::class,   'login']);

// ─── Public Sync (offline-first data for Android) ────────────────────────────
Route::prefix('sync')->group(function () {
    Route::get('version',   [SyncController::class, 'version']);
    Route::get('bootstrap', [SyncController::class, 'bootstrap']);
});

// ─── Authenticated (any valid token) ─────────────────────────────────────────
Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [StudentAuthController::class, 'logout']);
    Route::get('me',      [StudentAuthController::class, 'me']);

    // User read-only endpoints
    Route::get('rooms',                   [UserRoomController::class,   'index']);
    Route::get('rooms/{room}',            [UserRoomController::class,   'show']);
    Route::get('origins',                 [UserOriginController::class, 'index']);
    Route::get('routes/{roomId}',         [UserRouteController::class,  'show']);
    Route::get('schedules',               [UserScheduleController::class, 'index']);
    Route::post('schedules',              [UserScheduleController::class, 'store']);
    Route::put('schedules/{schedule}',    [UserScheduleController::class, 'update']);
    Route::delete('schedules/{schedule}', [UserScheduleController::class, 'destroy']);
});

// ─── Admin (token + admin role) ───────────────────────────────────────────────
Route::middleware(['auth:sanctum', 'admin.api'])->prefix('admin')->group(function () {
    Route::post('logout', [AdminAuthController::class, 'logout']);

    Route::apiResource('buildings', AdminBuildingController::class);
    Route::apiResource('floors',    AdminFloorController::class);
    Route::apiResource('rooms',     AdminRoomController::class);
    Route::post('rooms/{room}/image', [AdminRoomImageController::class, 'upload']);
    Route::delete('rooms/{room}/image', [AdminRoomImageController::class, 'destroy']);
    Route::apiResource('facilities', AdminFacilityController::class);
    Route::apiResource('origins',   AdminOriginController::class);
    Route::apiResource('routes',    AdminRouteController::class);

    Route::post('publish', [PublishController::class, 'publish']);
});
