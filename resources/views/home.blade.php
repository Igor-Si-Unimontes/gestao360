@extends('components.layouts.dashboard-layout')

@section('title', 'Dashboard')

@section('content')
<x-layouts.breadcrumb 
    title="Dashboard" 
    :breadcrumbs="[
        ['name' => 'Dashboard', 'route' => 'dashboard'],
    ]"
>
</x-layouts.breadcrumb>
<div class="container mx-auto bg-white rounded p-6">
    <p>Home</p>
    <button class="btn btn-primary">alou</button>
</div>
@endsection