@extends('admin.layouts.app')

@section('title', 'Qısa URL-lər')

@section('content')

<div class="mb-6">
    <h1 class="text-2xl font-bold text-slate-800">Qısa URL-lər</h1>
    <p class="text-sm text-slate-500 mt-1">QR kodlar üçün sabit linklər — <code class="bg-slate-100 px-1.5 py-0.5 rounded text-xs font-mono">/q/{kod}</code></p>
</div>

@if (session('success'))
    <div class="mb-6 px-5 py-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm">
        {{ session('success') }}
    </div>
@endif

@if ($errors->any())
    <div class="mb-6 px-5 py-3 rounded-xl bg-red-50 border border-red-200 text-red-800 text-sm">
        <ul class="list-disc list-inside">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <table class="w-full">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="text-left px-5 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider">Kod</th>
                        <th class="text-left px-5 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider">Növ</th>
                        <th class="text-left px-5 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider">Hədəf</th>
                        <th class="text-left px-5 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider">Hits</th>
                        <th class="text-left px-5 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="text-right px-5 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider">Əməliyyatlar</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($shortUrls as $shortUrl)
                        @php
                            $typeLabels = [
                                'sculpture' => 'Heykəl',
                                'page' => 'Səhifə',
                                'sculptures_pair' => 'Cüt heykəl',
                            ];
                            $typeColors = [
                                'sculpture' => 'bg-emerald-50 text-emerald-700',
                                'page' => 'bg-blue-50 text-blue-700',
                                'sculptures_pair' => 'bg-violet-50 text-violet-700',
                            ];

                            $targetLabel = '—';
                            if ($shortUrl->target_type === 'sculpture' && $shortUrl->target_id) {
                                $s = \App\Models\Sculpture::find($shortUrl->target_id);
                                $targetLabel = $s ? ($s->translation()?->title ?? $s->slug) : '—';
                            } elseif ($shortUrl->target_type === 'page' && $shortUrl->target_id) {
                                $p = \App\Models\Page::find($shortUrl->target_id);
                                $targetLabel = $p ? $p->slug : '—';
                            } elseif ($shortUrl->target_type === 'sculptures_pair') {
                                $ids = $shortUrl->target_params['ids'] ?? [];
                                $items = \App\Models\Sculpture::whereIn('id', $ids)->get()->keyBy('id');
                                $names = collect($ids)->map(fn($id) => $items[$id]?->translation()?->title ?? '—')->toArray();
                                $targetLabel = implode(' + ', $names) ?: '—';
                            }
                        @endphp
                        <tr class="hover:bg-slate-50/60 transition">
                            <td class="px-5 py-4">
                                <code class="text-xs font-mono text-slate-800 bg-slate-100 px-2 py-1 rounded">{{ $shortUrl->code }}</code>
                            </td>
                            <td class="px-5 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $typeColors[$shortUrl->target_type] ?? 'bg-slate-100' }}">
                                    {{ $typeLabels[$shortUrl->target_type] ?? $shortUrl->target_type }}
                                </span>
                            </td>
                            <td class="px-5 py-4 max-w-xs">
                                <span class="text-sm text-slate-700 block truncate" title="{{ $targetLabel }}">{{ $targetLabel }}</span>
                                @if ($shortUrl->note)
                                    <span class="text-xs text-slate-400 block mt-0.5 truncate">{{ $shortUrl->note }}</span>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                <span class="text-sm font-semibold text-slate-700">{{ $shortUrl->hits }}</span>
                            </td>
                            <td class="px-5 py-4">
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
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ url('/q/' . $shortUrl->code) }}" target="_blank" rel="noopener" title="Aç"
                                       class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-blue-100 text-slate-600 hover:text-blue-600 flex items-center justify-center transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                    </a>
                                    <button type="button"
                                            onclick="copyShortUrl('{{ url('/q/' . $shortUrl->code) }}', this)"
                                            title="Kopyala"
                                            class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-emerald-100 text-slate-600 hover:text-emerald-600 flex items-center justify-center transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                    </button>
                                    <button type="button"
                                            onclick='openEditModal(@json([
                                                "id" => $shortUrl->id,
                                                "code" => $shortUrl->code,
                                                "target_type" => $shortUrl->target_type,
                                                "target_id" => $shortUrl->target_id,
                                                "target_params" => $shortUrl->target_params,
                                                "note" => $shortUrl->note,
                                                "is_active" => $shortUrl->is_active,
                                            ]))'
                                            title="Redaktə"
                                            class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-indigo-100 text-slate-600 hover:text-indigo-600 flex items-center justify-center transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    <form method="POST" action="{{ route('admin.short-urls.destroy', $shortUrl) }}"
                                          onsubmit="return confirm('Bu qısa URL silinsin?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Sil"
                                                class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-red-100 text-slate-600 hover:text-red-600 flex items-center justify-center transition">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
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
    </div>

    <div>
        <form method="POST" action="{{ route('admin.short-urls.store') }}"
              class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden sticky top-24"
              id="create-form">
            @csrf

            <div class="px-5 py-4 border-b border-slate-100 bg-gradient-to-r from-indigo-50 to-white flex items-center gap-2">
                <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                <h3 class="font-semibold text-slate-800 text-sm">Yeni qısa URL</h3>
            </div>

            <div class="p-5 space-y-4">

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Kod</label>
                    <input type="text" name="code" value="{{ old('code') }}" placeholder="p-001"
                           class="w-full border border-slate-200 rounded-xl px-3 py-2 font-mono text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                    <p class="text-[11px] text-slate-400 mt-1">Yalnız kiçik hərf, rəqəm, defis</p>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Növ</label>
                    <div class="grid grid-cols-3 gap-2">
                        @foreach (['sculpture' => 'Heykəl', 'page' => 'Səhifə', 'sculptures_pair' => 'Cüt'] as $type => $label)
                            <label class="cursor-pointer">
                                <input type="radio" name="target_type" value="{{ $type }}"
                                       class="hidden create-type-radio"
                                       {{ old('target_type', 'sculpture') === $type ? 'checked' : '' }}>
                                <div class="border-2 border-slate-200 rounded-xl px-2 py-2 text-center text-xs font-semibold text-slate-600 transition"
                                     data-create-type-btn="{{ $type }}">
                                    {{ $label }}
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div data-create-pane="sculpture" class="create-type-pane">
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Heykəl</label>
                    <select data-create-select="sculpture"
                            class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                        <option value="">— Seçin —</option>
                        @foreach ($sculptures as $s)
                            <option value="{{ $s->id }}">{{ $s->translation()?->title ?? $s->slug }}</option>
                        @endforeach
                    </select>
                </div>

                <div data-create-pane="page" class="create-type-pane hidden">
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Səhifə</label>
                    <select data-create-select="page"
                            class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                        <option value="">— Seçin —</option>
                        @foreach ($pages as $p)
                            <option value="{{ $p->id }}">{{ $p->slug }}</option>
                        @endforeach
                    </select>
                </div>

                <div data-create-pane="sculptures_pair" class="create-type-pane hidden">
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Heykəllər <span class="text-slate-400 font-normal">(Ctrl ilə çoxlu)</span>
                    </label>
                    <select data-create-select="pair" multiple size="6"
                            class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                        @foreach ($sculptures as $s)
                            <option value="{{ $s->id }}">{{ $s->translation()?->title ?? $s->slug }}</option>
                        @endforeach
                    </select>
                </div>

                <input type="hidden" name="target_id" id="create-target-id">
                <input type="hidden" name="sculpture_ids_present" value="1">

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Qeyd</label>
                    <input type="text" name="note" value="{{ old('note') }}" placeholder="daxili qeyd"
                           class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                </div>

                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                           class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500">
                    <span class="text-sm text-slate-700">Aktiv</span>
                </label>

                <button type="submit"
                        class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-2.5 rounded-xl font-semibold text-sm hover:shadow-lg hover:shadow-indigo-500/30 transition">
                    Yadda saxla
                </button>
            </div>
        </form>
    </div>
</div>

<div id="edit-modal" class="hidden fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden" onclick="event.stopPropagation()">
        <form method="POST" id="edit-form" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h3 class="font-semibold text-slate-800">Qısa URL redaktə</h3>
                <button type="button" onclick="closeEditModal()" class="w-8 h-8 rounded-full hover:bg-slate-100 flex items-center justify-center text-slate-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="p-6 space-y-4">

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Kod</label>
                    <input type="text" id="edit-code" readonly
                           class="w-full border border-slate-200 rounded-xl px-3 py-2 font-mono text-sm bg-slate-50 text-slate-500">
                    <p class="text-[11px] text-slate-400 mt-1">Ömürlük sabitdir — dəyişdirilə bilməz</p>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Növ</label>
                    <div class="grid grid-cols-3 gap-2">
                        @foreach (['sculpture' => 'Heykəl', 'page' => 'Səhifə', 'sculptures_pair' => 'Cüt'] as $type => $label)
                            <label class="cursor-pointer">
                                <input type="radio" name="target_type" value="{{ $type }}"
                                       class="hidden edit-type-radio">
                                <div class="border-2 border-slate-200 rounded-xl px-2 py-2 text-center text-xs font-semibold text-slate-600 transition"
                                     data-edit-type-btn="{{ $type }}">
                                    {{ $label }}
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div data-edit-pane="sculpture" class="edit-type-pane">
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Heykəl</label>
                    <select data-edit-select="sculpture"
                            class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm">
                        <option value="">— Seçin —</option>
                        @foreach ($sculptures as $s)
                            <option value="{{ $s->id }}">{{ $s->translation()?->title ?? $s->slug }}</option>
                        @endforeach
                    </select>
                </div>

                <div data-edit-pane="page" class="edit-type-pane hidden">
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Səhifə</label>
                    <select data-edit-select="page"
                            class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm">
                        <option value="">— Seçin —</option>
                        @foreach ($pages as $p)
                            <option value="{{ $p->id }}">{{ $p->slug }}</option>
                        @endforeach
                    </select>
                </div>

                <div data-edit-pane="sculptures_pair" class="edit-type-pane hidden">
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Heykəllər <span class="text-slate-400 font-normal">(Ctrl ilə çoxlu)</span>
                    </label>
                    <select data-edit-select="pair" multiple size="6"
                            class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm">
                        @foreach ($sculptures as $s)
                            <option value="{{ $s->id }}">{{ $s->translation()?->title ?? $s->slug }}</option>
                        @endforeach
                    </select>
                </div>

                <input type="hidden" name="target_id" id="edit-target-id">
                <input type="hidden" name="sculpture_ids_present" value="1">

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Qeyd</label>
                    <input type="text" name="note" id="edit-note"
                           class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm">
                </div>

                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" id="edit-is-active" value="1"
                           class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500">
                    <span class="text-sm text-slate-700">Aktiv</span>
                </label>
            </div>

            <div class="px-6 py-4 border-t border-slate-100 flex gap-2 justify-end bg-slate-50">
                <button type="button" onclick="closeEditModal()"
                        class="px-4 py-2 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-200 transition">
                    Ləğv et
                </button>
                <button type="submit"
                        class="px-5 py-2 rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 text-white text-sm font-semibold hover:shadow-lg hover:shadow-indigo-500/30 transition">
                    Yenilə
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function copyShortUrl(url, btn) {
    navigator.clipboard.writeText(url).then(() => {
        const orig = btn.innerHTML;
        btn.innerHTML = '<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>';
        btn.classList.add('bg-emerald-100', 'text-emerald-600');
        setTimeout(() => {
            btn.innerHTML = orig;
            btn.classList.remove('bg-emerald-100', 'text-emerald-600');
        }, 1400);
    });
}

(function () {
    const createRadios = document.querySelectorAll('.create-type-radio');
    const createPanes = document.querySelectorAll('.create-type-pane');
    const createBtns = document.querySelectorAll('[data-create-type-btn]');
    const createTargetId = document.getElementById('create-target-id');
    const createForm = document.getElementById('create-form');

    function updateCreateUI() {
        const sel = document.querySelector('.create-type-radio:checked')?.value ?? 'sculpture';
        createBtns.forEach(b => {
            const on = b.dataset.createTypeBtn === sel;
            b.classList.toggle('border-indigo-500', on);
            b.classList.toggle('bg-indigo-50', on);
            b.classList.toggle('text-indigo-700', on);
            b.classList.toggle('border-slate-200', !on);
        });
        createPanes.forEach(p => p.classList.toggle('hidden', p.dataset.createPane !== sel));
    }

    createRadios.forEach(r => r.addEventListener('change', updateCreateUI));
    updateCreateUI();

    createForm.addEventListener('submit', function () {
        const sel = document.querySelector('.create-type-radio:checked')?.value;
        if (sel === 'sculpture') {
            createTargetId.value = document.querySelector('[data-create-select="sculpture"]').value;
        } else if (sel === 'page') {
            createTargetId.value = document.querySelector('[data-create-select="page"]').value;
        } else {
            createTargetId.value = '';
            const pairSelect = document.querySelector('[data-create-select="pair"]');
            pairSelect.querySelectorAll('option').forEach(o => o.selected = o.selected);
            const hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.name = 'sculpture_ids[]';
            hidden.value = '';
            createForm.appendChild(hidden);
            createForm.querySelectorAll('input[name="sculpture_ids[]"]').forEach(el => el.remove());
            Array.from(pairSelect.selectedOptions).forEach(o => {
                const inp = document.createElement('input');
                inp.type = 'hidden';
                inp.name = 'sculpture_ids[]';
                inp.value = o.value;
                createForm.appendChild(inp);
            });
        }
    });
})();

function openEditModal(data) {
    const modal = document.getElementById('edit-modal');
    const form = document.getElementById('edit-form');
    form.action = '/admin/short-urls/' + data.id;

    document.getElementById('edit-code').value = data.code;
    document.getElementById('edit-note').value = data.note ?? '';
    document.getElementById('edit-is-active').checked = !!data.is_active;

    document.querySelectorAll('.edit-type-radio').forEach(r => {
        r.checked = (r.value === data.target_type);
    });

    const editBtns = document.querySelectorAll('[data-edit-type-btn]');
    editBtns.forEach(b => {
        const on = b.dataset.editTypeBtn === data.target_type;
        b.classList.toggle('border-indigo-500', on);
        b.classList.toggle('bg-indigo-50', on);
        b.classList.toggle('text-indigo-700', on);
        b.classList.toggle('border-slate-200', !on);
    });

    document.querySelectorAll('.edit-type-pane').forEach(p => {
        p.classList.toggle('hidden', p.dataset.editPane !== data.target_type);
    });

    if (data.target_type === 'sculpture') {
        document.querySelector('[data-edit-select="sculpture"]').value = data.target_id ?? '';
    } else if (data.target_type === 'page') {
        document.querySelector('[data-edit-select="page"]').value = data.target_id ?? '';
    } else if (data.target_type === 'sculptures_pair') {
        const ids = (data.target_params?.ids ?? []).map(String);
        const pairSelect = document.querySelector('[data-edit-select="pair"]');
        Array.from(pairSelect.options).forEach(o => {
            o.selected = ids.includes(o.value);
        });
    }

    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeEditModal() {
    const modal = document.getElementById('edit-modal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

document.getElementById('edit-modal')?.addEventListener('click', function (e) {
    if (e.target === this) closeEditModal();
});

document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') closeEditModal();
});

(function () {
    const editForm = document.getElementById('edit-form');
    const editRadios = document.querySelectorAll('.edit-type-radio');
    const editBtns = document.querySelectorAll('[data-edit-type-btn]');
    const editPanes = document.querySelectorAll('.edit-type-pane');
    const editTargetId = document.getElementById('edit-target-id');

    editRadios.forEach(r => r.addEventListener('change', function () {
        editBtns.forEach(b => {
            const on = b.dataset.editTypeBtn === this.value;
            b.classList.toggle('border-indigo-500', on);
            b.classList.toggle('bg-indigo-50', on);
            b.classList.toggle('text-indigo-700', on);
            b.classList.toggle('border-slate-200', !on);
        });
        editPanes.forEach(p => p.classList.toggle('hidden', p.dataset.editPane !== this.value));
    }));

    editForm.addEventListener('submit', function () {
        const sel = document.querySelector('.edit-type-radio:checked')?.value;
        editForm.querySelectorAll('input[name="sculpture_ids[]"]').forEach(el => el.remove());
        if (sel === 'sculpture') {
            editTargetId.value = document.querySelector('[data-edit-select="sculpture"]').value;
        } else if (sel === 'page') {
            editTargetId.value = document.querySelector('[data-edit-select="page"]').value;
        } else {
            editTargetId.value = '';
            const pairSelect = document.querySelector('[data-edit-select="pair"]');
            Array.from(pairSelect.selectedOptions).forEach(o => {
                const inp = document.createElement('input');
                inp.type = 'hidden';
                inp.name = 'sculpture_ids[]';
                inp.value = o.value;
                editForm.appendChild(inp);
            });
        }
    });
})();
</script>

@endsection
