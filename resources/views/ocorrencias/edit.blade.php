@extends('components.layouts.dashboard-layout')

@section('title', 'Editar Ocorrência')

@section('content')
    <x-layouts.breadcrumb title="Editar Ocorrência" :breadcrumbs="[
        ['name' => 'Ocorrências', 'route' => 'ocorrencias.index'],
        ['name' => 'Editar Ocorrência'],
    ]" />

    @if (session('error'))
        <div class="container mb-3">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif

    <div class="container bg-white rounded" style="padding: 30px;">
        <form action="{{ route('ocorrencias.update', $ocorrencia->id) }}" method="POST">
            @method('PATCH')
            @csrf

            <div class="row">
                <div class="col-4">
                    <label for="produto_id" class="form-label text-gray-label mt-4">Produto*</label>
                    <select name="produto_id" id="produto_id" required class="form-select p-3 @error('produto_id') is-invalid @enderror">
                        <option value="">Selecione...</option>
                        @foreach ($produtos as $produto)
                            <option value="{{ $produto->id }}" {{ old('produto_id', $ocorrencia->produto_id) == $produto->id ? 'selected' : '' }}>
                                {{ $produto->name }} (Estoque: {{ number_format((int) $produto->total_quantity, 0, ',', '.') }})
                            </option>
                        @endforeach
                    </select>
                    @error('produto_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-4">
                    <label for="quantidade" class="form-label text-gray-label mt-4">Quantidade*</label>
                    <input type="number" step="0.01" min="0.01" name="quantidade" id="quantidade"
                        value="{{ old('quantidade', (int) $ocorrencia->quantidade) }}" required
                        class="form-control p-3 @error('quantidade') is-invalid @enderror"
                        placeholder="Digite a quantidade...">
                    @error('quantidade')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-4">
                    <label class="form-label text-gray-label mt-4">Responsável</label>
                    <input type="text" value="{{ $ocorrencia->usuario->name ?? '—' }}" disabled class="form-control p-3 bg-light">
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <label for="motivo" class="form-label text-gray-label mt-4">Motivo*</label>
                    <textarea name="motivo" id="motivo" rows="4" required
                        class="form-control p-3 @error('motivo') is-invalid @enderror"
                        placeholder="Ex: garrafa quebrada, produto sem gás, item estragado...">{{ old('motivo', $ocorrencia->motivo) }}</textarea>
                    @error('motivo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row" style="margin-top: 80px;">
                <div class="col-3">
                    <a href="{{ route('ocorrencias.index') }}" class="btn btn-cancelar w-100"
                        style="font-size: 18px; font-weight: 500;">Cancelar</a>
                </div>
                <div class="col-3">
                    <button type="submit" class="btn btn-purple w-100"
                        style="font-size: 18px; font-weight: 400;">Atualizar</button>
                </div>
            </div>
        </form>
    </div>
@endsection
