<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\LogsActivity;

class SchoolClass extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'classes';

    protected $fillable = ['nama_kelas', 'wali_kelas_id', 'academic_year_id'];

    public function waliKelas()
    {
        return $this->belongsTo(User::class, 'wali_kelas_id');
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id');
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'class_id');
    }
}
