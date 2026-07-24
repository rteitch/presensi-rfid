<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDeviceRequest;
use App\Http\Requests\UpdateDeviceRequest;
use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DeviceController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $escaped = $search ? str_replace(['\\', '%', '_'], ['\\\\', '\%', '\_'], $search) : null;
        $devices = Device::query()
            ->when($escaped, function ($query, $escaped) {
                $query->where('nama_device', 'like', "%{$escaped}%")
                    ->orWhere('lokasi', 'like', "%{$escaped}%");
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('devices.index', compact('devices', 'search'));
    }

    public function create()
    {
        $token = Str::random(40);

        return view('devices.create', compact('token'));
    }

    public function store(StoreDeviceRequest $request)
    {
        Device::create($request->validated());

        return redirect()->route('devices.index')->with('success', 'Device RFID berhasil ditambahkan.');
    }

    public function edit(Device $device)
    {
        return view('devices.edit', compact('device'));
    }

    public function update(UpdateDeviceRequest $request, Device $device)
    {
        $device->update($request->validated());

        return redirect()->route('devices.index')->with('success', 'Device RFID berhasil diperbarui.');
    }

    public function destroy(Device $device)
    {
        $device->delete();

        return redirect()->route('devices.index')->with('success', 'Device RFID berhasil dihapus.');
    }

    public function regenerateToken(Device $device)
    {
        $device->update(['token_device' => Str::random(40)]);

        return back()->with('success', 'Token device berhasil di-regenerate.');
    }
}
