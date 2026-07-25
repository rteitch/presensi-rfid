<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\LogsActivity;

class Student extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'nis',
        'nama',
        'jenis_kelamin',
        'agama',
        'rfid_uid',
        'class_id',
        'nama_ortu',
        'no_hp_ortu',
        'foto',
        'status',
    ];

    protected $appends = ['foto_url', 'wa_number'];

    public function getFotoUrlAttribute(): ?string
    {
        if ($this->foto) {
            if (str_starts_with($this->foto, 'http://') || str_starts_with($this->foto, 'https://')) {
                return $this->foto;
            }
            return asset('storage/'.$this->foto);
        }

        return 'https://ui-avatars.com/api/?name='.urlencode($this->nama).'&background=0D8ABC&color=fff';
    }

    public function getWaNumberAttribute(): ?string
    {
        if (! $this->no_hp_ortu) {
            return null;
        }
        $hp = preg_replace('/[^0-9]/', '', $this->no_hp_ortu);
        if (str_starts_with($hp, '0')) {
            $hp = '62'.substr($hp, 1);
        }

        return $hp;
    }

    public function getWaLateSummaryLinkAttribute(): ?string
    {
        if (! $this->wa_number) {
            return null;
        }
        $namaOrtu = $this->nama_ortu ?: 'Bapak/Ibu Orang Tua';
        $totalTelat = $this->attendances_count ?? $this->attendances()->where('status', 'terlambat')->count();
        $kelas = $this->schoolClass ? $this->schoolClass->nama_kelas : '-';

        $msg = "Assalamu'alaikum Wr. Wb. Yth. {$namaOrtu},\n\nMenginfokan catatan kedisiplinan ananda *{$this->nama}* (Kelas {$kelas}).\n\nDalam periode ini, ananda telah tercatat *TERLAMBAT* sebanyak *{$totalTelat} kali*.\n\nMohon perhatian dan pembinaannya demi kedisiplinan ananda. Terima kasih.\n- Pengurus Sekolah";

        return 'https://wa.me/'.$this->wa_number.'?text='.urlencode($msg);
    }

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'student_id');
    }

    public function rfidLogs()
    {
        return $this->hasMany(RfidLog::class, 'student_id');
    }
}
