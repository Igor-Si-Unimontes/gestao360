@extends('components.layouts.dashboard-layout')

@section('title', 'Categorias')

@section('content')
    <x-layouts.breadcrumb title="Nova Categoria" :breadcrumbs="[
        ['name' => 'Categorias', 'route' => 'categories.index'],
        ['name' => 'Nova Categoria', 'route' => 'categories.create'],
    ]">
    </x-layouts.breadcrumb>

    <div class="container bg-white rounded" style="padding: 30px;">
        <form action="{{ route('categories.store') }}" method="POST">
            @csrf

            <div class="row">
                <div class="col-12">
                    <label for="name" class="form-label text-gray-label mt-4">Nome*</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                        class="form-control p-3 @error('name') is-invalid @enderror"
                        placeholder="Digite o nome da categoria...">
                    @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            <div class="row" style="margin-top: 80px;">
                <div class="col-3">
                    <a href="{{ route('categories.index') }}" class="btn btn-cancelar w-100"
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