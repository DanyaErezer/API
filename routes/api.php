<?php

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SensorController;


Route::get('/', [SensorController::class, 'store']);
Route::get('/data', [SensorController::class, 'getData']);

Route::get('/test', function () {
    return response()->json(['message' => 'API is working']);
});

Route::get('/test-log', function () {
    Log::info('Test log entry');
    return response()->json(['message' => 'Log written']);
});

