@extends('admin.layouts.app')

@section('title', 'Ümumi ayarlar')

@section('content')
<form method="POST" action="{{ route('admin.settings.general.update') }}" enctype="multipart/form-data">
    @csrf

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="lg:col-span-2 space-y-6">

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-gradient-to-r from-slate-50 to-white">
                    <h3 class="font-semibold text-slate-800">Sayt məlumatları</h3>
                </div>

                <div class="border-b border-slate-100 flex overflow-x-auto bg-slate-50/50">
                    @foreach ($languages as $i => $lang)
                        <button type="button" onclick="switchTab('general', '{{ $lang->code }}')"
                                class="general-tab px-5 py-3 text-sm font-medium transition-all whitespace-nowrap
                                       {{ $i === 0 ? 'text-indigo-600 bg-white' : 'text-slate-500 hover:text-slate-800' }}"
                                data-lang="{{ $lang->code }}">
                            @if ($lang->flag)<img src="{{ $lang->flag }}" class="inline w-5 h-3.5 mr-1.5 rounded-sm object-cover">@endif
                            {{ $lang->name }}
                        </button>
                    @endforeach
                </div>

                <div class="p-6">
                    @foreach ($languages as $i => $lang)
                        <div class="general-pane {{ $i === 0 ? '' : 'hidden' }}" data-lang="{{ $lang->code }}">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Sayt adı</label>
                                    <input type="text" name="site_name_{{ $lang->code }}"
                                           value="{{ $settings['site_name_' . $lang->code] ?? '' }}"
                                           class="w-full border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Sayt təsviri</label>
                                    <textarea name="site_description_{{ $lang->code }}" rows="3"
                                              class="w-full border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">{{ $settings['site_description_' . $lang->code] ?? '' }}</textarea>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Footer mətni</label>
                                    <input type="text" name="footer_text_{{ $lang->code }}"
                                           value="{{ $settings['footer_text_' . $lang->code] ?? '' }}"
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
                    <h3 class="font-semibold text-slate-800">Logo</h3>
                </div>
                <div class="p-6">
                    @if (!empty($settings['logo']))
                        <img src="{{ asset('storage/' . $settings['logo']) }}" class="max-h-16 mb-3">
                    @endif
                    <input type="file" name="logo" accept="image/*"
                           class="w-full text-sm border border-slate-200 rounded-xl px-3 py-2">
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100">
                    <h3 class="font-semibold text-slate-800">Favicon</h3>
                </div>
                <div class="p-6">
                    @if (!empty($settings['favicon']))
                        <img src="{{ asset('storage/' . $settings['favicon']) }}" class="max-h-10 mb-3">
                    @endif
                    <input type="file" name="favicon" accept="image/*"
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
