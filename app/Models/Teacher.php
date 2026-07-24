<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\LogsActivity;

class Teacher extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'nip',
        'nama',
        'email',
        'user_id',
        'no_hp',
        'mata_pelajaran',
        'foto',
        'status',
    ];

    protected $appends = ['foto_url'];

    public function getFotoUrlAttribute(): ?string
    {
        if ($this->foto) {
            return asset('storage/'.$this->foto);
        }

        return 'https://ui-avatars.com/api/?name='.urlencode($this->nama).'&background=0D9488&color=fff';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function schoolClasses()
    {
        return $this->hasMany(SchoolClass::class, 'wali_kelas_id', 'user_id');
    }
}
