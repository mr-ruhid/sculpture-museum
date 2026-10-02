@extends('admin.layouts.app')

@section('title', __('admin.about'))

@section('content')
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-2">{{ __('admin.about') }}</h2>
        <p class="text-gray-600">{{ __('admin.about_text') }}</p>
    </div>
@endsection
