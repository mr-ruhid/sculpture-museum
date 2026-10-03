@extends('admin.layouts.app')

@section('title', 'Heykəllər')

@section('content')
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-semibold text-gray-800">Heykəllər</h2>
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

        <table class="w-full text-sm">
            <thead>
                <tr class="border-b text-left text-gray-600">
                    <th class="py-2 w-16">Şəkil</th>
                    <th class="py-2">Ad</th>
                    <th class="py-2">Heykəltəraş</th>
                    <th class="py-2">İl</th>
                    <th class="py-2">Şəhər</th>
                    <th class="py-2">Status</th>
                    <th class="py-2 text-right">Əməliyyat</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($sculptures as $sculpture)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="py-2">
                            @if ($sculpture->main_image)
                                <img src="{{ asset('storage/' . $sculpture->main_image) }}"
                                     class="w-12 h-12 object-cover rounded">
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
                        <td colspan="7" class="py-6 text-center text-gray-400">
                            Hələ heykəl əlavə edilməyib.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4">
            {{ $sculptures->links() }}
        </div>
    </div>
@endsection
