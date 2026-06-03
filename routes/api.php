<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// API routes are not used in this project — all functionality is server-rendered via Blade.
// Kept for completeness as part of the standard Laravel structure.

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
