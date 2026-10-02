<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class TranslationController extends Controller
{
    public function edit(Language $language)
    {
        $path = lang_path("{$language->code}.json");

        $translations = File::exists($path)
            ? json_decode(File::get($path), true)
            : [];

        return view('admin.translations.edit', compact('language', 'translations'));
    }

    public function update(Request $request, Language $language)
    {
        $data = $request->validate([
            'translations' => ['nullable', 'array'],
            'translations.*' => ['nullable', 'string'],
        ]);

        $translations = collect($data['translations'] ?? [])
            ->filter(fn ($value, $key) => !empty($key))
            ->toArray();

        $path = lang_path("{$language->code}.json");

        File::put(
            $path,
            json_encode($translations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );

        return back()->with('success', 'Tərcümələr yadda saxlanıldı.');
    }
}
