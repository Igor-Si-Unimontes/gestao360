@extends('components.layouts.dashboard-layout')

@section('title', 'Funcionários')

@section('content')
<x-layouts.breadcrumb 
    title="Novo Funcionário" 
    :breadcrumbs="[
        ['name' => 'Funcionários', 'route' => 'employees.index'],
        ['name' => 'Novo Funcionário', 'route' => 'employees.create'], 
    ]"
>
</x-layouts.breadcrumb>
<div class="container mx-auto bg-white rounded p-6">
    <p>Funcionarios</p>
    <button class="btn btn-primary">alou</button>
</div>
@endsection