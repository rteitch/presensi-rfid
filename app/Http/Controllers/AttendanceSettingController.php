<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\AttendanceSetting;
use Illuminate\Http\Request;

class AttendanceSettingController extends Controller
{
    public function index()
    {
        $activeYear = AcademicYear::active();
        $setting = $activeYear ? $activeYear->attendanceSetting : null;
        $academicYears = AcademicYear::all();

        return view('settings.index', compact('activeYear', 'setting', 'academicYears'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'jam_masuk' => 'required|date_format:H:i',
            'jam_pulang' => 'required|date_format:H:i',
            'toleransi_menit' => 'required|integer|min:0|max:120',
        ]);

        // Activate selected academic year atomically
        \Illuminate\Support\Facades\DB::transaction(function () use ($request, &$year) {
            AcademicYear::query()->update(['is_active' => false]);
            $year = AcademicYear::findOrFail($request->input('academic_year_id'));
            $year->update(['is_active' => true]);
        });

        // Update or create setting for this academic year
        AttendanceSetting::updateOrCreate(
            ['academic_year_id' => $year->id],
            [
                'jam_masuk' => $request->input('jam_masuk').':00',
                'jam_pulang' => $request->input('jam_pulang').':00',
                'toleransi_menit' => $request->input('toleransi_menit'),
            ]
        );

        return back()->with('success', 'Pengaturan jam presensi telah berhasil diperbarui.');
    }

    public function storeAcademicYear(Request $request)
    {
        $request->validate([
            'nama' => 'required|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
        ]);

        AcademicYear::create($request->only(['nama', 'tanggal_mulai', 'tanggal_selesai']));

        return back()->with('success', 'Tahun Ajaran baru telah berhasil ditambahkan.');
    }
}
