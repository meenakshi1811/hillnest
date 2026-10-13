@extends('layouts.app')

@section('title', 'Too Many Attempts — HillNest')
@section('robots', 'noindex')

@php($retryAfter = (int) ($exception->getHeaders()['Retry-After'] ?? 60))

@section('content')
@include('errors.partials.message', [
    'code' => '429',
    'heading' => 'Too many attempts',
    'text' => 'For your security, please wait '.($retryAfter >= 120 ? ceil($retryAfter / 60).' minutes' : $retryAfter.' seconds').' and then try again.',
])
@endsection
