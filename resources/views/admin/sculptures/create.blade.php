@extends('admin.layouts.app')

@section('title', 'Yeni səhifə')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/codemirror@5.65.16/lib/codemirror.min.css">
<style>
    .CodeMirror {
        height: 420px;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        font-size: 13px;
        font-family: ui-monospace, SFMono-Regular, Menlo, monospace;
    }
    .CodeMirror-focused {
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99,102,241,0.15);
    }
</style>
@endpush

@section('content')

<form method="POST" action="{{ route('admin.pages.store') }}">
    @csrf

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="lg:col-span-2 space-y-6">

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-gradient-to-r from-indigo-50 to-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <h3 class="font-semibold text-slate-800">Səhifə məzmunu</h3>
                </div>

                <div class="border-b border-slate-100 flex overflow-x-auto bg-slate-50/50">
                    @foreach ($languages as $i => $lang)
                        <button type="button" onclick="switchTab('page', '{{ $lang->code }}')"
                                class="page-tab px-5 py-3 text-sm font-medium transition-all whitespace-nowrap
                                       {{ $i === 0 ? 'text-indigo-600 bg-white' : 'text-slate-500 hover:text-slate-800' }}"
                                data-lang="{{ $lang->code }}">
                            @if ($lang->flag)<img src="{{ $lang->flag }}" class="inline w-5 h-3.5 mr-1.5 rounded-sm object-cover">@endif
                            {{ $lang->name }}
                        </button>
                    @endforeach
                </div>

                <div class="p-6">
                    @foreach ($languages as $i => $lang)
                        <div class="page-pane {{ $i === 0 ? '' : 'hidden' }}" data-lang="{{ $lang->code }}">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                                        Başlıq @if ($lang->code === 'en')<span class="text-red-500">*</span>@endif
                                    </label>
                                    <input type="text"
                                           name="title_{{ $lang->code }}"
                                           value="{{ old('title_' . $lang->code) }}"
                                           data-title-input="{{ $lang->code }}"
                                           class="w-full border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                                    @error('title_' . $lang->code)
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Məzmun (HTML)</label>
                                    <textarea name="content_{{ $lang->code }}"
                                              class="cm-editor">{{ old('content_' . $lang->code) }}</textarea>
                                    <p class="text-xs text-slate-400 mt-2">HTML teqləri frontend-də olduğu kimi render olunacaq.</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-gradient-to-r from-blue-50 to-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
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
                        <div class="seo-pane {{ $i === 0 ? '' : 'hidden' }}" data-lang="{{ $lang->code }}">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Meta title</label>
                                    <input type="text" name="meta_title_{{ $lang->code }}"
                                           value="{{ old('meta_title_' . $lang->code) }}"
                                           class="w-full border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Meta description</label>
                                    <textarea name="meta_description_{{ $lang->code }}" rows="2"
                                              class="w-full border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">{{ old('meta_description_' . $lang->code) }}</textarea>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Meta keywords</label>
                                    <input type="text" name="meta_keywords_{{ $lang->code }}"
                                           value="{{ old('meta_keywords_' . $lang->code) }}"
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
                <button type="submit"
                        class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-3 rounded-xl font-semibold hover:shadow-lg hover:shadow-indigo-500/30 transition">
                    Yadda saxla
                </button>
                <a href="{{ route('admin.pages.index') }}"
                   class="block text-center text-sm text-slate-500 hover:text-slate-800 py-2 mt-2">
                    Ləğv et
                </a>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100">
                    <h3 class="font-semibold text-slate-800">Slug</h3>
                </div>
                <div class="p-6">
                    <input type="text" id="slug-input" name="slug" value="{{ old('slug') }}" placeholder="haqqimizda"
                           class="w-full border border-slate-200 rounded-xl px-4 py-2.5 font-mono text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                    <p class="text-xs text-slate-400 mt-2">Yalnız kiçik hərf, rəqəm və defis. URL-də görünəcək.</p>
                    @error('slug')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100">
                    <h3 class="font-semibold text-slate-800">Status</h3>
                </div>
                <div class="p-6">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_published" value="1" {{ old('is_published', true) ? 'checked' : '' }}
                               class="w-5 h-5 rounded text-indigo-600 focus:ring-indigo-500">
                        <span class="text-sm text-slate-700">Dərc edilsin</span>
                    </label>
                </div>
            </div>

        </div>

    </div>
</form>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/codemirror@5.65.16/lib/codemirror.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/codemirror@5.65.16/mode/xml/xml.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/codemirror@5.65.16/mode/javascript/javascript.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/codemirror@5.65.16/mode/css/css.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/codemirror@5.65.16/mode/htmlmixed/htmlmixed.min.js"></script>
<script>
function switchTab(group, code) {
    document.querySelectorAll('.' + group + '-tab').forEach(el => {
        const on = el.dataset.lang === code;
        el.classList.toggle('text-indigo-600', on);
        el.classList.toggle('bg-white', on);
        el.classList.toggle('text-slate-500', !on);
    });
    document.querySelectorAll('.' + group + '-pane').forEach(el => {
        const isOpen = el.dataset.lang === code;
        el.classList.toggle('hidden', !isOpen);
        if (isOpen) {
            el.querySelectorAll('.CodeMirror').forEach(cm => cm.CodeMirror.refresh());
        }
    });
}

document.querySelectorAll('.cm-editor').forEach(el => {
    CodeMirror.fromTextArea(el, {
        mode: 'htmlmixed',
        lineNumbers: true,
        lineWrapping: true,
        indentUnit: 4,
        tabSize: 4,
    });
});

const slugInput = document.getElementById('slug-input');
const titleInputs = document.querySelectorAll('[data-title-input]');
let slugTouched = slugInput.value.length > 0;

function slugify(str) {
    const map = {
        'ə': 'e', 'ı': 'i', 'ö': 'o', 'ü': 'u', 'ç': 'c', 'ş': 's', 'ğ': 'g',
        'Ə': 'e', 'I': 'i', 'İ': 'i', 'Ö': 'o', 'Ü': 'u', 'Ç': 'c', 'Ş': 's', 'Ğ': 'g',
        'а': 'a', 'б': 'b', 'в': 'v', 'г': 'g', 'д': 'd', 'е': 'e', 'ё': 'e', 'ж': 'zh',
        'з': 'z', 'и': 'i', 'й': 'y', 'к': 'k', 'л': 'l', 'м': 'm', 'н': 'n', 'о': 'o',
        'п': 'p', 'р': 'r', 'с': 's', 'т': 't', 'у': 'u', 'ф': 'f', 'х': 'h', 'ц': 'ts',
        'ч': 'ch', 'ш': 'sh', 'щ': 'shch', 'ъ': '', 'ы': 'y', 'ь': '', 'э': 'e', 'ю': 'yu', 'я': 'ya',
    };
    return str
        .split('')
        .map(c => map[c] !== undefined ? map[c] : c)
        .join('')
        .toLowerCase()
        .replace(/[^a-z0-9]+/g, '-')
        .replace(/^-+|-+$/g, '')
        .slice(0, 100);
}

slugInput.addEventListener('input', function () {
    slugTouched = true;
    const cursor = this.selectionStart;
    this.value = this.value.toLowerCase().replace(/[^a-z0-9\-]/g, '');
});

titleInputs.forEach(input => {
    input.addEventListener('input', function () {
        if (slugTouched) return;
        const azInput = document.querySelector('[data-title-input="az"]');
        const enInput = document.querySelector('[data-title-input="en"]');
        const source = (azInput && azInput.value) ? azInput : enInput;
        if (source) slugInput.value = slugify(source.value);
    });
});
</script>
@endpush
