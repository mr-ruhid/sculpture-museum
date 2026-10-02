@extends('admin.layouts.app')

@section('title', 'Dillər')

@section('content')
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-6">Dillər</h2>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4 text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mb-4 text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <table class="w-full text-sm">
            <thead>
                <tr class="border-b text-left text-gray-600">
                    <th class="py-2">Kod</th>
                    <th class="py-2">Ad</th>
                    <th class="py-2">Bayraq URL</th>
                    <th class="py-2">Görünüş</th>
                    <th class="py-2">Default</th>
                    <th class="py-2 text-right">Əməliyyat</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($languages as $language)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="py-2 font-mono">{{ $language->code }}</td>
                        <td class="py-2">
                            <form method="POST" action="{{ route('admin.languages.update', $language) }}"
                                  class="flex items-center gap-2">
                                @csrf
                                @method('PUT')
                                <input type="text" name="name" value="{{ $language->name }}"
                                       class="border border-gray-300 rounded px-2 py-1 text-sm w-32">
                                <input type="text" name="flag" value="{{ $language->flag }}"
                                       placeholder="https://..."
                                       class="border border-gray-300 rounded px-2 py-1 text-sm w-64">
                                <label class="flex items-center gap-1 text-xs text-gray-600">
                                    <input type="checkbox" name="is_active" value="1"
                                           {{ $language->is_active ? 'checked' : '' }}>
                                    Aktiv
                                </label>
                                <button type="submit"
                                        class="bg-blue-600 text-white px-3 py-1 rounded text-xs hover:bg-blue-700">
                                    Yadda saxla
                                </button>
                            </form>
                        </td>
                        <td class="py-2">
                            @if ($language->flag)
                                <img src="{{ $language->flag }}" alt="{{ $language->code }}"
                                     class="w-8 h-5 object-cover rounded border border-gray-200">
                            @else
                                <span class="text-gray-300">—</span>
                            @endif
                        </td>
                        <td class="py-2">
                            @if ($language->is_default)
                                <span class="text-blue-600 font-semibold">★ Default</span>
                            @else
                                <form method="POST" action="{{ route('admin.languages.default', $language) }}">
                                    @csrf
                                    <button type="submit" class="text-gray-500 hover:text-blue-600 text-xs">
                                        Default et
                                    </button>
                                </form>
                            @endif
                        </td>
                        <td class="py-2 text-right">
                            <a href="{{ route('admin.translations.edit', $language) }}"
                               class="text-blue-600 hover:underline text-xs">
                                Tərcümələri redaktə et
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
