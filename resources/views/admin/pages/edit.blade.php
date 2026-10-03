@extends('admin.layouts.app')

@section('title', 'Səhifəni redaktə et')

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

<form method="POST" action="{{ route('admin.pages.update', $page->slug) }}">
    @csrf
    @method('PUT')

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="lg:col-span-2 space-y-6">

            @if (!$is_static)
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
                            @php $tr = $translations->get($lang->code); @endphp
                            <div class="page-pane {{ $i === 0 ? '' : 'hidden' }}" data-lang="{{ $lang->code }}">
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Başlıq</label>
                                        <input type="text" name="title_{{ $lang->code }}"
                                               value="{{ old('title_' . $lang->code, $tr?->title) }}"
                                               class="w-full border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Məzmun (HTML)</label>
                                        <textarea name="content_{{ $lang->code }}"
                                                  class="cm-editor">{{ old('content_' . $lang->code, $tr?->content) }}</textarea>
                                        <p class="text-xs text-slate-400 mt-2">HTML teqləri frontend-də olduğu kimi render olunacaq.</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="bg-violet-50 border border-violet-200 rounded-2xl p-5 flex gap-3">
                    <svg class="w-5 h-5 text-violet-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div class="text-sm text-violet-900">
                        <p class="font-semibold mb-1">Statik səhifə</p>
                        <p class="text-violet-700">Bu səhifənin məzmunu birbaşa <code class="bg-violet-100 px-1.5 py-0.5 rounded font-mono text-xs">{{ $page->slug }}.blade.php</code> faylında saxlanılır. Burada yalnız SEO məlumatlarını redaktə edə bilərsiniz.</p>
                    </div>
                </div>
            @endif

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
                        @php $tr = $translations->get($lang->code); @endphp
                        <div class="seo-pane {{ $i === 0 ? '' : 'hidden' }}" data-lang="{{ $lang->code }}">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Meta title</label>
                                    <input type="text" name="meta_title_{{ $lang->code }}"
                                           value="{{ old('meta_title_' . $lang->code, $tr?->meta_title) }}"
                                           class="w-full border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Meta description</label>
                                    <textarea name="meta_description_{{ $lang->code }}" rows="2"
                                              class="w-full border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">{{ old('meta_description_' . $lang->code, $tr?->meta_description) }}</textarea>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Meta keywords</label>
                                    <input type="text" name="meta_keywords_{{ $lang->code }}"
                                           value="{{ old('meta_keywords_' . $lang->code, $tr?->meta_keywords) }}"
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
                <a href="{{ $page->slug === 'home' ? url('/' . app()->getLocale()) : url('/' . app()->getLocale() . '/' . $page->slug) }}"
                   target="_blank" rel="noopener"
                   class="w-full mt-3 inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-slate-100 text-slate-700 text-sm font-semibold hover:bg-blue-100 hover:text-blue-700 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                    Brauzerdə aç
                </a>
                <a href="{{ route('admin.pages.index') }}"
                   class="block text-center text-sm text-slate-500 hover:text-slate-800 py-2 mt-2">
                    Geri
                </a>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100">
                    <h3 class="font-semibold text-slate-800">Slug</h3>
                </div>
                <div class="p-6">
                    <code class="block w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl font-mono text-sm text-slate-700">
                        {{ $page->slug }}
                    </code>
                    <p class="text-xs text-slate-400 mt-2">Slug dəyişdirilə bilməz.</p>
                </div>
            </div>

            @if (!$is_static)
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100">
                        <h3 class="font-semibold text-slate-800">Status</h3>
                    </div>
                    <div class="p-6">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="is_published" value="1"
                                   {{ old('is_published', $page->is_published) ? 'checked' : '' }}
                                   class="w-5 h-5 rounded text-indigo-600 focus:ring-indigo-500">
                            <span class="text-sm text-slate-700">Dərc edilsin</span>
                        </label>
                    </div>
                </div>
            @endif

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
</script>
@endpush
