<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class HealthCheckController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $dbStatus = false;
        try {
            DB::connection()->getPdo();
            $dbStatus = true;
        } catch (\Throwable $e) {
            $dbStatus = false;
        }

        $cacheStatus = false;
        try {
            Cache::put('health_check', 'ok', 10);
            $cacheStatus = Cache::get('health_check') === 'ok';
        } catch (\Throwable $e) {
            $cacheStatus = false;
        }

        $isHealthy = $dbStatus && $cacheStatus;

        return response()->json([
            'status' => $isHealthy ? 'healthy' : 'unhealthy',
            'timestamp' => now()->toIso8601String(),
            'services' => [
                'database' => $dbStatus ? 'connected' : 'error',
                'cache' => $cacheStatus ? 'operational' : 'error',
            ],
            'environment' => config('app.env'),
        ], $isHealthy ? 200 : 503);
    }
}
