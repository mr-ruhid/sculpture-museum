@extends('admin.layouts.app')

@section('title', 'About')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <h3 class="font-semibold text-slate-800 mb-4">Site Details</h3>
            <dl class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <dt class="text-slate-500">Backend</dt>
                    <dd class="font-medium text-slate-800">RJBackend / RJ CMS Lite derivative</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-slate-500">Frontend</dt>
                    <dd class="font-medium text-slate-800">RJ CMS Museum Theme</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-slate-500">Version</dt>
                    <dd class="font-medium text-slate-800">1.1.5</dd>
                </div>
            </dl>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <h3 class="font-semibold text-slate-800 mb-4">System Information</h3>
            <dl class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <dt class="text-slate-500">Laravel Version</dt>
                    <dd class="font-medium text-slate-800">{{ app()->version() }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-slate-500">PHP Version</dt>
                    <dd class="font-medium text-slate-800">{{ phpversion() }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-slate-500">Server Software</dt>
                    <dd class="font-medium text-slate-800">{{ $_SERVER['SERVER_SOFTWARE'] ?? 'N/A' }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-slate-500">Server OS</dt>
                    <dd class="font-medium text-slate-800">{{ PHP_OS }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-slate-500">Database Driver</dt>
                    <dd class="font-medium text-slate-800">{{ config('database.default') }}</dd>
                </div>
            </dl>
        </div>

    </div>

    <div class="mt-6 flex flex-wrap gap-3">
        <a href="https://ruhidjavadoff.blogspot.com/2026/07/rj-cms-sistemlri.html" target="_blank"
           class="inline-flex items-center gap-2 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-sm font-medium transition">
            RJ CMS Systems
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
        </a>
        <a href="https://ruhidjavadoff.blogspot.com/2023/12/rj-cms-derivative-sites.html" target="_blank"
           class="inline-flex items-center gap-2 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-sm font-medium transition">
            RJ CMS Derivative Sites
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
        </a>
        <a href="https://ruhidjavadoff.blogspot.com/2021/03/rj-cms-lite.html" target="_blank"
           class="inline-flex items-center gap-2 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-sm font-medium transition">
            RJ CMS Lite
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
        </a>
    </div>
@endsection
