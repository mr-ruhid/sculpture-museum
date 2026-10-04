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
        $sculptures = Sculpture::with('translations')->orderBy('id')->get();
        $pages = Page::orderBy('slug')->get();

        return view('admin.short-urls.index', compact('shortUrls', 'sculptures', 'pages'));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request, true);

        [$targetId, $targetParams] = $this->resolveTarget($data, $request);

        if ($targetId === false) {
            return back()->withErrors($this->errorBag())->withInput();
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

    public function update(Request $request, ShortUrl $shortUrl)
    {
        $data = $this->validateData($request, false);

        [$targetId, $targetParams] = $this->resolveTarget($data, $request);

        if ($targetId === false) {
            return back()->withErrors($this->errorBag())->withInput();
        }

        $shortUrl->update([
            'target_type' => $data['target_type'],
            'target_id' => $targetId,
            'target_params' => $targetParams,
            'note' => $data['note'] ?? null,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.short-urls.index')->with('success', 'Qısa URL yeniləndi.');
    }

    public function destroy(ShortUrl $shortUrl)
    {
        $shortUrl->delete();
        return redirect()->route('admin.short-urls.index')->with('success', 'Qısa URL silindi.');
    }

    private function validateData(Request $request, bool $isCreate): array
    {
        $rules = [
            'target_type' => ['required', 'in:sculpture,page,sculptures_pair'],
            'target_id' => ['nullable', 'integer'],
            'sculpture_ids' => ['nullable', 'array'],
            'sculpture_ids.*' => ['integer', 'exists:sculptures,id'],
            'note' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ];

        if ($isCreate) {
            $rules['code'] = ['required', 'string', 'max:32', 'regex:/^[a-z0-9\-]+$/', 'unique:short_urls,code'];
        }

        return $request->validate($rules);
    }

    private function resolveTarget(array $data, Request $request): array
    {
        $this->errorBag = [];

        if ($data['target_type'] === 'sculpture') {
            if (empty($data['target_id'])) {
                $this->errorBag = ['target_id' => 'Heykəl seçilməlidir.'];
                return [false, null];
            }
            return [(int) $data['target_id'], null];
        }

        if ($data['target_type'] === 'page') {
            if (empty($data['target_id'])) {
                $this->errorBag = ['target_id' => 'Səhifə seçilməlidir.'];
                return [false, null];
            }
            return [(int) $data['target_id'], null];
        }

        if ($data['target_type'] === 'sculptures_pair') {
            $ids = array_map('intval', $data['sculpture_ids'] ?? []);
            if (count($ids) < 2) {
                $this->errorBag = ['sculpture_ids' => 'Ən azı 2 heykəl seçilməlidir.'];
                return [false, null];
            }
            return [null, ['ids' => $ids]];
        }

        $this->errorBag = ['target_type' => 'Yanlış növ.'];
        return [false, null];
    }

    private array $errorBag = [];

    private function errorBag(): array
    {
        return $this->errorBag ?: ['form' => 'Xəta baş verdi.'];
    }
}
