<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Sculpture;
use App\Models\ShortUrl;
use Illuminate\Http\Request;

class ShortUrlController extends Controller
{
    public function index()
    {
        $shortUrls = ShortUrl::latest()->paginate(20);
        return view('admin.short-urls.index', compact('shortUrls'));
    }

    public function create()
    {
        $sculptures = Sculpture::with('translations')->orderBy('id')->get();
        $pages = Page::orderBy('slug')->get();
        return view('admin.short-urls.create', compact('sculptures', 'pages'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:32', 'regex:/^[a-z0-9\-]+$/', 'unique:short_urls,code'],
            'target_type' => ['required', 'in:sculpture,page,sculptures_pair,custom'],
            'target_id' => ['nullable', 'integer'],
            'sculpture_ids' => ['nullable', 'array'],
            'sculpture_ids.*' => ['integer', 'exists:sculptures,id'],
            'custom_url' => ['nullable', 'string', 'max:500'],
            'note' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $targetId = null;
        $targetParams = null;

        if ($data['target_type'] === 'sculpture') {
            if (empty($data['target_id'])) {
                return back()->withErrors(['target_id' => 'Heykəl seçilməlidir.'])->withInput();
            }
            $targetId = $data['target_id'];
        } elseif ($data['target_type'] === 'page') {
            if (empty($data['target_id'])) {
                return back()->withErrors(['target_id' => 'Səhifə seçilməlidir.'])->withInput();
            }
            $targetId = $data['target_id'];
        } elseif ($data['target_type'] === 'sculptures_pair') {
            $ids = $data['sculpture_ids'] ?? [];
            if (count($ids) < 2) {
                return back()->withErrors(['sculpture_ids' => 'Ən azı 2 heykəl seçilməlidir.'])->withInput();
            }
            $slugs = Sculpture::whereIn('id', $ids)
                ->orderByRaw('FIELD(id, ' . implode(',', $ids) . ')')
                ->pluck('slug')
                ->toArray();
            $targetParams = ['slugs' => $slugs];
        } elseif ($data['target_type'] === 'custom') {
            if (empty($data['custom_url'])) {
                return back()->withErrors(['custom_url' => 'URL daxil edilməlidir.'])->withInput();
            }
            $targetParams = ['url' => $data['custom_url']];
        }

        ShortUrl::create([
            'code' => $data['code'],
            'target_type' => $data['target_type'],
            'target_id' => $targetId,
            'target_params' => $targetParams,
            'note' => $data['note'] ?? null,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.short-urls.index')->with('success', 'Qısa URL yaradıldı.');
    }

    public function edit(ShortUrl $shortUrl)
    {
        $sculptures = Sculpture::with('translations')->orderBy('id')->get();
        $pages = Page::orderBy('slug')->get();
        return view('admin.short-urls.edit', compact('shortUrl', 'sculptures', 'pages'));
    }

    public function update(Request $request, ShortUrl $shortUrl)
    {
        $data = $request->validate([
            'target_type' => ['required', 'in:sculpture,page,sculptures_pair,custom'],
            'target_id' => ['nullable', 'integer'],
            'sculpture_ids' => ['nullable', 'array'],
            'sculpture_ids.*' => ['integer', 'exists:sculptures,id'],
            'custom_url' => ['nullable', 'string', 'max:500'],
            'note' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $targetId = null;
        $targetParams = null;

        if ($data['target_type'] === 'sculpture' || $data['target_type'] === 'page') {
            $targetId = $data['target_id'] ?? null;
        } elseif ($data['target_type'] === 'sculptures_pair') {
            $ids = $data['sculpture_ids'] ?? [];
            if (count($ids) < 2) {
                return back()->withErrors(['sculpture_ids' => 'Ən azı 2 heykəl seçilməlidir.'])->withInput();
            }
            $slugs = Sculpture::whereIn('id', $ids)
                ->orderByRaw('FIELD(id, ' . implode(',', $ids) . ')')
                ->pluck('slug')
                ->toArray();
            $targetParams = ['slugs' => $slugs];
        } elseif ($data['target_type'] === 'custom') {
            $targetParams = ['url' => $data['custom_url'] ?? ''];
        }

        $shortUrl->update([
            'target_type' => $data['target_type'],
            'target_id' => $targetId,
            'target_params' => $targetParams,
            'note' => $data['note'] ?? null,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return back()->with('success', 'Qısa URL yeniləndi.');
    }

    public function destroy(ShortUrl $shortUrl)
    {
        $shortUrl->delete();
        return redirect()->route('admin.short-urls.index')->with('success', 'Qısa URL silindi.');
    }
}
