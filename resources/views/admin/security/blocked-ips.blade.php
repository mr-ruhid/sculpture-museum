@extends('admin.layouts.app')

@section('title', 'Bloklanmış IP-lər')

@section('content')

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Təhlükəsizlik — Giriş cəhdləri</h1>
        <p class="text-sm text-slate-500 mt-1">5 uğursuz cəhddən sonra IP 1 saat bloklanır</p>
    </div>
    @if ($attempts->total() > 0)
        <form method="POST" action="{{ route('admin.security.clearAll') }}"
              onsubmit="return confirm('Bütün cəhd qeydləri silinsin? Blokdaki IP-lər də açılacaq.');">
            @csrf
            @method('DELETE')
            <button type="submit"
                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-red-50 text-red-700 text-sm font-semibold hover:bg-red-100 transition border border-red-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                Hamısını təmizlə
            </button>
        </form>
    @endif
</div>

@if (session('success'))
    <div class="mb-6 px-5 py-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm">
        {{ session('success') }}
    </div>
@endif

<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
        <div class="flex items-center gap-3">
            <div class="w-11 h-11 rounded-xl bg-slate-100 flex items-center justify-center">
                <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/>
                </svg>
            </div>
            <div>
                <div class="text-xs text-slate-500 uppercase tracking-wider font-semibold">Ümumi qeyd</div>
                <div class="text-2xl font-black text-slate-900">{{ $stats['total'] }}</div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
        <div class="flex items-center gap-3">
            <div class="w-11 h-11 rounded-xl bg-red-100 flex items-center justify-center">
                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>
            <div>
                <div class="text-xs text-slate-500 uppercase tracking-wider font-semibold">Blokda</div>
                <div class="text-2xl font-black text-red-600">{{ $stats['blocked'] }}</div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
        <div class="flex items-center gap-3">
            <div class="w-11 h-11 rounded-xl bg-amber-100 flex items-center justify-center">
                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <div class="text-xs text-slate-500 uppercase tracking-wider font-semibold">Bugün</div>
                <div class="text-2xl font-black text-amber-600">{{ $stats['today'] }}</div>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <table class="w-full">
        <thead class="bg-slate-50 border-b border-slate-100">
            <tr>
                <th class="text-left px-5 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider">IP ünvanı</th>
                <th class="text-left px-5 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider">Cəhd</th>
                <th class="text-left px-5 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider">Son cəhd</th>
                <th class="text-left px-5 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                <th class="text-left px-5 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider">Brauzer</th>
                <th class="text-right px-5 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider">Əməliyyat</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse ($attempts as $attempt)
                <tr class="hover:bg-slate-50/60 transition">
                    <td class="px-5 py-4">
                        <code class="text-sm font-mono text-slate-800 bg-slate-100 px-2 py-1 rounded">{{ $attempt->ip }}</code>
                    </td>
                    <td class="px-5 py-4">
                        @if ($attempt->attempts >= 5)
                            <span class="text-sm font-bold text-red-600">{{ $attempt->attempts }}</span>
                        @else
                            <span class="text-sm font-semibold text-slate-700">{{ $attempt->attempts }}</span>
                        @endif
                        <span class="text-xs text-slate-400 ml-1">/ 5</span>
                    </td>
                    <td class="px-5 py-4">
                        <span class="text-xs text-slate-600">
                            {{ $attempt->last_attempt_at ? $attempt->last_attempt_at->diffForHumans() : '—' }}
                        </span>
                    </td>
                    <td class="px-5 py-4">
                        @if ($attempt->isBlocked())
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-red-50 text-red-700 text-xs font-semibold">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></span>
                                Blokda ({{ $attempt->remainingMinutes() }} dəq)
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-slate-100 text-slate-600 text-xs font-semibold">
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                Aktiv deyil
                            </span>
                        @endif
                    </td>
                    <td class="px-5 py-4 max-w-xs">
                        <span class="text-xs text-slate-500 line-clamp-1" title="{{ $attempt->user_agent }}">
                            {{ $attempt->user_agent ?? '—' }}
                        </span>
                    </td>
                    <td class="px-5 py-4 text-right">
                        <form method="POST" action="{{ route('admin.security.unblock', $attempt) }}"
                              onsubmit="return confirm('Bu IP-ni blokdan çıxarmaq istəyirsiniz?');"
                              class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-emerald-100 text-slate-600 hover:text-emerald-700 text-xs font-semibold transition">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/>
                                </svg>
                                Blokdan çıxar
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-16 text-center">
                        <div class="flex flex-col items-center gap-3">
                            <div class="w-14 h-14 rounded-full bg-emerald-50 flex items-center justify-center">
                                <svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="text-sm text-slate-500">
                                Heç bir uğursuz giriş cəhdi yoxdur. Hər şey qaydasındadır. ✓
                            </div>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if ($attempts->hasPages())
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $attempts->links() }}
        </div>
    @endif
</div>

@endsection
