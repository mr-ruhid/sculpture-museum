<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class PageController extends Controller
{
    protected string $pagesPath;

    public function __construct()
    {
        $this->pagesPath = resource_path('views/theme/rjmuseum/pages');
    }

    public function index()
    {
        $files = collect(File::files($this->pagesPath))
            ->map(fn ($f) => preg_replace('/\.blade\.php$/', '', $f->getFilename()))
            ->reject(fn ($slug) => Str::startsWith($slug, '_'))
            ->values();

        $dbPages = Page::with('translations')->get()->keyBy('slug');

        $pages = $files->map(function ($slug) use ($dbPages) {
            return (object) [
                'slug' => $slug,
                'model' => $dbPages->get($slug),
                'is_custom' => $dbPages->has($slug),
            ];
        });

        return view('admin.pages.index', compact('pages'));
    }

    public function create()
    {
        $languages = Language::active();
        return view('admin.pages.create', compact('languages'));
    }

    public function store(Request $request)
    {
        $languages = Language::active();

        $rules = ['slug' => ['required', 'string', 'max:100', 'regex:/^[a-z0-9\-]+$/']];
        foreach ($languages as $lang) {
            $rules["title_{$lang->code}"] = ['required', 'string', 'max:255'];
        }

        $data = $request->validate($rules);

        $slug = $data['slug'];

        if (in_array($slug, Page::reservedSlugs())) {
            return back()->withErrors(['slug' => 'Bu slug rezerv edilib.'])->withInput();
        }

        if (File::exists($this->pagesPath . '/' . $slug . '.blade.php')) {
            return back()->withErrors(['slug' => 'Bu slug artıq mövcuddur.'])->withInput();
        }

        $page = Page::create([
            'slug' => $slug,
            'is_published' => $request->boolean('is_published', true),
        ]);

        foreach ($languages as $lang) {
            $page->translations()->create([
                'locale' => $lang->code,
                'title' => $data["title_{$lang->code}"] ?? '',
                'content' => $request->input("content_{$lang->code}", ''),
                'meta_title' => $request->input("meta_title_{$lang->code}", ''),
                'meta_description' => $request->input("meta_description_{$lang->code}", ''),
                'meta_keywords' => $request->input("meta_keywords_{$lang->code}", ''),
            ]);
        }

        $this->writeStub($slug);

        return redirect()->route('admin.pages.edit', $page)->with('success', 'Səhifə yaradıldı.');
    }

    public function edit(Page $page)
    {
        $languages = Language::active();
        $page->load('translations');
        $translations = $page->translations->keyBy('locale');

        return view('admin.pages.edit', compact('page', 'languages', 'translations'));
    }

    public function update(Request $request, Page $page)
    {
        $languages = Language::active();

        $page->update(['is_published' => $request->boolean('is_published', true)]);

        foreach ($languages as $lang) {
            $page->translations()->updateOrCreate(
                ['locale' => $lang->code],
                [
                    'title' => $request->input("title_{$lang->code}", ''),
                    'content' => $request->input("content_{$lang->code}", ''),
                    'meta_title' => $request->input("meta_title_{$lang->code}", ''),
                    'meta_description' => $request->input("meta_description_{$lang->code}", ''),
                    'meta_keywords' => $request->input("meta_keywords_{$lang->code}", ''),
                ]
            );
        }

        return back()->with('success', 'Səhifə yeniləndi.');
    }

    public function destroy(Page $page)
    {
        $file = $this->pagesPath . '/' . $page->slug . '.blade.php';

        if (File::exists($file) && !in_array($page->slug, Page::reservedSlugs())) {
            File::delete($file);
        }

        $page->delete();

        return redirect()->route('admin.pages.index')->with('success', 'Səhifə silindi.');
    }

    protected function writeStub(string $slug): void
    {
        if (!File::isDirectory($this->pagesPath)) {
            File::makeDirectory($this->pagesPath, 0755, true);
        }

        $file = $this->pagesPath . '/' . $slug . '.blade.php';

        if (File::exists($file)) {
            return;
        }

        $content = "@include('theme.rjmuseum.pages._dynamic', ['slug' => '" . $slug . "'])";
        File::put($file, $content);
    }
}
