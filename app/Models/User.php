<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasRoles, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function managedClasses()
    {
        return $this->hasMany(SchoolClass::class, 'wali_kelas_id');
    }

    public function getManagedClassIdsAttribute(): array
    {
        if ($this->relationLoaded('managedClasses')) {
            return $this->managedClasses->pluck('id')->toArray();
        }

        if (!isset($this->attributes['managed_class_ids_cache'])) {
            $this->attributes['managed_class_ids_cache'] = $this->managedClasses()->pluck('id')->toArray();
        }

        return $this->attributes['managed_class_ids_cache'];
    }

    public function teacher()
    {
        return $this->hasOne(Teacher::class, 'user_id');
    }
}
