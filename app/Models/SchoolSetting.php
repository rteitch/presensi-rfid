<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class SchoolSetting extends Model
{
    protected $table = 'school_settings';

    protected $primaryKey = 'key';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = ['key', 'value'];

    /**
     * Get a setting value by key, with optional default.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        return Cache::rememberForever("school_setting_{$key}", function () use ($key, $default) {
            $setting = static::find($key);

            return $setting ? $setting->value : $default;
        });
    }

    /**
     * Set a setting value by key and clear cache.
     */
    public static function set(string $key, mixed $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget("school_setting_{$key}");
        Cache::forget('global_school_settings');
    }

    /**
     * Get all settings as an associative array.
     */
    public static function all($columns = ['*'])
    {
        return static::query()->get()->pluck('value', 'key');
    }

    /**
     * Get school logo URL (full URL if uploaded, null otherwise).
     */
    public static function logoUrl(): ?string
    {
        $logo = static::get('school_logo');
        if ($logo && Storage::disk('public')->exists($logo)) {
            return asset('storage/'.$logo);
        }

        return null;
    }

    /**
     * Get kiosk wallpaper CSS style string.
     */
    public static function kioskBgStyle(): string
    {
        $type = static::get('kiosk_bg_type', 'gradient');

        if ($type === 'image') {
            $img = static::get('kiosk_bg_image');
            if ($img && Storage::disk('public')->exists($img)) {
                return 'background-image: url('.asset('storage/'.$img).'); background-size: cover; background-position: center;';
            }
        }

        if ($type === 'color') {
            $color = static::get('kiosk_bg_color', '#0f172a');

            return "background-color: {$color};";
        }

        // Default: gradient
        return 'background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #0f172a 100%);';
    }

    /**
     * Default settings values for seeding.
     */
    public static function defaults(): array
    {
        return [
            'app_name' => 'PRESENSI RTH NEXUS',
            'school_name' => 'SMPN 1 Contoh',
            'school_tagline' => 'Disiplin, Cerdas, Berkarakter',
            'school_logo' => null,
            'school_address' => 'Jl. Pendidikan No. 1, Kota Contoh',
            'school_phone' => '(021) 123-4567',
            'school_email' => 'info@smpn1contoh.sch.id',
            'footer_text' => 'PRESENSI RTH NEXUS — Hak Cipta © '.date('Y').'. All rights reserved.',
            'kiosk_bg_type' => 'gradient',
            'kiosk_bg_color' => '#0f172a',
            'kiosk_bg_image' => null,
            'kiosk_title' => 'PRESENSI RTH NEXUS',
            'kiosk_subtitle' => 'Tempelkan Kartu RFID pada Reader',
            'rate_limit_api' => 60,
            'hari_efektif' => json_encode(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']),
        ];
    }
}
