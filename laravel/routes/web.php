<?php

use Illuminate\Support\Facades\Route;

// This backend is API-only. All endpoints are under /api.
Route::fallback(fn () => response()->json([
    'success' => false,
    'data'    => null,
    'error'   => 'Not found. Use /api endpoints.',
], 404));
