@extends('components.layouts.dashboard-layout')

@section('title', 'Funcionários')

@section('content')
<x-layouts.breadcrumb 
    title="Funcionários" 
    :breadcrumbs="[
        ['name' => 'Funcionários', 'route' => 'employees.index'],
    ]"
>
    <a href="{{ route('employees.create') }}" class="btn btn-purple-main text-white">Novo Funcionário</a>
</x-layouts.breadcrumb>

<div class="container mx-auto bg-white rounded p-6">
    <p>Funcionarios</p>
    <button class="btn btn-primary">alou</button>
</div>
@endsection