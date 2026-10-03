@php
    $tr = $page->translation();
    if (!$tr) {
        abort(404);
    }
    $siteName = \App\Models\Setting::get('site_name_' . app()->getLocale(), config('app.name'));
@endphp

@extends('theme.rjmuseum.layouts.app')

@section('title', $tr->meta_title ?: ($tr->title ? $tr->title . ' — ' . $siteName : $siteName))
@section('meta_description', $tr->meta_description ?? '')
@section('meta_keywords', $tr->meta_keywords ?? '')

@section('content')

<div class="page-content">
    {!! $tr->content !!}
</div>

@endsection
