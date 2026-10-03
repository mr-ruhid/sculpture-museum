@extends('admin.layouts.app')

@section('title', 'Səhifələr')

@section('content')

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Səhifələr</h1>
        <p class="text-sm text-slate-500 mt-1">Skan edilmiş və yaradılmış səhifələrin siyahısı</p>
    </div>
    <a href="{{ route('admin.pages.create') }}"
       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 text-white text-sm font-semibold hover:shadow-lg hover:shadow-indigo-500/30 transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
        Yeni səhifə
    </a>
</div>

@if (session('success'))
    <div class="mb-6 px-5 py-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <table class="w-full">
        <thead class="bg-slate-50 border-b border-slate-100">
            <tr>
                <th class="text-left px-6 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider">Slug</th>
                <th class="text-left px-6 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider">Başlıq</th>
                <th class="text-left px-6 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                <th class="text-right px-6 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider">Əməliyyatlar</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse ($pages as $item)
                @php
                    $model = $item->model;
                    $title = $model?->translation('az')?->title
                        ?? $model?->translation('en')?->title
                        ?? '—';
                    $frontendUrl = url('/' . app()->getLocale() . '/' . $item->slug);
                @endphp
                <tr class="hover:bg-slate-50/60 transition">
                    <td class="px-6 py-4">
                        <code class="text-xs font-mono text-slate-700 bg-slate-100 px-2 py-1 rounded">{{ $item->slug }}</code>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-sm font-medium text-slate-800">{{ $title }}</span>
                    </td>
                    <td class="px-6 py-4">
                        @if ($item->is_custom)
                            @if ($model?->is_published)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-semibold">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Aktiv
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-slate-100 text-slate-600 text-xs font-semibold">
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                    Qaralama
                                </span>
                            @endif
                        @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-blue-50 text-blue-700 text-xs font-semibold">
                                <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                Statik fayl
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-end gap-2">
                            @if ($item->is_custom)
                                <a href="{{ route('admin.pages.edit', $model) }}"
                                   title="Redaktə"
                                   class="w-9 h-9 rounded-lg bg-slate-100 hover:bg-indigo-100 text-slate-600 hover:text-indigo-600 flex items-center justify-center transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                            @else
                                <form method="POST" action="{{ route('admin.pages.store') }}">
                                    @csrf
                                    <input type="hidden" name="slug" value="{{ $item->slug }}">
                                    <input type="hidden" name="title_az" value="{{ ucfirst($item->slug) }}">
                                    <button type="submit" title="Məzmun yarat"
                                            class="w-9 h-9 rounded-lg bg-slate-100 hover:bg-emerald-100 text-slate-600 hover:text-emerald-600 flex items-center justify-center transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                                        </svg>
                                    </button>
                                </form>
                            @endif

                            <a href="{{ $frontendUrl }}" target="_blank" rel="noopener" title="Brauzerdə aç"
                               class="w-9 h-9 rounded-lg bg-slate-100 hover:bg-blue-100 text-slate-600 hover:text-blue-600 flex items-center justify-center transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                </svg>
                            </a>

                            @if ($item->is_custom)
                                <form method="POST" action="{{ route('admin.pages.destroy', $model) }}"
                                      onsubmit="return confirm('Bu səhifəni silmək istəyirsiniz?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Sil"
                                            class="w-9 h-9 rounded-lg bg-slate-100 hover:bg-red-100 text-slate-600 hover:text-red-600 flex items-center justify-center transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-6 py-16 text-center text-sm text-slate-400">
                        Heç bir səhifə tapılmadı.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
