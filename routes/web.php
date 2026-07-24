<?php

use App\Http\Controllers\ApiIntegrationController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AttendanceSettingController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SchoolSettingController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/guide', [DashboardController::class, 'guide'])->name('guide');

    Route::middleware('role:admin')->group(function () {
        Route::get('/students/import', [StudentController::class, 'import'])->name('students.import');
        Route::post('/students/import', [StudentController::class, 'importStore'])->name('students.import-store');
        Route::get('/students/export', [StudentController::class, 'export'])->name('students.export');
        Route::get('/students/template', [StudentController::class, 'template'])->name('students.template');
        Route::resource('students', StudentController::class)->except(['index', 'show']);
        Route::resource('classes', ClassController::class)->except(['index', 'show']);
        Route::get('/teachers/import', [TeacherController::class, 'import'])->name('teachers.import');
        Route::post('/teachers/import', [TeacherController::class, 'importStore'])->name('teachers.import-store');
        Route::get('/teachers/export', [TeacherController::class, 'export'])->name('teachers.export');
        Route::get('/teachers/template', [TeacherController::class, 'template'])->name('teachers.template');
        Route::resource('teachers', TeacherController::class)->except(['show']);
        Route::resource('devices', DeviceController::class)->except(['show']);
        Route::post('/devices/{device}/regenerate-token', [DeviceController::class, 'regenerateToken'])->name('devices.regenerate');
        Route::get('/settings', [AttendanceSettingController::class, 'index'])->name('settings.index');
        Route::post('/settings', [AttendanceSettingController::class, 'update'])->name('settings.update');
        Route::post('/settings/academic-year', [AttendanceSettingController::class, 'storeAcademicYear'])->name('settings.academic-year');
        // Konfigurasi Sekolah (logo, nama, kiosk, footer)
        Route::get('/settings/school', [SchoolSettingController::class, 'index'])->name('settings.school');
        Route::post('/settings/school', [SchoolSettingController::class, 'update'])->name('settings.school.update');
        Route::resource('users', UserController::class)->except(['show']);
        Route::resource('integrations', ApiIntegrationController::class)->except(['show']);
        Route::post('/integrations/{integration}/regenerate', [ApiIntegrationController::class, 'regenerate'])->name('integrations.regenerate');
        Route::get('/activity-logs', [\App\Http\Controllers\ActivityLogController::class, 'index'])->name('activity-logs.index');
        Route::resource('holidays', \App\Http\Controllers\HolidayController::class)->only(['index', 'store', 'destroy']);
    });

    Route::middleware('role:admin|guru')->group(function () {
        Route::get('/students', [StudentController::class, 'index'])->name('students.index');
        Route::get('/students/{student}', [StudentController::class, 'show'])->name('students.show');
        Route::get('/students/{student}/export-attendance', [StudentController::class, 'exportAttendance'])->name('students.export-attendance');
        Route::get('/classes', [ClassController::class, 'index'])->name('classes.index');
        Route::get('/classes/{class}', [ClassController::class, 'show'])->name('classes.show');
        Route::get('/classes/{class}/export-excel', [ClassController::class, 'exportExcel'])->name('classes.export-excel');
        Route::get('/classes/{class}/export-pdf', [ClassController::class, 'exportPdf'])->name('classes.export-pdf');

        Route::get('/attendances', [AttendanceController::class, 'index'])->name('attendances.index');
        Route::post('/attendances/manual', [AttendanceController::class, 'storeManual'])->name('attendances.manual');
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/export-pdf', [ReportController::class, 'exportPdf'])->name('reports.pdf');
        Route::get('/reports/export-excel', [ReportController::class, 'exportExcel'])->name('reports.excel');
        Route::get('/reports/rekap', [ReportController::class, 'rekap'])->name('reports.rekap');
        Route::get('/reports/rekap/export-pdf', [ReportController::class, 'exportRekapPdf'])->name('reports.rekap-pdf');
        Route::get('/reports/rekap/export-excel', [ReportController::class, 'exportRekapExcel'])->name('reports.rekap-excel');
        Route::get('/reports/leaderboard', [ReportController::class, 'leaderboard'])->name('reports.leaderboard');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Halaman Kiosk Tap RFID & Leaderboard Publik (Layar Penuh, tanpa Auth)
Route::get('/kiosk', function () {
    $activeDevices = \App\Models\Device::where('is_active', true)->select('id', 'nama_device', 'lokasi')->get();
    return view('kiosk.scan', compact('activeDevices'));
})->name('kiosk.scan');

Route::get('/leaderboard', [ReportController::class, 'publicLeaderboard'])->name('public.leaderboard');

require __DIR__.'/auth.php';
