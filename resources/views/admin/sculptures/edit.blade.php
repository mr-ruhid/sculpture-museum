@extends('admin.layouts.app')

@section('title', 'Heykəli redaktə et')

@section('content')
<form method="POST" action="{{ route('admin.sculptures.update', $sculpture) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="lg:col-span-2 space-y-6">

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-gradient-to-r from-slate-50 to-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/></svg>
                    <h3 class="font-semibold text-slate-800">Mətn məlumatları</h3>
                </div>

                <div class="border-b border-slate-100 flex overflow-x-auto bg-slate-50/50">
                    @foreach ($languages as $i => $lang)
                        <button type="button" onclick="switchTab('lang', '{{ $lang->code }}')"
                                class="lang-tab px-5 py-3 text-sm font-medium transition-all whitespace-nowrap
                                       {{ $i === 0 ? 'text-indigo-600 bg-white' : 'text-slate-500 hover:text-slate-800' }}"
                                data-lang="{{ $lang->code }}">
                            @if ($lang->flag)<img src="{{ $lang->flag }}" class="inline w-5 h-3.5 mr-1.5 rounded-sm object-cover">@endif
                            {{ $lang->name }}
                        </button>
                    @endforeach
                </div>

                <div class="p-6">
                    @foreach ($languages as $i => $lang)
                        @php $tr = $sculpture->translations->firstWhere('locale', $lang->code); @endphp
                        <div class="lang-pane {{ $i === 0 ? '' : 'hidden' }}" data-lang="{{ $lang->code }}">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                                        Ad @if ($lang->code === 'en')<span class="text-red-500">*</span>@endif
                                    </label>
                                    <input type="text" name="translations[{{ $lang->code }}][title]"
                                           value="{{ old("translations.{$lang->code}.title", $tr->title ?? '') }}"
                                           class="w-full border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Heykəltəraş</label>
                                        <input type="text" name="translations[{{ $lang->code }}][sculptor]"
                                               value="{{ old("translations.{$lang->code}.sculptor", $tr->sculptor ?? '') }}"
                                               class="w-full border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Memar</label>
                                        <input type="text" name="translations[{{ $lang->code }}][architect]"
                                               value="{{ old("translations.{$lang->code}.architect", $tr->architect ?? '') }}"
                                               class="w-full border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Material</label>
                                        <input type="text" name="translations[{{ $lang->code }}][material]"
                                               value="{{ old("translations.{$lang->code}.material", $tr->material ?? '') }}"
                                               class="w-full border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Üslub</label>
                                        <input type="text" name="translations[{{ $lang->code }}][style]"
                                               value="{{ old("translations.{$lang->code}.style", $tr->style ?? '') }}"
                                               class="w-full border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Şəhər / rayon</label>
                                        <input type="text" name="translations[{{ $lang->code }}][city]"
                                               value="{{ old("translations.{$lang->code}.city", $tr->city ?? '') }}"
                                               class="w-full border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Ünvan</label>
                                        <input type="text" name="translations[{{ $lang->code }}][address]"
                                               value="{{ old("translations.{$lang->code}.address", $tr->address ?? '') }}"
                                               class="w-full border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Qısa təsvir</label>
                                    <textarea name="translations[{{ $lang->code }}][short_description]" rows="2"
                                              class="w-full border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">{{ old("translations.{$lang->code}.short_description", $tr->short_description ?? '') }}</textarea>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Tam təsvir</label>
                                    <textarea name="translations[{{ $lang->code }}][description]" rows="5"
                                              class="w-full border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">{{ old("translations.{$lang->code}.description", $tr->description ?? '') }}</textarea>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Tarixi məlumat</label>
                                    <textarea name="translations[{{ $lang->code }}][history]" rows="4"
                                              class="w-full border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">{{ old("translations.{$lang->code}.history", $tr->history ?? '') }}</textarea>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-gradient-to-r from-slate-50 to-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <h3 class="font-semibold text-slate-800">Əsas məlumatlar</h3>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Slug</label>
                        <input type="text" name="slug" value="{{ old('slug', $sculpture->slug) }}"
                               class="w-full border border-slate-200 rounded-xl px-4 py-2.5 font-mono text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Yaranma ili</label>
                        <input type="number" name="year" value="{{ old('year', $sculpture->year) }}" min="1000" max="2100"
                               class="w-full border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Açılış tarixi</label>
                        <input type="date" name="opening_date"
                               value="{{ old('opening_date', $sculpture->opening_date?->format('Y-m-d')) }}"
                               class="w-full border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Ölçülər</label>
                        <input type="text" name="dimensions" value="{{ old('dimensions', $sculpture->dimensions) }}"
                               class="w-full border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-gradient-to-r from-slate-50 to-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <h3 class="font-semibold text-slate-800">Koordinatlar</h3>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Latitude</label>
                        <input type="text" name="latitude" value="{{ old('latitude', $sculpture->latitude) }}"
                               class="w-full border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Longitude</label>
                        <input type="text" name="longitude" value="{{ old('longitude', $sculpture->longitude) }}"
                               class="w-full border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-gradient-to-r from-slate-50 to-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <h3 class="font-semibold text-slate-800">SEO</h3>
                </div>
                <div class="border-b border-slate-100 flex overflow-x-auto bg-slate-50/50">
                    @foreach ($languages as $i => $lang)
                        <button type="button" onclick="switchTab('seo', '{{ $lang->code }}')"
                                class="seo-tab px-5 py-3 text-sm font-medium transition-all whitespace-nowrap
                                       {{ $i === 0 ? 'text-indigo-600 bg-white' : 'text-slate-500 hover:text-slate-800' }}"
                                data-lang="{{ $lang->code }}">
                            {{ $lang->name }}
                        </button>
                    @endforeach
                </div>
                <div class="p-6">
                    @foreach ($languages as $i => $lang)
                        @php $tr = $sculpture->translations->firstWhere('locale', $lang->code); @endphp
                        <div class="seo-pane {{ $i === 0 ? '' : 'hidden' }}" data-lang="{{ $lang->code }}">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Meta title</label>
                                    <input type="text" name="translations[{{ $lang->code }}][meta_title]"
                                           value="{{ old("translations.{$lang->code}.meta_title", $tr->meta_title ?? '') }}"
                                           class="w-full border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Meta description</label>
                                    <textarea name="translations[{{ $lang->code }}][meta_description]" rows="2"
                                              class="w-full border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">{{ old("translations.{$lang->code}.meta_description", $tr->meta_description ?? '') }}</textarea>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Meta keywords</label>
                                    <input type="text" name="translations[{{ $lang->code }}][meta_keywords]"
                                           value="{{ old("translations.{$lang->code}.meta_keywords", $tr->meta_keywords ?? '') }}"
                                           class="w-full border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>

        <div class="space-y-6">

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <h3 class="font-semibold text-slate-800 mb-4">Nəşr</h3>
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="is_published" value="1"
                           {{ old('is_published', $sculpture->is_published) ? 'checked' : '' }}
                           class="w-5 h-5 rounded text-indigo-600 focus:ring-indigo-500">
                    <span class="text-sm text-slate-700">Dərc edilsin</span>
                </label>
                <div class="mt-6 space-y-3">
                    <button type="submit" class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-3 rounded-xl font-semibold hover:shadow-lg hover:shadow-indigo-500/30 transition">
                        Yenilə
                    </button>
                    <a href="{{ route('admin.sculptures.index') }}" class="block text-center text-sm text-slate-500 hover:text-slate-800 py-2">
                        Ləğv et
                    </a>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100">
                    <h3 class="font-semibold text-slate-800">Status</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Vəziyyət</label>
                        <select name="condition" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                            <option value="exists" {{ old('condition', $sculpture->condition) === 'exists' ? 'selected' : '' }}>Mövcuddur</option>
                            <option value="destroyed" {{ old('condition', $sculpture->condition) === 'destroyed' ? 'selected' : '' }}>Dağıdılıb</option>
                            <option value="moved" {{ old('condition', $sculpture->condition) === 'moved' ? 'selected' : '' }}>Köçürülüb</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Dövlət qeydiyyatı</label>
                        <input type="text" name="registration_info" value="{{ old('registration_info', $sculpture->registration_info) }}"
                               class="w-full border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="font-semibold text-slate-800">Əsas şəkil</h3>
                    <span class="text-xs text-slate-400">PNG, JPG</span>
                </div>
                <div class="p-6">
                    <label for="main_image" class="block cursor-pointer">
                        <div class="w-full aspect-square rounded-xl border-2 border-dashed border-slate-200 hover:border-indigo-400 transition flex flex-col items-center justify-center bg-slate-50 overflow-hidden">
                            @if ($sculpture->main_image)
                                <img id="main_preview" src="{{ asset('storage/' . $sculpture->main_image) }}" class="w-full h-full object-cover">
                                <div id="main_placeholder" class="hidden"></div>
                            @else
                                <img id="main_preview" class="hidden w-full h-full object-cover">
                                <div id="main_placeholder" class="flex flex-col items-center text-slate-400 py-8">
                                    <svg class="w-10 h-10 mb-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span class="text-sm">Şəkil seç</span>
                                </div>
                            @endif
                        </div>
                        <input id="main_image" type="file" name="main_image" accept="image/*" class="hidden">
                    </label>
                    <p class="text-xs text-slate-400 mt-2">Yeni şəkil seçsəniz, köhnəni əvəz edəcək</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="font-semibold text-slate-800">Təsvir üçün şəkil</h3>
                    <span class="text-xs text-slate-400">PNG, JPG</span>
                </div>
                <div class="p-6">
                    <label for="description_image" class="block cursor-pointer">
                        <div class="w-full aspect-video rounded-xl border-2 border-dashed border-slate-200 hover:border-indigo-400 transition flex flex-col items-center justify-center bg-slate-50 overflow-hidden">
                            @if ($sculpture->description_image)
                                <img id="description_preview" src="{{ asset('storage/' . $sculpture->description_image) }}" class="w-full h-full object-cover">
                                <div id="description_placeholder" class="hidden"></div>
                            @else
                                <img id="description_preview" class="hidden w-full h-full object-cover">
                                <div id="description_placeholder" class="flex flex-col items-center text-slate-400 py-8">
                                    <svg class="w-10 h-10 mb-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span class="text-sm">Şəkil seç</span>
                                </div>
                            @endif
                        </div>
                        <input id="description_image" type="file" name="description_image" accept="image/*" class="hidden">
                    </label>
                    <p class="text-xs text-slate-400 mt-2">Bu şəkil "Təsvir" bölməsinin yuxarısında göstəriləcək</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="font-semibold text-slate-800">Tarixi üçün şəkil</h3>
                    <span class="text-xs text-slate-400">PNG, JPG</span>
                </div>
                <div class="p-6">
                    <label for="history_image" class="block cursor-pointer">
                        <div class="w-full aspect-video rounded-xl border-2 border-dashed border-slate-200 hover:border-indigo-400 transition flex flex-col items-center justify-center bg-slate-50 overflow-hidden">
                            @if ($sculpture->history_image)
                                <img id="history_preview" src="{{ asset('storage/' . $sculpture->history_image) }}" class="w-full h-full object-cover">
                                <div id="history_placeholder" class="hidden"></div>
                            @else
                                <img id="history_preview" class="hidden w-full h-full object-cover">
                                <div id="history_placeholder" class="flex flex-col items-center text-slate-400 py-8">
                                    <svg class="w-10 h-10 mb-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span class="text-sm">Şəkil seç</span>
                                </div>
                            @endif
                        </div>
                        <input id="history_image" type="file" name="history_image" accept="image/*" class="hidden">
                    </label>
                    <p class="text-xs text-slate-400 mt-2">Bu şəkil "Tarixi məlumat" bölməsinin yuxarısında göstəriləcək</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="font-semibold text-slate-800">Qalereya</h3>
                    <span class="text-xs text-slate-400">Çoxlu seçim</span>
                </div>
                <div class="p-6">
                    @if ($sculpture->images->count())
                        <div class="grid grid-cols-3 gap-2 mb-4">
                            @foreach ($sculpture->images as $img)
                                <div class="relative group aspect-square rounded-lg overflow-hidden border border-slate-200">
                                    <img src="{{ asset('storage/' . $img->path) }}" class="w-full h-full object-cover">
                                    <button type="button"
                                            onclick="deleteImage({{ $img->id }})"
                                            class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                                        <span class="text-white text-xs bg-red-600 hover:bg-red-700 px-3 py-1.5 rounded-lg font-medium">
                                            Sil
                                        </span>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <label for="gallery" class="block cursor-pointer">
                        <div class="w-full py-6 rounded-xl border-2 border-dashed border-slate-200 hover:border-indigo-400 transition flex flex-col items-center justify-center bg-slate-50 text-slate-400">
                            <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                            <span class="text-sm">Yeni şəkillər əlavə et</span>
                        </div>
                        <input id="gallery" type="file" name="gallery[]" accept="image/*" multiple class="hidden">
                    </label>
                    <div id="gallery_preview" class="grid grid-cols-3 gap-2 mt-4"></div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100">
                    <h3 class="font-semibold text-slate-800">360° Panorama</h3>
                </div>
                <div class="p-6">
                    <textarea name="panorama_embed" rows="4" placeholder="<iframe src=&quot;...&quot;></iframe>"
                              class="w-full border border-slate-200 rounded-xl px-4 py-2.5 font-mono text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">{{ old('panorama_embed', $sculpture->panorama_embed) }}</textarea>
                    <p class="text-xs text-slate-400 mt-2">Google Maps embed kodunu yapışdırın</p>
                </div>
            </div>

        </div>

    </div>
</form>

{{-- Gizli silmə formaları — ƏSAS FORMDAN KƏNARDA --}}
@foreach ($sculpture->images as $img)
    <form id="delete-image-{{ $img->id }}"
          action="{{ route('admin.sculptures.images.destroy', $img) }}"
          method="POST"
          class="hidden"
          onsubmit="return confirm('Bu şəkil silinsin?');">
        @csrf
        @method('DELETE')
    </form>
@endforeach

<script>
function switchTab(group, code) {
    document.querySelectorAll('.' + group + '-tab').forEach(el => {
        const on = el.dataset.lang === code;
        el.classList.toggle('text-indigo-600', on);
        el.classList.toggle('bg-white', on);
        el.classList.toggle('text-slate-500', !on);
    });
    document.querySelectorAll('.' + group + '-pane').forEach(el => {
        el.classList.toggle('hidden', el.dataset.lang !== code);
    });
}

function bindImagePreview(inputId, previewId, placeholderId) {
    const input = document.getElementById(inputId);
    if (!input) return;

    input.addEventListener('change', function (e) {
        const f = e.target.files[0];
        if (!f) return;
        const r = new FileReader();
        r.onload = ev => {
            const img = document.getElementById(previewId);
            img.src = ev.target.result;
            img.classList.remove('hidden');
            const ph = document.getElementById(placeholderId);
            if (ph) ph.classList.add('hidden');
        };
        r.readAsDataURL(f);
    });
}

bindImagePreview('main_image', 'main_preview', 'main_placeholder');
bindImagePreview('description_image', 'description_preview', 'description_placeholder');
bindImagePreview('history_image', 'history_preview', 'history_placeholder');

document.getElementById('gallery').addEventListener('change', function(e) {
    const box = document.getElementById('gallery_preview');
    box.innerHTML = '';
    Array.from(e.target.files).forEach(f => {
        const r = new FileReader();
        r.onload = ev => {
            const div = document.createElement('div');
            div.className = 'aspect-square rounded-lg overflow-hidden border border-slate-200';
            div.innerHTML = '<img src="' + ev.target.result + '" class="w-full h-full object-cover">';
            box.appendChild(div);
        };
        r.readAsDataURL(f);
    });
});

function deleteImage(id) {
    const form = document.getElementById('delete-image-' + id);
    if (form) form.submit();
}
</script>
@endsection
