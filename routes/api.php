<?php

use App\Http\Controllers\Api\ReportApiController;
use App\Http\Controllers\Api\RfidScanController;
use Illuminate\Support\Facades\Route;

Route::middleware(['device.token', 'throttle:rfid'])->group(function () {
    Route::post('/rfid/scan', [RfidScanController::class, 'store']);
});

// Endpoint Integrasi API Pihak ke-3 (gunakan X-API-Key, bukan X-Device-Token)
Route::prefix('v1')->middleware(['api.integration', 'throttle:api'])->group(function () {
    Route::get('/attendances/rekap', [ReportApiController::class, 'rekap']);
    Route::get('/students/{student}/history', [ReportApiController::class, 'studentHistory']);
});

// Endpoint Health Check Monitoring
Route::get('/health', \App\Http\Controllers\Api\HealthCheckController::class);
