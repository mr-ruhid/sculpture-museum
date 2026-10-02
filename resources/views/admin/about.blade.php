@extends('admin.layouts.app')

@section('title', 'Haqqında')

@section('content')
<form method="POST" action="{{ route('admin.about.update') }}" enctype="multipart/form-data">
    @csrf

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="lg:col-span-2 space-y-6">

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-gradient-to-r from-slate-50 to-white">
                    <h3 class="font-semibold text-slate-800">Məzmun</h3>
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
                                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Başlıq</label>
                                    <input type="text" name="about_title_{{ $lang->code }}"
                                           value="{{ $settings['about_title_' . $lang->code] ?? '' }}"
                                           class="w-full border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Qısa təsvir</label>
                                    <textarea name="about_short_{{ $lang->code }}" rows="2"
                                              class="w-full border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">{{ $settings['about_short_' . $lang->code] ?? '' }}</textarea>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Tam məzmun</label>
                                    <textarea name="about_content_{{ $lang->code }}" rows="10"
                                              class="w-full border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">{{ $settings['about_content_' . $lang->code] ?? '' }}</textarea>
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
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100">
                    <h3 class="font-semibold text-slate-800">Şəkil</h3>
                </div>
                <div class="p-6">
                    @if (!empty($settings['about_image']))
                        <img src="{{ asset('storage/' . $settings['about_image']) }}" class="w-full rounded-lg mb-3">
                    @endif
                    <input type="file" name="about_image" accept="image/*"
                           class="w-full text-sm border border-slate-200 rounded-xl px-3 py-2">
                </div>
            </div>

        </div>

    </div>
</form>

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
</script>
@endsection
