@extends('admin.layouts.app')

@section('title', 'Ana səhifə')

@section('content')

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
            <div class="w-10 h-10 rounded-lg bg-indigo-50 flex items-center justify-center mb-3">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 21v-7m0 0V9a2 2 0 012-2h2m-4 6h4m12 8v-7m0 0V9a2 2 0 00-2-2h-2m4 6h-4M12 3v18"/></svg>
            </div>
            <div class="text-2xl font-bold text-slate-800">{{ $stats['total'] }}</div>
            <div class="text-xs text-slate-500 mt-1">Ümumi heykəl</div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
            <div class="w-10 h-10 rounded-lg bg-emerald-50 flex items-center justify-center mb-3">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            </div>
            <div class="text-2xl font-bold text-slate-800">{{ $stats['published'] }}</div>
            <div class="text-xs text-slate-500 mt-1">Dərc edilib</div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
            <div class="w-10 h-10 rounded-lg bg-amber-50 flex items-center justify-center mb-3">
                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div class="text-2xl font-bold text-slate-800">{{ $stats['draft'] }}</div>
            <div class="text-xs text-slate-500 mt-1">Qaralama</div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
            <div class="w-10 h-10 rounded-lg bg-purple-50 flex items-center justify-center mb-3">
                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <div class="text-2xl font-bold text-slate-800">{{ $stats['cities'] }}</div>
            <div class="text-xs text-slate-500 mt-1">Şəhər</div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
            <div class="w-10 h-10 rounded-lg bg-cyan-50 flex items-center justify-center mb-3">
                <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div class="text-2xl font-bold text-slate-800">{{ $stats['languages'] }}</div>
            <div class="text-xs text-slate-500 mt-1">Aktiv dil</div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
            <div class="w-10 h-10 rounded-lg bg-pink-50 flex items-center justify-center mb-3">
                <svg class="w-5 h-5 text-pink-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <div class="text-2xl font-bold text-slate-800">{{ $stats['images'] }}</div>
            <div class="text-xs text-slate-500 mt-1">Qalereya şəkli</div>
        </div>

    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h3 class="font-semibold text-slate-800">Son əlavə edilənlər</h3>
                <a href="{{ route('admin.wikis.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                    Hamısına bax →
                </a>
            </div>

            @if ($recent->count())
                <table class="w-full text-sm">
                    <tbody>
                        @foreach ($recent as $item)
                            <tr class="border-b border-slate-50 last:border-0 hover:bg-slate-50/50">
                                <td class="py-3 pl-6 w-16">
                                    @if ($item->main_image)
                                        <img src="{{ asset('storage/' . $item->main_image) }}" class="w-10 h-10 rounded-lg object-cover">
                                    @else
                                        <div class="w-10 h-10 rounded-lg bg-slate-100"></div>
                                    @endif
                                </td>
                                <td class="py-3">
                                    <div class="font-medium text-slate-800">
                                        {{ $item->translation('en')?->title ?? '—' }}
                                    </div>
                                    <div class="text-xs text-slate-400">
                                        {{ $item->translation('en')?->sculptor ?? '' }}
                                        @if ($item->year) · {{ $item->year }} @endif
                                    </div>
                                </td>
                                <td class="py-3 text-right pr-6">
                                    @if ($item->is_published)
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-xs bg-emerald-50 text-emerald-700">Dərc</span>
                                    @else
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-xs bg-slate-100 text-slate-500">Qaralama</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="p-8 text-center text-slate-400 text-sm">
                    Hələ heykəl əlavə edilməyib.
                </div>
            @endif
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100">
                <h3 class="font-semibold text-slate-800">Şəhərlər üzrə</h3>
            </div>
            <div class="p-6 space-y-3">
                @forelse ($byCity as $city => $count)
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-slate-700">{{ $city }}</span>
                        <span class="text-sm font-semibold text-slate-800">{{ $count }}</span>
                    </div>
                @empty
                    <div class="text-center text-slate-400 text-sm py-4">
                        Məlumat yoxdur
                    </div>
                @endforelse
            </div>
        </div>

    </div>

@endsection
