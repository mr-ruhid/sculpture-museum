@extends('admin.layouts.app')

@section('title', 'Haqqında səhifəsi')

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
<form method="POST" action="{{ route('admin.about.update') }}" enctype="multipart/form-data">
    @csrf

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="lg:col-span-2 space-y-6">

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-gradient-to-r from-violet-50 to-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-violet-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <h3 class="font-semibold text-slate-800">Haqqında məzmunu</h3>
                </div>

                <div class="border-b border-slate-100 flex overflow-x-auto bg-slate-50/50">
                    @foreach ($languages as $i => $lang)
                        <button type="button" onclick="switchTab('about', '{{ $lang->code }}')"
                                class="about-tab px-5 py-3 text-sm font-medium transition-all whitespace-nowrap
                                       {{ $i === 0 ? 'text-indigo-600 bg-white' : 'text-slate-500 hover:text-slate-800' }}"
                                data-lang="{{ $lang->code }}">
                            @if ($lang->flag)<img src="{{ $lang->flag }}" class="inline w-5 h-3.5 mr-1.5 rounded-sm object-cover">@endif
                            {{ $lang->name }}
                        </button>
                    @endforeach
                </div>

                <div class="p-6">
                    @foreach ($languages as $i => $lang)
                        <div class="about-pane {{ $i === 0 ? '' : 'hidden' }}" data-lang="{{ $lang->code }}">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Səhifə başlığı</label>
                                    <input type="text" name="about_title_{{ $lang->code }}"
                                           value="{{ $settings['about_title_' . $lang->code] ?? '' }}"
                                           class="w-full border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Qısa təsvir</label>
                                    <textarea name="about_short_{{ $lang->code }}" rows="3"
                                              class="w-full border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">{{ $settings['about_short_' . $lang->code] ?? '' }}</textarea>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Məzmun (HTML)</label>
                                    <textarea name="about_content_{{ $lang->code }}"
                                              class="cm-editor"
                                              data-cm-lang="{{ $lang->code }}">{{ $settings['about_content_' . $lang->code] ?? '' }}</textarea>
                                    <p class="text-xs text-slate-400 mt-2">HTML teqləri dəstəklənir. Frontend-də olduğu kimi render olunacaq.</p>
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
                                    <input type="text" name="about_meta_title_{{ $lang->code }}"
                                           value="{{ $settings['about_meta_title_' . $lang->code] ?? '' }}"
                                           class="w-full border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Meta description</label>
                                    <textarea name="about_meta_description_{{ $lang->code }}" rows="2"
                                              class="w-full border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">{{ $settings['about_meta_description_' . $lang->code] ?? '' }}</textarea>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Meta keywords</label>
                                    <input type="text" name="about_meta_keywords_{{ $lang->code }}"
                                           value="{{ $settings['about_meta_keywords_' . $lang->code] ?? '' }}"
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
                <button type="submit" class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-3 rounded-xl font-semibold hover:shadow-lg hover:shadow-indigo-500/30 transition">
                    Yadda saxla
                </button>
                <a href="{{ route('admin.settings') }}" class="block text-center text-sm text-slate-500 hover:text-slate-800 py-2 mt-2">
                    Geri
                </a>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100">
                    <h3 class="font-semibold text-slate-800">Səhifə şəkli</h3>
                </div>
                <div class="p-6">
                    @if (!empty($settings['about_image']))
                        <img src="{{ asset('storage/' . $settings['about_image']) }}" class="w-full rounded-xl mb-3">
                    @endif
                    <input type="file" name="about_image" accept="image/*"
                           class="w-full text-sm border border-slate-200 rounded-xl px-3 py-2">
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
        el.classList.toggle('hidden', el.dataset.lang !== code);
    });

    document.querySelectorAll('.' + group + '-pane').forEach(pane => {
        if (pane.dataset.lang === code) {
            pane.querySelectorAll('.CodeMirror').forEach(cm => cm.CodeMirror.refresh());
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
        theme: 'default',
    });
});
</script>
@endpush
