@extends('admin.layouts.app')

@section('title', 'Qısa URL-lər')

@section('content')

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Qısa URL-lər</h1>
        <p class="text-sm text-slate-500 mt-1">QR kodlar üçün sabit linklər</p>
    </div>
    <a href="{{ route('admin.short-urls.create') }}"
       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 text-white text-sm font-semibold hover:shadow-lg hover:shadow-indigo-500/30 transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
        Yeni qısa URL
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
                <th class="text-left px-6 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider">Kod</th>
                <th class="text-left px-6 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider">Növ</th>
                <th class="text-left px-6 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider">Hədəf</th>
                <th class="text-left px-6 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider">Statistika</th>
                <th class="text-left px-6 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                <th class="text-right px-6 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider">Əməliyyatlar</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse ($shortUrls as $shortUrl)
                @php
                    $typeLabels = [
                        'sculpture' => 'Heykəl',
                        'page' => 'Səhifə',
                        'sculptures_pair' => 'Cüt heykəl',
                        'custom' => 'Custom URL',
                    ];
                    $typeColors = [
                        'sculpture' => 'bg-emerald-50 text-emerald-700',
                        'page' => 'bg-blue-50 text-blue-700',
                        'sculptures_pair' => 'bg-violet-50 text-violet-700',
                        'custom' => 'bg-amber-50 text-amber-700',
                    ];

                    $targetLabel = '—';
                    if ($shortUrl->target_type === 'sculpture' && $shortUrl->target_id) {
                        $s = \App\Models\Sculpture::find($shortUrl->target_id);
                        $targetLabel = $s ? ($s->translation()?->title ?? $s->slug) : '—';
                    } elseif ($shortUrl->target_type === 'page' && $shortUrl->target_id) {
                        $p = \App\Models\Page::find($shortUrl->target_id);
                        $targetLabel = $p ? $p->slug : '—';
                    } elseif ($shortUrl->target_type === 'sculptures_pair') {
                        $slugs = $shortUrl->target_params['slugs'] ?? [];
                        $targetLabel = count($slugs) ? implode(' + ', $slugs) : '—';
                    } elseif ($shortUrl->target_type === 'custom') {
                        $targetLabel = $shortUrl->target_params['url'] ?? '—';
                    }
                @endphp
                <tr class="hover:bg-slate-50/60 transition">
                    <td class="px-6 py-4">
                        <code class="text-xs font-mono text-slate-800 bg-slate-100 px-2 py-1 rounded">{{ $shortUrl->code }}</code>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $typeColors[$shortUrl->target_type] ?? 'bg-slate-100 text-slate-700' }}">
                            {{ $typeLabels[$shortUrl->target_type] ?? $shortUrl->target_type }}
                        </span>
                    </td>
                    <td class="px-6 py-4 max-w-md">
                        <span class="text-sm text-slate-700 truncate block">{{ $targetLabel }}</span>
                        @if ($shortUrl->note)
                            <span class="text-xs text-slate-400 block mt-0.5">{{ $shortUrl->note }}</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-sm font-semibold text-slate-700">{{ $shortUrl->hits }}</span>
                        <span class="text-xs text-slate-400 ml-1">dəfə</span>
                    </td>
                    <td class="px-6 py-4">
                        @if ($shortUrl->is_active)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-semibold">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                Aktiv
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-slate-100 text-slate-600 text-xs font-semibold">
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                Deaktiv
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ url('/q/' . $shortUrl->code) }}" target="_blank" rel="noopener" title="Brauzerdə aç"
                               class="w-9 h-9 rounded-lg bg-slate-100 hover:bg-blue-100 text-slate-600 hover:text-blue-600 flex items-center justify-center transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                </svg>
                            </a>
                            <button type="button"
                                    onclick="copyShortUrl('{{ url('/q/' . $shortUrl->code) }}', this)"
                                    title="Kopyala"
                                    class="w-9 h-9 rounded-lg bg-slate-100 hover:bg-emerald-100 text-slate-600 hover:text-emerald-600 flex items-center justify-center transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                            </button>
                            <a href="{{ route('admin.short-urls.edit', $shortUrl) }}" title="Redaktə"
                               class="w-9 h-9 rounded-lg bg-slate-100 hover:bg-indigo-100 text-slate-600 hover:text-indigo-600 flex items-center justify-center transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            <form method="POST" action="{{ route('admin.short-urls.destroy', $shortUrl) }}"
                                  onsubmit="return confirm('Bu qısa URL silinsin?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" title="Sil"
                                        class="w-9 h-9 rounded-lg bg-slate-100 hover:bg-red-100 text-slate-600 hover:text-red-600 flex items-center justify-center transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-16 text-center text-sm text-slate-400">
                        Hələ qısa URL yaradılmayıb.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if ($shortUrls->hasPages())
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $shortUrls->links() }}
        </div>
    @endif
</div>

<script>
function copyShortUrl(url, btn) {
    navigator.clipboard.writeText(url).then(() => {
        const original = btn.innerHTML;
        btn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>';
        btn.classList.add('bg-emerald-100', 'text-emerald-600');
        setTimeout(() => {
            btn.innerHTML = original;
            btn.classList.remove('bg-emerald-100', 'text-emerald-600');
        }, 1500);
    });
}
</script>

@endsection
