@extends('admin.layouts.app')

@section('title', 'Heykəllər')

@section('content')
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">Heykəllər</h2>
                <p class="text-xs text-gray-500 mt-1">
                    <svg class="w-3.5 h-3.5 inline-block mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                    Sürüşdürərək sırala — dəyişikliklər avtomatik yadda saxlanılır
                </p>
            </div>
            <a href="{{ route('admin.sculptures.create') }}"
               class="bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700">
                + Yeni heykəl
            </a>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4 text-sm">
                {{ session('success') }}
            </div>
        @endif

        <div id="sort-status" class="hidden mb-4 px-4 py-2 rounded text-sm"></div>

        <table class="w-full text-sm">
            <thead>
                <tr class="border-b text-left text-gray-600">
                    <th class="py-2 w-10"></th>
                    <th class="py-2 w-10 text-center">#</th>
                    <th class="py-2 w-16">Şəkil</th>
                    <th class="py-2">Ad</th>
                    <th class="py-2">Heykəltəraş</th>
                    <th class="py-2">İl</th>
                    <th class="py-2">Şəhər</th>
                    <th class="py-2">Status</th>
                    <th class="py-2 text-right">Əməliyyat</th>
                </tr>
            </thead>
            <tbody id="sortable-body">
                @forelse ($sculptures as $sculpture)
                    <tr class="border-b hover:bg-gray-50 sortable-row" data-id="{{ $sculpture->id }}">
                        <td class="py-2 text-center cursor-grab active:cursor-grabbing drag-handle">
                            <svg class="w-4 h-4 text-gray-400 inline-block" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 8h16M4 16h16"/>
                            </svg>
                        </td>
                        <td class="py-2 text-center text-xs font-mono text-gray-500 sort-order-num">
                            {{ $sculpture->sort_order }}
                        </td>
                        <td class="py-2">
                            @if ($sculpture->main_image)
                                <img src="{{ asset('storage/' . $sculpture->main_image) }}"
                                     class="w-12 h-12 object-cover rounded pointer-events-none">
                            @else
                                <div class="w-12 h-12 bg-gray-100 rounded"></div>
                            @endif
                        </td>
                        <td class="py-2 font-medium text-gray-800">
                            {{ $sculpture->translation()?->title ?? '—' }}
                        </td>
                        <td class="py-2">{{ $sculpture->translation()?->sculptor ?? '—' }}</td>
                        <td class="py-2">{{ $sculpture->year ?? '—' }}</td>
                        <td class="py-2">{{ $sculpture->translation()?->city ?? '—' }}</td>
                        <td class="py-2">
                            @if ($sculpture->is_published)
                                <span class="text-green-600 text-xs">Dərc edilib</span>
                            @else
                                <span class="text-gray-400 text-xs">Qaralama</span>
                            @endif
                        </td>
                        <td class="py-2 text-right whitespace-nowrap">
                            <a href="{{ route('admin.sculptures.edit', $sculpture) }}"
                               class="text-blue-600 hover:underline text-xs">Redaktə</a>
                            <a href="{{ url('/' . app()->getLocale() . '/sculptures/' . $sculpture->slug) }}"
                               target="_blank" rel="noopener"
                               class="text-green-600 hover:underline text-xs ml-2">Bax</a>
                            <form action="{{ route('admin.sculptures.destroy', $sculpture) }}"
                                  method="POST" class="inline"
                                  onsubmit="return confirm('Silinsin?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline text-xs ml-2">
                                    Sil
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="py-6 text-center text-gray-400">
                            Hələ heykəl əlavə edilməyib.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const tbody = document.getElementById('sortable-body');
        if (!tbody) return;

        const statusEl = document.getElementById('sort-status');
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content
            || '{{ csrf_token() }}';
        const updateUrl = '{{ route('admin.sculptures.updateSort') }}';

        let saveTimer = null;

        function showStatus(message, isError) {
            statusEl.textContent = message;
            statusEl.classList.remove('hidden', 'bg-green-50', 'text-green-700', 'bg-red-50', 'text-red-700');

            if (isError) {
                statusEl.classList.add('bg-red-50', 'text-red-700');
            } else {
                statusEl.classList.add('bg-green-50', 'text-green-700');
            }

            clearTimeout(showStatus._timer);
            showStatus._timer = setTimeout(() => {
                statusEl.classList.add('hidden');
            }, 2500);
        }

        function renumberRows() {
            tbody.querySelectorAll('.sortable-row').forEach((row, index) => {
                const numEl = row.querySelector('.sort-order-num');
                if (numEl) numEl.textContent = index + 1;
            });
        }

        function saveOrder() {
            const ids = Array.from(tbody.querySelectorAll('.sortable-row'))
                .map(row => parseInt(row.dataset.id))
                .filter(id => !isNaN(id));

            fetch(updateUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({ order: ids }),
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    showStatus('✓ Sıra yadda saxlanıldı', false);
                } else {
                    showStatus('Xəta: ' + (data.message || 'bilinmir'), true);
                }
            })
            .catch(() => {
                showStatus('Şəbəkə xətası — sıra yadda saxlanmadı', true);
            });
        }

        Sortable.create(tbody, {
            animation: 180,
            handle: '.drag-handle',
            ghostClass: 'bg-indigo-50',
            chosenClass: 'bg-indigo-100',
            dragClass: 'shadow-lg',
            onEnd: function () {
                renumberRows();

                clearTimeout(saveTimer);
                saveTimer = setTimeout(saveOrder, 350);
            },
        });
    });
    </script>
@endsection
