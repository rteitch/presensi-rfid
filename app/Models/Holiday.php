<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class Holiday extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'nama_libur',
        'tanggal_mulai',
        'tanggal_selesai',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    public static function isHoliday(string $dateString): bool
    {
        return static::where('tanggal_mulai', '<=', $dateString)
            ->where('tanggal_selesai', '>=', $dateString)
            ->exists();
    }
}
