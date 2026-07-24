<?php

namespace App\Http\Controllers;

use App\Models\SchoolSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SchoolSettingController extends Controller
{
    public function index()
    {
        $s = SchoolSetting::all();

        return view('settings.school', compact('s'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'app_name' => 'required|string|max:255',
            'school_name' => 'required|string|max:255',
            'school_tagline' => 'nullable|string|max:255',
            'school_address' => 'nullable|string|max:500',
            'school_phone' => 'nullable|string|max:100',
            'school_email' => 'nullable|email|max:255',
            'footer_text' => 'nullable|string|max:500',
            'school_logo' => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:2048',
            'school_favicon' => 'nullable|image|mimes:jpeg,png,jpg,svg,ico,webp|max:1024',
            'kiosk_bg_type' => 'required|in:gradient,color,image',
            'kiosk_bg_color' => 'nullable|string|max:20',
            'kiosk_bg_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'kiosk_title' => 'nullable|string|max:255',
            'kiosk_subtitle' => 'nullable|string|max:255',
        ]);

        $textFields = [
            'app_name', 'school_name', 'school_tagline', 'school_address',
            'school_phone', 'school_email', 'footer_text',
            'kiosk_bg_type', 'kiosk_bg_color',
            'kiosk_title', 'kiosk_subtitle',
        ];

        foreach ($textFields as $field) {
            if ($request->has($field)) {
                SchoolSetting::set($field, $request->input($field));
            }
        }

        // Handle logo upload
        if ($request->hasFile('school_logo')) {
            $old = SchoolSetting::get('school_logo');
            if ($old && Storage::disk('public')->exists($old)) {
                Storage::disk('public')->delete($old);
            }
            $path = $request->file('school_logo')->store('school', 'public');
            SchoolSetting::set('school_logo', $path);
        }

        // Handle favicon upload
        if ($request->hasFile('school_favicon')) {
            $old = SchoolSetting::get('school_favicon');
            if ($old && Storage::disk('public')->exists($old)) {
                Storage::disk('public')->delete($old);
            }
            $path = $request->file('school_favicon')->store('school', 'public');
            SchoolSetting::set('school_favicon', $path);
        }

        // Handle kiosk wallpaper upload
        if ($request->hasFile('kiosk_bg_image')) {
            $old = SchoolSetting::get('kiosk_bg_image');
            if ($old && Storage::disk('public')->exists($old)) {
                Storage::disk('public')->delete($old);
            }
            $path = $request->file('kiosk_bg_image')->store('school', 'public');
            SchoolSetting::set('kiosk_bg_image', $path);
        }

        return back()->with('success', 'Pengaturan konfigurasi sekolah telah berhasil disimpan.');
    }
}
