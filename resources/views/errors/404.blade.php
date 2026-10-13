@extends('layouts.app')

@section('title', 'Page Not Found — HillNest')
@section('robots', 'noindex')

@section('content')
@include('errors.partials.message', [
    'code' => '404',
    'heading' => 'This page wandered off into the hills',
    'text' => 'The page you are looking for does not exist or may have moved. Our pure A2 bilona ghee is still right where you left it.',
])
@endsection
