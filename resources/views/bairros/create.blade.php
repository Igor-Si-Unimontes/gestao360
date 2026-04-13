@extends('components.layouts.dashboard-layout')

@section('title', 'Bairros')

@section('content')
    <x-layouts.breadcrumb title="Novo Bairro" :breadcrumbs="[
        ['name' => 'Bairros', 'route' => 'bairros.index'],
        ['name' => 'Novo Bairro', 'route' => 'bairros.create'],
    ]">
    </x-layouts.breadcrumb>

    <div class="container bg-white rounded" style="padding: 30px;">
        <form action="{{ route('bairros.store') }}" method="POST">
            @csrf

            <div class="row">
                <div class="col-8">
                    <label for="nome" class="form-label text-gray-label mt-4">Bairro*</label>
                    <input type="text" name="nome" id="nome" value="{{ old('nome') }}" required
                        class="form-control @error('nome') is-invalid @enderror"
                        placeholder="Digite o nome do bairro...">
                    @error('nome')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col-4">
                    <label for="taxa" class="form-label text-gray-label mt-4">Taxa de Entrega*</label>
                    <input type="number" step="0.01" name="taxa" id="taxa" value="{{ old('taxa') }}" required class="form-control @error('taxa') is-invalid @enderror"
                        placeholder="Ex: 10.50">
                    @error('taxa')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            <div class="row" style="margin-top: 80px;">
                <div class="col-3">
                    <a href="{{ route('bairros.index') }}" class="btn btn-cancelar w-100"
                        style="font-size: 18px; font-weight: 500;">Cancelar</a>
                </div>
                <div class="col-3">
                    <button type="submit" class="btn btn-purple w-100"
                        style="font-size: 18px; font-weight: 400;">Adicionar</button>
                </div>
            </div>
        </form>
    </div>
@endsection
