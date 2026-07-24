<?php

namespace App\Http\Middleware;

use App\Models\ApiIntegration;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiIntegrationTokenMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $key = $request->header('X-API-Key');

        $integration = ApiIntegration::where('api_key', $key)
            ->where('is_active', true)
            ->first();

        if (! $integration) {
            return response()->json([
                'status' => 'error',
                'message' => 'API Key tidak valid atau aplikasi tidak aktif.',
            ], 401);
        }

        // Catat waktu terakhir digunakan
        $integration->update(['last_used_at' => now()]);

        $request->attributes->set('api_integration', $integration);

        return $next($request);
    }
}
