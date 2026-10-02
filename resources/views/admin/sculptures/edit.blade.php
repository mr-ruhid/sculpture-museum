@extends('admin.layouts.app')

@section('title', 'Heykəli redaktə et')

@section('content')
<form method="POST" action="{{ route('admin.sculptures.update', $sculpture) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="bg-white rounded-lg shadow mb-4">
        <div class="border-b flex">
            @foreach ($languages as $i => $lang)
                <button type="button" onclick="switchTab('lang', '{{ $lang->code }}')"
                        class="lang-tab px-4 py-3 text-sm font-medium border-b-2 {{ $i === 0 ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-600 hover:text-gray-900' }}"
                        data-lang="{{ $lang->code }}">
                    @if ($lang->flag)<img src="{{ $lang->flag }}" class="inline w-5 h-3 mr-1">@endif
                    {{ $lang->name }}
                </button>
            @endforeach
        </div>
        <div class="p-6">
            @foreach ($languages as $i => $lang)
                @php $tr = $sculpture->translations->firstWhere('locale', $lang->code); @endphp
                <div class="lang-pane {{ $i === 0 ? '' : 'hidden' }}" data-lang="{{ $lang->code }}">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Ad {{ $lang->code === 'en' ? '(məcburi)' : '' }}</label>
                            <input type="text" name="translations[{{ $lang->code }}][title]"
                                   value="{{ old("translations.{$lang->code}.title", $tr->title ?? '') }}"
                                   class="w-full border border-gray-300 rounded px-3 py-2">
                        </div>
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Qısa təsvir</label>
                            <textarea name="translations[{{ $lang->code }}][short_description]" rows="2"
                                      class="w-full border border-gray-300 rounded px-3 py-2">{{ old("translations.{$lang->code}.short_description", $tr->short_description ?? '') }}</textarea>
                        </div>
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tam təsvir</label>
                            <textarea name="translations[{{ $lang->code }}][description]" rows="5"
                                      class="w-full border border-gray-300 rounded px-3 py-2">{{ old("translations.{$lang->code}.description", $tr->description ?? '') }}</textarea>
                        </div>
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tarixi məlumat</label>
                            <textarea name="translations[{{ $lang->code }}][history]" rows="4"
                                      class="w-full border border-gray-300 rounded px-3 py-2">{{ old("translations.{$lang->code}.history", $tr->history ?? '') }}</textarea>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="bg-white rounded-lg shadow mb-4 p-6">
        <h3 class="text-base font-semibold text-gray-800 mb-4">Əsas məlumatlar</h3>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
                <input type="text" name="slug" value="{{ old('slug', $sculpture->slug) }}"
                       class="w-full border border-gray-300 rounded px-3 py-2 font-mono text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Heykəltəraş</label>
                <input type="text" name="sculptor" value="{{ old('sculptor', $sculpture->sculptor) }}"
                       class="w-full border border-gray-300 rounded px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Memar</label>
                <input type="text" name="architect" value="{{ old('architect', $sculpture->architect) }}"
                       class="w-full border border-gray-300 rounded px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Yaranma ili</label>
                <input type="number" name="year" value="{{ old('year', $sculpture->year) }}" min="1000" max="2100"
                       class="w-full border border-gray-300 rounded px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Açılış tarixi</label>
                <input type="date" name="opening_date"
                       value="{{ old('opening_date', $sculpture->opening_date?->format('Y-m-d')) }}"
                       class="w-full border border-gray-300 rounded px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Material</label>
                <input type="text" name="material" value="{{ old('material', $sculpture->material) }}"
                       class="w-full border border-gray-300 rounded px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Ölçülər</label>
                <input type="text" name="dimensions" value="{{ old('dimensions', $sculpture->dimensions) }}"
                       class="w-full border border-gray-300 rounded px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Üslub</label>
                <input type="text" name="style" value="{{ old('style', $sculpture->style) }}"
                       class="w-full border border-gray-300 rounded px-3 py-2">
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow mb-4 p-6">
        <h3 class="text-base font-semibold text-gray-800 mb-4">Yerləşmə</h3>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Şəhər / rayon</label>
                <input type="text" name="city" value="{{ old('city', $sculpture->city) }}"
                       class="w-full border border-gray-300 rounded px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Ünvan</label>
                <input type="text" name="address" value="{{ old('address', $sculpture->address) }}"
                       class="w-full border border-gray-300 rounded px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Latitude</label>
                <input type="text" name="latitude" value="{{ old('latitude', $sculpture->latitude) }}"
                       class="w-full border border-gray-300 rounded px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Longitude</label>
                <input type="text" name="longitude" value="{{ old('longitude', $sculpture->longitude) }}"
                       class="w-full border border-gray-300 rounded px-3 py-2">
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow mb-4 p-6">
        <h3 class="text-base font-semibold text-gray-800 mb-4">Status</h3>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Vəziyyət</label>
                <select name="condition" class="w-full border border-gray-300 rounded px-3 py-2">
                    <option value="exists" {{ old('condition', $sculpture->condition) === 'exists' ? 'selected' : '' }}>Mövcuddur</option>
                    <option value="destroyed" {{ old('condition', $sculpture->condition) === 'destroyed' ? 'selected' : '' }}>Dağıdılıb</option>
                    <option value="moved" {{ old('condition', $sculpture->condition) === 'moved' ? 'selected' : '' }}>Köçürülüb</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Dövlət qeydiyyatı</label>
                <input type="text" name="registration_info" value="{{ old('registration_info', $sculpture->registration_info) }}"
                       class="w-full border border-gray-300 rounded px-3 py-2">
            </div>
            <div class="col-span-2">
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="is_published" value="1"
                           {{ old('is_published', $sculpture->is_published) ? 'checked' : '' }}>
                    <span class="text-sm text-gray-700">Dərc edilsin</span>
                </label>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow mb-4 p-6">
        <h3 class="text-base font-semibold text-gray-800 mb-4">Media</h3>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Əsas şəkil</label>
                @if ($sculpture->main_image)
                    <img src="{{ asset('storage/' . $sculpture->main_image) }}"
                         class="w-32 h-32 object-cover rounded mb-2">
                @endif
                <input type="file" name="main_image" accept="image/*"
                       class="w-full border border-gray-300 rounded px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Qalereyaya əlavə et</label>
                <input type="file" name="gallery[]" accept="image/*" multiple
                       class="w-full border border-gray-300 rounded px-3 py-2">
            </div>
            <div class="col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">360° panorama (embed kodu)</label>
                <textarea name="panorama_embed" rows="3"
                          class="w-full border border-gray-300 rounded px-3 py-2 font-mono text-xs">{{ old('panorama_embed', $sculpture->panorama_embed) }}</textarea>
            </div>
        </div>

        @if ($sculpture->images->count())
            <div class="mt-4 pt-4 border-t">
                <h4 class="text-sm font-medium text-gray-700 mb-3">Mövcud qalereya</h4>
                <div class="grid grid-cols-4 gap-3">
                    @foreach ($sculpture->images as $img)
                        <div class="relative group">
                            <img src="{{ asset('storage/' . $img->path) }}"
                                 class="w-full h-24 object-cover rounded">
                            <form action="{{ route('admin.sculptures.images.destroy', $img) }}"
                                  method="POST" class="absolute top-1 right-1 opacity-0 group-hover:opacity-100">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="bg-red-600 text-white text-xs w-6 h-6 rounded-full">×</button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <div class="bg-white rounded-lg shadow mb-4">
        <div class="border-b flex">
            @foreach ($languages as $i => $lang)
                <button type="button" onclick="switchTab('seo', '{{ $lang->code }}')"
                        class="seo-tab px-4 py-3 text-sm font-medium border-b-2 {{ $i === 0 ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-600' }}"
                        data-lang="{{ $lang->code }}">
                    SEO — {{ $lang->name }}
                </button>
            @endforeach
        </div>
        <div class="p-6">
            @foreach ($languages as $i => $lang)
                @php $tr = $sculpture->translations->firstWhere('locale', $lang->code); @endphp
                <div class="seo-pane {{ $i === 0 ? '' : 'hidden' }}" data-lang="{{ $lang->code }}">
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Meta title</label>
                            <input type="text" name="translations[{{ $lang->code }}][meta_title]"
                                   value="{{ old("translations.{$lang->code}.meta_title", $tr->meta_title ?? '') }}"
                                   class="w-full border border-gray-300 rounded px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Meta description</label>
                            <textarea name="translations[{{ $lang->code }}][meta_description]" rows="2"
                                      class="w-full border border-gray-300 rounded px-3 py-2">{{ old("translations.{$lang->code}.meta_description", $tr->meta_description ?? '') }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Meta keywords</label>
                            <input type="text" name="translations[{{ $lang->code }}][meta_keywords]"
                                   value="{{ old("translations.{$lang->code}.meta_keywords", $tr->meta_keywords ?? '') }}"
                                   class="w-full border border-gray-300 rounded px-3 py-2">
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="flex items-center gap-3 mb-8">
        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded text-sm hover:bg-blue-700">
            Yenilə
        </button>
        <a href="{{ route('admin.sculptures.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
            Ləğv et
        </a>
    </div>
</form>

<script>
function switchTab(group, code) {
    document.querySelectorAll('.' + group + '-tab').forEach(el => {
        const isActive = el.dataset.lang === code;
        el.classList.toggle('border-blue-600', isActive);
        el.classList.toggle('text-blue-600', isActive);
        el.classList.toggle('border-transparent', !isActive);
        el.classList.toggle('text-gray-600', !isActive);
    });
    document.querySelectorAll('.' + group + '-pane').forEach(el => {
        el.classList.toggle('hidden', el.dataset.lang !== code);
    });
}
</script>
@endsection
