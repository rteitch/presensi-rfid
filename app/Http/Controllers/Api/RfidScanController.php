<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RfidScanRequest;
use App\Services\AttendanceService;

class RfidScanController extends Controller
{
    public function __construct(protected AttendanceService $attendanceService) {}

    public function store(RfidScanRequest $request)
    {
        $device = $request->attributes->get('device');

        $result = $this->attendanceService->processScan($request->validated('rfid_uid'), $device?->id);

        return response()->json($result);
    }
}
