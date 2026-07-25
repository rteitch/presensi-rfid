<?php

namespace App\Http\Middleware;

use App\Models\Device;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DeviceTokenMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->header('X-Device-Token') ?? $request->input('device_token');

        if (! $token) {
            if (auth('web')->check()) {
                $device = Device::where('is_active', true)->first();
                if ($device) {
                    $request->attributes->set('device', $device);

                    return $next($request);
                }
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Maaf, token device RFID tidak disertakan.',
            ], 401);
        }

        $device = Device::where('token_device', $token)->where('is_active', true)->first();

        if (! $device) {
            return response()->json([
                'status' => 'error',
                'message' => 'Maaf, perangkat (Kiosk) tidak dikenali atau sedang dinonaktifkan.',
            ], 401);
        }

        $request->attributes->set('device', $device);

        return $next($request);
    }
}
