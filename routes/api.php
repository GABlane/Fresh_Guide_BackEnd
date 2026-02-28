<?php

use App\Http\Controllers\Api\SyncController;
use Illuminate\Support\Facades\Route;

// ─── Public Sync API ─────────────────────────────────────────────────────────
// Consumed by the Android app for offline-first data sync.
// No authentication required.

Route::prefix('sync')->group(function () {
    Route::get('version',   [SyncController::class, 'version']);
    Route::get('bootstrap', [SyncController::class, 'bootstrap']);
});
