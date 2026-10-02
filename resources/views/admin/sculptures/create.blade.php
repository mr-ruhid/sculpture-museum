@extends('admin.layouts.app')

@section('title', 'Yeni heykəl')

@section('content')
<form method="POST" action="{{ route('admin.sculptures.store') }}" enctype="multipart/form-data">
    @csrf

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
                <div class="lang-pane {{ $i === 0 ? '' : 'hidden' }}" data-lang="{{ $lang->code }}">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Ad {{ $lang->code === 'en' ? '(məcburi)' : '' }}</label>
                            <input type="text" name="translations[{{ $lang->code }}][title]"
                                   value="{{ old("translations.{$lang->code}.title") }}"
                                   class="w-full border border-gray-300 rounded px-3 py-2">
                        </div>
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Qısa təsvir</label>
                            <textarea name="translations[{{ $lang->code }}][short_description]" rows="2"
                                      class="w-full border border-gray-300 rounded px-3 py-2">{{ old("translations.{$lang->code}.short_description") }}</textarea>
                        </div>
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tam təsvir</label>
                            <textarea name="translations[{{ $lang->code }}][description]" rows="5"
                                      class="w-full border border-gray-300 rounded px-3 py-2">{{ old("translations.{$lang->code}.description") }}</textarea>
                        </div>
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tarixi məlumat</label>
                            <textarea name="translations[{{ $lang->code }}][history]" rows="4"
                                      class="w-full border border-gray-300 rounded px-3 py-2">{{ old("translations.{$lang->code}.history") }}</textarea>
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
                <input type="text" name="slug" value="{{ old('slug') }}" placeholder="avtomatik yaranacaq"
                       class="w-full border border-gray-300 rounded px-3 py-2 font-mono text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Heykəltəraş</label>
                <input type="text" name="sculptor" value="{{ old('sculptor') }}"
                       class="w-full border border-gray-300 rounded px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Memar</label>
                <input type="text" name="architect" value="{{ old('architect') }}"
                       class="w-full border border-gray-300 rounded px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Yaranma ili</label>
                <input type="number" name="year" value="{{ old('year') }}" min="1000" max="2100"
                       class="w-full border border-gray-300 rounded px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Açılış tarixi</label>
                <input type="date" name="opening_date" value="{{ old('opening_date') }}"
                       class="w-full border border-gray-300 rounded px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Material</label>
                <input type="text" name="material" value="{{ old('material') }}"
                       class="w-full border border-gray-300 rounded px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Ölçülər</label>
                <input type="text" name="dimensions" value="{{ old('dimensions') }}"
                       class="w-full border border-gray-300 rounded px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Üslub</label>
                <input type="text" name="style" value="{{ old('style') }}"
                       class="w-full border border-gray-300 rounded px-3 py-2">
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow mb-4 p-6">
        <h3 class="text-base font-semibold text-gray-800 mb-4">Yerləşmə</h3>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Şəhər / rayon</label>
                <input type="text" name="city" value="{{ old('city') }}"
                       class="w-full border border-gray-300 rounded px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Ünvan</label>
                <input type="text" name="address" value="{{ old('address') }}"
                       class="w-full border border-gray-300 rounded px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Latitude</label>
                <input type="text" name="latitude" value="{{ old('latitude') }}"
                       class="w-full border border-gray-300 rounded px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Longitude</label>
                <input type="text" name="longitude" value="{{ old('longitude') }}"
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
                    <option value="exists" {{ old('condition') === 'exists' ? 'selected' : '' }}>Mövcuddur</option>
                    <option value="destroyed" {{ old('condition') === 'destroyed' ? 'selected' : '' }}>Dağıdılıb</option>
                    <option value="moved" {{ old('condition') === 'moved' ? 'selected' : '' }}>Köçürülüb</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Dövlət qeydiyyatı</label>
                <input type="text" name="registration_info" value="{{ old('registration_info') }}"
                       class="w-full border border-gray-300 rounded px-3 py-2">
            </div>
            <div class="col-span-2">
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="is_published" value="1" {{ old('is_published', true) ? 'checked' : '' }}>
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
                <input type="file" name="main_image" accept="image/*"
                       class="w-full border border-gray-300 rounded px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Qalereya (çoxlu)</label>
                <input type="file" name="gallery[]" accept="image/*" multiple
                       class="w-full border border-gray-300 rounded px-3 py-2">
            </div>
            <div class="col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">360° panorama (embed kodu)</label>
                <textarea name="panorama_embed" rows="3"
                          class="w-full border border-gray-300 rounded px-3 py-2 font-mono text-xs">{{ old('panorama_embed') }}</textarea>
            </div>
        </div>
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
                <div class="seo-pane {{ $i === 0 ? '' : 'hidden' }}" data-lang="{{ $lang->code }}">
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Meta title</label>
                            <input type="text" name="translations[{{ $lang->code }}][meta_title]"
                                   value="{{ old("translations.{$lang->code}.meta_title") }}"
                                   class="w-full border border-gray-300 rounded px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Meta description</label>
                            <textarea name="translations[{{ $lang->code }}][meta_description]" rows="2"
                                      class="w-full border border-gray-300 rounded px-3 py-2">{{ old("translations.{$lang->code}.meta_description") }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Meta keywords</label>
                            <input type="text" name="translations[{{ $lang->code }}][meta_keywords]"
                                   value="{{ old("translations.{$lang->code}.meta_keywords") }}"
                                   class="w-full border border-gray-300 rounded px-3 py-2">
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="flex items-center gap-3 mb-8">
        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded text-sm hover:bg-blue-700">
            Yadda saxla
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
