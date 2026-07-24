<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RfidLog extends Model
{
    use HasFactory;

    protected $fillable = ['rfid_uid', 'student_id', 'device_id', 'is_valid', 'keterangan', 'scanned_at'];

    protected $casts = [
        'scanned_at' => 'datetime',
        'is_valid' => 'boolean',
    ];

    public function device()
    {
        return $this->belongsTo(Device::class, 'device_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}
