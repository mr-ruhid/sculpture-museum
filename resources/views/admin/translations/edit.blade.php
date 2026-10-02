@extends('admin.layouts.app')

@section('title', 'Tərcümələr')

@section('content')
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-semibold text-gray-800">
                Tərcümələr — {{ $language->flag }} {{ $language->name }}
            </h2>
            <a href="{{ route('admin.languages.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                ← Geri
            </a>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4 text-sm">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.translations.update', $language) }}">
            @csrf
            @method('PUT')

            <table class="w-full text-sm mb-4">
                <thead>
                    <tr class="border-b text-left text-gray-600">
                        <th class="py-2 w-1/3">Key</th>
                        <th class="py-2">Dəyər</th>
                    </tr>
                </thead>
                <tbody id="rows">
                    @foreach ($translations as $key => $value)
                        <tr class="border-b">
                            <td class="py-2 pr-2">
                                <input type="text" name="keys[]" value="{{ $key }}"
                                       class="w-full border border-gray-300 rounded px-2 py-1 font-mono text-xs">
                            </td>
                            <td class="py-2">
                                <input type="text" name="values[]" value="{{ $value }}"
                                       class="w-full border border-gray-300 rounded px-2 py-1">
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="flex items-center gap-3">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded text-sm hover:bg-blue-700">
                    Yadda saxla
                </button>
                <button type="button" onclick="addRow()"
                        class="bg-gray-200 text-gray-700 px-4 py-2 rounded text-sm hover:bg-gray-300">
                    + Yeni sətir
                </button>
            </div>
        </form>
    </div>

    <script>
        function addRow() {
            const row = document.createElement('tr');
            row.className = 'border-b';
            row.innerHTML = `
                <td class="py-2 pr-2">
                    <input type="text" name="keys[]" class="w-full border border-gray-300 rounded px-2 py-1 font-mono text-xs">
                </td>
                <td class="py-2">
                    <input type="text" name="values[]" class="w-full border border-gray-300 rounded px-2 py-1">
                </td>
            `;
            document.getElementById('rows').appendChild(row);
        }
    </script>
@endsection
