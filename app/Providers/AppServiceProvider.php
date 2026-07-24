<?php

namespace App\Providers;

use App\Models\SchoolSetting;
use Carbon\Carbon;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Rate limiter untuk RFID scan API (60 requests per menit per device)
        RateLimiter::for('rfid', function ($request) {
            return Limit::perMinute(60);
        });

        // Set locale Carbon ke Indonesia
        Carbon::setLocale('id');

        // Share school settings ke semua views secara global
        View::composer('*', function ($view) {
            try {
                $schoolSettings = \Illuminate\Support\Facades\Cache::rememberForever('global_school_settings', function () {
                    return [
                        'app_name' => SchoolSetting::get('app_name', 'PRESENSI RTH NEXUS'),
                        'school_name' => SchoolSetting::get('school_name', 'SMPN 1 Contoh'),
                        'school_tagline' => SchoolSetting::get('school_tagline', 'Disiplin, Cerdas, Berkarakter'),
                        'school_address' => SchoolSetting::get('school_address', ''),
                        'school_phone' => SchoolSetting::get('school_phone', ''),
                        'school_email' => SchoolSetting::get('school_email', ''),
                        'footer_text' => SchoolSetting::get('footer_text', 'PRESENSI RTH NEXUS — Hak Cipta © '.date('Y')),
                        'logo_url' => SchoolSetting::logoUrl(),
                        'kiosk_bg_style' => SchoolSetting::kioskBgStyle(),
                        'kiosk_title' => SchoolSetting::get('kiosk_title', 'PRESENSI RTH NEXUS'),
                        'kiosk_subtitle' => SchoolSetting::get('kiosk_subtitle', 'Tempelkan Kartu RFID pada Reader'),
                        'kiosk_bg_type' => SchoolSetting::get('kiosk_bg_type', 'gradient'),
                        'kiosk_bg_color' => SchoolSetting::get('kiosk_bg_color', '#0f172a'),
                        'kiosk_bg_image_url' => (function () {
                            $img = SchoolSetting::get('kiosk_bg_image');

                            return $img ? asset('storage/'.$img) : null;
                        })(),
                    ];
                });
                $view->with('schoolSettings', $schoolSettings);
            } catch (\Exception $e) {
                // Jika tabel belum ada (misal sebelum migrate), gunakan default
                $view->with('schoolSettings', [
                    'app_name' => 'PRESENSI RTH NEXUS',
                    'school_name' => 'SMPN 1 Contoh',
                    'school_tagline' => '',
                    'school_address' => '',
                    'school_phone' => '',
                    'school_email' => '',
                    'footer_text' => 'PRESENSI RTH NEXUS',
                    'logo_url' => null,
                    'kiosk_bg_style' => 'background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #0f172a 100%);',
                    'kiosk_title' => 'PRESENSI RTH NEXUS',
                    'kiosk_subtitle' => 'Tempelkan Kartu RFID pada Reader',
                    'kiosk_bg_type' => 'gradient',
                    'kiosk_bg_color' => '#0f172a',
                    'kiosk_bg_image_url' => null,
                ]);
            }
        });
    }
}
