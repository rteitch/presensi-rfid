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
            // For web session, strictly require referer from /kiosk to prevent arbitrary API impersonation
            if (auth('web')->check()) {
                $referer = (string) $request->headers->get('referer', '');
                $isKioskSession = str_contains($referer, '/kiosk') || $request->session()->get('is_kiosk');

                if ($isKioskSession) {
                    $deviceId = session('selected_device_id');
                    $device = $deviceId 
                        ? Device::where('id', $deviceId)->where('is_active', true)->first()
                        : Device::where('tipe_device', 'kiosk_browser')->where('is_active', true)->first();

                    if (! $device) {
                        $device = Device::where('is_active', true)->first();
                    }

                    if ($device) {
                        $request->attributes->set('device', $device);

                        return $next($request);
                    }
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
