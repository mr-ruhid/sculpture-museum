<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sculpture;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SculptureController extends Controller
{
    public function index()
    {
        $sculptures = Sculpture::latest()->paginate(15);
        return view('admin.sculptures.index', compact('sculptures'));
    }

    public function create()
    {
        return view('admin.sculptures.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'sculptor' => ['nullable', 'string', 'max:255'],
            'year' => ['nullable', 'integer', 'min:1000', 'max:2100'],
            'material' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'max:4096'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('sculptures', 'public');
        }

        $data['slug'] = Str::slug($data['title']);
        $data['is_published'] = $request->boolean('is_published');

        Sculpture::create($data);

        return redirect()->route('admin.sculptures.index');
    }

    public function show(Sculpture $sculpture)
    {
        return view('admin.sculptures.show', compact('sculpture'));
    }

    public function edit(Sculpture $sculpture)
    {
        return view('admin.sculptures.edit', compact('sculpture'));
    }

    public function update(Request $request, Sculpture $sculpture)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'sculptor' => ['nullable', 'string', 'max:255'],
            'year' => ['nullable', 'integer', 'min:1000', 'max:2100'],
            'material' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'max:4096'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('sculptures', 'public');
        }

        $data['slug'] = Str::slug($data['title']);
        $data['is_published'] = $request->boolean('is_published');

        $sculpture->update($data);

        return redirect()->route('admin.sculptures.index');
    }

    public function destroy(Sculpture $sculpture)
    {
        $sculpture->delete();
        return redirect()->route('admin.sculptures.index');
    }
}
