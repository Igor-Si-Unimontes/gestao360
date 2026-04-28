@extends('components.layouts.dashboard-layout')

@section('title', 'Editar item')

@section('content')
    <x-layouts.breadcrumb title="Editar item" :breadcrumbs="[
        ['name' => 'Itens do cardápio', 'route' => 'cardapio.itens.index'],
        ['name' => 'Editar'],
    ]" />

    <div class="container">
        @if ($errors->any())
            <div class="alert alert-danger mb-3">
                <ul class="mb-0 small">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form method="post" action="{{ route('cardapio.itens.update', $item) }}">
                    @csrf
                    @method('PUT')
                    @include('cardapio.itens._form', ['item' => $item])
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-purple text-white px-4">Atualizar</button>
                        <a href="{{ route('cardapio.itens.index') }}" class="btn btn-outline-secondary">Voltar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
