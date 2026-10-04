<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function index()
    {
        Language::syncFromFiles();
        $languages = Language::orderBy('is_default', 'desc')->get();
        return view('admin.languages.index', compact('languages'));
    }

    public function update(Request $request, Language $language)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'flag' => ['nullable', 'string', 'max:500'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active');

        if (!$data['is_active'] && $language->is_default) {
            return back()->withErrors(['is_active' => 'Default dil deaktiv edilə bilməz.']);
        }

        $language->update($data);

        return back()->with('success', 'Dil yeniləndi.');
    }

    public function setDefault(Language $language)
    {
        $language->setAsDefault();
        return back()->with('success', 'Default dil dəyişdirildi.');
    }
}
