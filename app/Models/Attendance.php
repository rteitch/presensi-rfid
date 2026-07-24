<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = ['student_id', 'tanggal', 'jam_masuk', 'jam_pulang', 'status', 'keterangan'];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}
