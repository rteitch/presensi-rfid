<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use Illuminate\Http\Request;

class HolidayController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $escaped = $search ? str_replace(['\\', '%', '_'], ['\\\\', '\%', '\_'], $search) : null;

        $holidays = Holiday::query()
            ->when($escaped, fn ($q) => $q->where('nama_libur', 'like', "%{$escaped}%"))
            ->latest('tanggal_mulai')
            ->paginate(15)
            ->withQueryString();

        return view('holidays.index', compact('holidays', 'search'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_libur' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'keterangan' => 'nullable|string',
        ]);

        Holiday::create($request->only(['nama_libur', 'tanggal_mulai', 'tanggal_selesai', 'keterangan']));

        return back()->with('success', 'Hari libur berhasil ditambahkan ke kalender.');
    }

    public function destroy(Holiday $holiday)
    {
        $holiday->delete();

        return back()->with('success', 'Hari libur berhasil dihapus dari kalender.');
    }
}
