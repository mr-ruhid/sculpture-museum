<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function smtp()
    {
        $settings = Setting::group('smtp');
        return view('admin.settings.smtp', compact('settings'));
    }

    public function smtpUpdate(Request $request)
    {
        $data = $request->validate([
            'smtp_host' => ['nullable', 'string', 'max:255'],
            'smtp_port' => ['nullable', 'integer'],
            'smtp_username' => ['nullable', 'string', 'max:255'],
            'smtp_password' => ['nullable', 'string', 'max:255'],
            'smtp_encryption' => ['nullable', 'in:tls,ssl,none'],
            'smtp_from_address' => ['nullable', 'email'],
            'smtp_from_name' => ['nullable', 'string', 'max:255'],
        ]);

        foreach ($data as $key => $value) {
            Setting::set($key, $value ?? '', 'smtp');
        }

        return back()->with('success', 'SMTP ayarları yadda saxlanıldı.');
    }

    public function general()
    {
        $settings = Setting::group('general');
        return view('admin.settings.general', compact('settings'));
    }

    public function generalUpdate(Request $request)
    {
        $data = $request->validate([
            'site_name' => ['nullable', 'string', 'max:255'],
            'site_description' => ['nullable', 'string', 'max:500'],
            'contact_email' => ['nullable', 'email'],
            'contact_phone' => ['nullable', 'string', 'max:50'],
            'contact_address' => ['nullable', 'string', 'max:255'],
            'social_facebook' => ['nullable', 'url'],
            'social_instagram' => ['nullable', 'url'],
            'social_twitter' => ['nullable', 'url'],
            'social_youtube' => ['nullable', 'url'],
            'logo' => ['nullable', 'image', 'max:2048'],
            'favicon' => ['nullable', 'image', 'max:512'],
        ]);

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('settings', 'public');
        } else {
            unset($data['logo']);
        }

        if ($request->hasFile('favicon')) {
            $data['favicon'] = $request->file('favicon')->store('settings', 'public');
        } else {
            unset($data['favicon']);
        }

        foreach ($data as $key => $value) {
            Setting::set($key, $value ?? '', 'general');
        }

        return back()->with('success', 'Ümumi ayarlar yadda saxlanıldı.');
    }
}
