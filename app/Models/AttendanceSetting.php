<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceSetting extends Model
{
    use HasFactory;

    protected $fillable = ['academic_year_id', 'jam_masuk', 'jam_pulang', 'toleransi_menit'];

    protected static function booted()
    {
        static::saved(function ($model) {
            \Illuminate\Support\Facades\Cache::forget('active_attendance_setting_' . $model->academic_year_id);
        });
        static::deleted(function ($model) {
            \Illuminate\Support\Facades\Cache::forget('active_attendance_setting_' . $model->academic_year_id);
        });
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id');
    }

    public static function activeSetting(): ?self
    {
        $year = AcademicYear::active();
        if (! $year) {
            return null;
        }

        return \Illuminate\Support\Facades\Cache::remember('active_attendance_setting_' . $year->id, 86400, function () use ($year) {
            return static::where('academic_year_id', $year->id)->first();
        });
    }
}
