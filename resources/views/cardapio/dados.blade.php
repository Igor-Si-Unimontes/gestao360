@extends('components.layouts.dashboard-layout')

@section('title', 'Dados do cardápio')

@section('content')
    <x-layouts.breadcrumb title="Dados do cardápio" :breadcrumbs="[
        ['name' => 'Itens do cardápio', 'route' => 'cardapio.itens.index'],
        ['name' => 'Título e descrição'],
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
                <p class="text-muted small mb-4">Exibidos no topo da vitrine pública (<code>/menu</code>).</p>
                <form method="post" action="{{ route('cardapio.dados.update') }}">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Nome do cardápio</label>
                        <input type="text" name="nome" class="form-control @error('nome') is-invalid @enderror"
                               value="{{ old('nome', $cardapio->nome) }}" required maxlength="160">
                        @error('nome')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Descrição <span class="text-muted small">(opcional)</span></label>
                        <textarea name="descricao" rows="3" class="form-control @error('descricao') is-invalid @enderror"
                                  maxlength="2000">{{ old('descricao', $cardapio->descricao) }}</textarea>
                        @error('descricao')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-purple text-white px-4">Salvar</button>
                        <a href="{{ route('cardapio.itens.index') }}" class="btn btn-outline-secondary">Voltar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
