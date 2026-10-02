<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\Sculpture;
use App\Models\SculptureImage;
use App\Models\SculptureTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SculptureController extends Controller
{
    public function index()
    {
        $sculptures = Sculpture::with('translations')->latest()->paginate(15);
        return view('admin.sculptures.index', compact('sculptures'));
    }

    public function create()
    {
        $languages = Language::active();
        return view('admin.sculptures.create', compact('languages'));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['slug'] = $this->makeSlug($request);

        if ($request->hasFile('main_image')) {
            $data['main_image'] = $request->file('main_image')->store('sculptures', 'public');
        }

        $sculpture = Sculpture::create($data);

        $this->saveTranslations($sculpture, $request);
        $this->saveGallery($sculpture, $request);

        return redirect()->route('admin.sculptures.index')->with('success', 'Heykəl əlavə edildi.');
    }

    public function show(Sculpture $sculpture)
    {
        $sculpture->load('translations', 'images');
        return view('admin.sculptures.show', compact('sculpture'));
    }

    public function edit(Sculpture $sculpture)
    {
        $sculpture->load('translations', 'images');
        $languages = Language::active();
        return view('admin.sculptures.edit', compact('sculpture', 'languages'));
    }

    public function update(Request $request, Sculpture $sculpture)
    {
        $data = $this->validateData($request);
        $data['slug'] = $this->makeSlug($request, $sculpture);

        if ($request->hasFile('main_image')) {
            $data['main_image'] = $request->file('main_image')->store('sculptures', 'public');
        }

        $sculpture->update($data);

        $this->saveTranslations($sculpture, $request);
        $this->saveGallery($sculpture, $request);

        return redirect()->route('admin.sculptures.index')->with('success', 'Heykəl yeniləndi.');
    }

    public function destroy(Sculpture $sculpture)
    {
        $sculpture->delete();
        return redirect()->route('admin.sculptures.index')->with('success', 'Heykəl silindi.');
    }

    public function destroyImage(SculptureImage $image)
    {
        $image->delete();
        return back()->with('success', 'Şəkil silindi.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'sculptor' => ['nullable', 'string', 'max:255'],
            'architect' => ['nullable', 'string', 'max:255'],
            'year' => ['nullable', 'integer', 'min:1000', 'max:2100'],
            'opening_date' => ['nullable', 'date'],
            'material' => ['nullable', 'string', 'max:255'],
            'dimensions' => ['nullable', 'string', 'max:255'],
            'style' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
            'condition' => ['required', 'in:exists,destroyed,moved'],
            'registration_info' => ['nullable', 'string'],
            'panorama_embed' => ['nullable', 'string'],
            'main_image' => ['nullable', 'image', 'max:8192'],
            'is_published' => ['nullable', 'boolean'],
            'translations' => ['required', 'array'],
            'translations.en.title' => ['required', 'string', 'max:255'],
            'translations.*.title' => ['nullable', 'string', 'max:255'],
            'translations.*.short_description' => ['nullable', 'string'],
            'translations.*.description' => ['nullable', 'string'],
            'translations.*.history' => ['nullable', 'string'],
            'translations.*.meta_title' => ['nullable', 'string', 'max:255'],
            'translations.*.meta_description' => ['nullable', 'string'],
            'translations.*.meta_keywords' => ['nullable', 'string'],
            'gallery' => ['nullable', 'array'],
            'gallery.*' => ['image', 'max:8192'],
        ]);
    }

    private function makeSlug(Request $request, ?Sculpture $sculpture = null): string
    {
        if ($request->filled('slug')) {
            return Str::slug($request->input('slug'));
        }

        $base = Str::slug($request->input('translations.en.title'));
        $slug = $base;
        $i = 1;

        while (Sculpture::where('slug', $slug)
            ->when($sculpture, fn ($q) => $q->where('id', '!=', $sculpture->id))
            ->exists()
        ) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }

    private function saveTranslations(Sculpture $sculpture, Request $request): void
    {
        $languages = Language::active()->pluck('code');
        $input = $request->input('translations', []);
        $en = $input['en'] ?? [];

        foreach ($languages as $locale) {
            $row = $input[$locale] ?? [];

            SculptureTranslation::updateOrCreate(
                ['sculpture_id' => $sculpture->id, 'locale' => $locale],
                [
                    'title' => $row['title'] ?? $en['title'] ?? '',
                    'short_description' => $row['short_description'] ?? $en['short_description'] ?? null,
                    'description' => $row['description'] ?? $en['description'] ?? null,
                    'history' => $row['history'] ?? $en['history'] ?? null,
                    'meta_title' => $row['meta_title'] ?? $en['meta_title'] ?? null,
                    'meta_description' => $row['meta_description'] ?? $en['meta_description'] ?? null,
                    'meta_keywords' => $row['meta_keywords'] ?? $en['meta_keywords'] ?? null,
                ]
            );
        }
    }

    private function saveGallery(Sculpture $sculpture, Request $request): void
    {
        if (!$request->hasFile('gallery')) {
            return;
        }

        $order = $sculpture->images()->max('sort_order') ?? 0;

        foreach ($request->file('gallery') as $file) {
            $path = $file->store('sculptures/gallery', 'public');
            SculptureImage::create([
                'sculpture_id' => $sculpture->id,
                'path' => $path,
                'sort_order' => ++$order,
            ]);
        }
    }
}
