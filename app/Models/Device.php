<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;

    protected $fillable = ['nama_device', 'tipe_device', 'lokasi', 'token_device', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function rfidLogs()
    {
        return $this->hasMany(RfidLog::class, 'device_id');
    }
}
