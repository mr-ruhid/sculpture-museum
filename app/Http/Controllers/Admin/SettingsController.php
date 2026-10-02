<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
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

        return back()->with('success', 'SMTP saved.');
    }

    public function general()
    {
        $settings = Setting::group('general');
        $languages = Language::active();
        return view('admin.settings.general', compact('settings', 'languages'));
    }

    public function generalUpdate(Request $request)
    {
        $languages = Language::active();
        $rules = ['logo' => ['nullable', 'image', 'max:2048'], 'favicon' => ['nullable', 'image', 'max:512']];

        foreach ($languages as $lang) {
            $rules["site_name_{$lang->code}"] = ['nullable', 'string', 'max:255'];
            $rules["site_description_{$lang->code}"] = ['nullable', 'string', 'max:500'];
            $rules["footer_text_{$lang->code}"] = ['nullable', 'string', 'max:255'];
        }

        $data = $request->validate($rules);

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

        return back()->with('success', 'General settings saved.');
    }

    public function contact()
    {
        $settings = Setting::group('contact');
        $languages = Language::active();
        return view('admin.settings.contact', compact('settings', 'languages'));
    }

    public function contactUpdate(Request $request)
    {
        $languages = Language::active();
        $rules = [
            'contact_email' => ['nullable', 'email'],
            'contact_phone' => ['nullable', 'string', 'max:50'],
            'contact_phone_2' => ['nullable', 'string', 'max:50'],
            'contact_fax' => ['nullable', 'string', 'max:50'],
            'map_embed' => ['nullable', 'string'],
        ];

        foreach ($languages as $lang) {
            $rules["contact_address_{$lang->code}"] = ['nullable', 'string', 'max:255'];
            $rules["working_hours_{$lang->code}"] = ['nullable', 'string', 'max:255'];
        }

        $data = $request->validate($rules);

        foreach ($data as $key => $value) {
            Setting::set($key, $value ?? '', 'contact');
        }

        return back()->with('success', 'Contact settings saved.');
    }

    public function social()
    {
        $settings = Setting::group('social');
        return view('admin.settings.social', compact('settings'));
    }

    public function socialUpdate(Request $request)
    {
        $data = $request->validate([
            'social_facebook' => ['nullable', 'url'],
            'social_instagram' => ['nullable', 'url'],
            'social_twitter' => ['nullable', 'url'],
            'social_youtube' => ['nullable', 'url'],
            'social_linkedin' => ['nullable', 'url'],
            'social_telegram' => ['nullable', 'url'],
            'social_whatsapp' => ['nullable', 'url'],
        ]);

        foreach ($data as $key => $value) {
            Setting::set($key, $value ?? '', 'social');
        }

        return back()->with('success', 'Social settings saved.');
    }

    public function seo()
    {
        $settings = Setting::group('seo');
        $languages = Language::active();
        return view('admin.settings.seo', compact('settings', 'languages'));
    }

    public function seoUpdate(Request $request)
    {
        $languages = Language::active();
        $rules = [
            'google_analytics' => ['nullable', 'string', 'max:50'],
            'yandex_metrika' => ['nullable', 'string', 'max:50'],
            'google_search_console' => ['nullable', 'string', 'max:255'],
            'robots_txt' => ['nullable', 'string'],
        ];

        foreach ($languages as $lang) {
            $rules["meta_title_{$lang->code}"] = ['nullable', 'string', 'max:255'];
            $rules["meta_description_{$lang->code}"] = ['nullable', 'string', 'max:500'];
            $rules["meta_keywords_{$lang->code}"] = ['nullable', 'string', 'max:500'];
        }

        $data = $request->validate($rules);

        foreach ($data as $key => $value) {
            Setting::set($key, $value ?? '', 'seo');
        }

        return back()->with('success', 'SEO settings saved.');
    }

    public function homepage()
    {
        $settings = Setting::group('homepage');
        $languages = Language::active();
        return view('admin.settings.homepage', compact('settings', 'languages'));
    }

    public function homepageUpdate(Request $request)
    {
        $languages = Language::active();
        $rules = [
            'stat_sculptures' => ['nullable', 'integer'],
            'stat_cities' => ['nullable', 'integer'],
            'stat_sculptors' => ['nullable', 'integer'],
            'stat_years' => ['nullable', 'string', 'max:50'],
            'hero_image' => ['nullable', 'image', 'max:4096'],
        ];

        foreach ($languages as $lang) {
            $rules["hero_title_{$lang->code}"] = ['nullable', 'string', 'max:255'];
            $rules["hero_subtitle_{$lang->code}"] = ['nullable', 'string', 'max:500'];
            $rules["hero_button_{$lang->code}"] = ['nullable', 'string', 'max:100'];
        }

        $data = $request->validate($rules);

        if ($request->hasFile('hero_image')) {
            $data['hero_image'] = $request->file('hero_image')->store('settings', 'public');
        } else {
            unset($data['hero_image']);
        }

        foreach ($data as $key => $value) {
            Setting::set($key, $value ?? '', 'homepage');
        }

        return back()->with('success', 'Homepage settings saved.');
    }

    public function about()
    {
        $settings = Setting::group('about');
        $languages = Language::active();
        return view('admin.settings.about', compact('settings', 'languages'));
    }

    public function aboutUpdate(Request $request)
    {
        $languages = Language::active();
        $rules = ['about_image' => ['nullable', 'image', 'max:4096']];

        foreach ($languages as $lang) {
            $rules["about_title_{$lang->code}"] = ['nullable', 'string', 'max:255'];
            $rules["about_short_{$lang->code}"] = ['nullable', 'string', 'max:500'];
            $rules["about_content_{$lang->code}"] = ['nullable', 'string'];
        }

        $data = $request->validate($rules);

        if ($request->hasFile('about_image')) {
            $data['about_image'] = $request->file('about_image')->store('settings', 'public');
        } else {
            unset($data['about_image']);
        }

        foreach ($data as $key => $value) {
            Setting::set($key, $value ?? '', 'about');
        }

        return back()->with('success', 'About settings saved.');
    }
}
