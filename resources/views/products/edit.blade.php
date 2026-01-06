@extends('components.layouts.dashboard-layout')

@section('title', 'Editar Produto')

@section('content')
    <x-layouts.breadcrumb title="Editar Produto" :breadcrumbs="[['name' => 'Produtos', 'route' => 'produtos.index'], ['name' => 'Editar Produto']]" />

    <div class="container bg-white rounded" style="padding: 30px;">

        <ul class="nav nav-tabs" id="produtoTabs" role="tablist">
            <li class="nav-item">
                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#dados">
                    Dados do Produto
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#lotes">
                    Lotes
                </button>
            </li>
        </ul>

        <div class="tab-content mt-3">
            <div class="tab-pane fade show active" id="dados">
                <form action="{{ route('produtos.update', $produto->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-4 mb-3">
                            <label class="form-label">Nome</label>
                            <input type="text" name="name" class="form-control" value="{{ $produto->name }}">
                        </div>

                        <div class="col-4 mb-3">
                            <label class="form-label">Código</label>
                            <input type="text" name="code" class="form-control" value="{{ $produto->code }}">
                        </div>
                        <div class="col-4 mb-3">
                            <label for="category_id" class="form-label">Categoria</label>
                            <select class="form-select" id="category_id" name="category_id" required>
                                <option value="" disabled>
                                    Selecione uma categoria
                                </option>

                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('category_id', $product->category_id ?? null) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-4 mb-3">
                            <label for="supplier_id" class="form-label">Fornecedor</label>
                            <select class="form-select" id="supplier_id" name="supplier_id" required>
                                <option value="" disabled>
                                    Selecione um fornecedor
                                </option>

                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}"
                                        {{ old('supplier_id', $product->supplier_id ?? null) == $supplier->id ? 'selected' : '' }}>
                                        {{ $supplier->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-4 mb-3">
                            <label class="form-label">Retornável</label>
                            <select name="returnable_product" class="form-control">
                                <option value="0" {{ !$produto->returnable_product ? 'selected' : '' }}>Não</option>
                                <option value="1" {{ $produto->returnable_product ? 'selected' : '' }}>Sim</option>
                            </select>
                        </div>
                        <div class="col-4 mb-3">
                            <label class="form-label">Descrição</label>
                            <input type="text" name="description" class="form-control"
                                value="{{ $produto->description }}">
                        </div>
                    </div>
                    <div class="row" style="margin-top: 30px;">
                        <div class="col-3">
                            <a href="{{ route('produtos.index') }}" class="btn btn-cancelar w-100"
                                style="font-size: 18px; font-weight: 500;">Cancelar</a>
                        </div>
                        <div class="col-3">
                            <button type="submit" class="btn btn-purple w-100"
                                style="font-size: 18px; font-weight: 400;">Salvar Produto</button>
                        </div>
                    </div>
            </div>
            </form>
        </div>

        <div class="tab-pane fade" id="lotes">

            @forelse ($produto->batches as $batch)
                <form action="{{ route('lotes.update', $batch->id) }}" method="POST" class="border rounded p-3 mb-4 lote">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-4 mb-3">
                            <label class="form-label">Quantidade</label>
                            <input type="number" name="quantity" class="form-control" value="{{ $batch->quantity }}">
                        </div>

                        <div class="col-4 mb-3">
                            <label class="form-label">Preço de Custo</label>
                            <input type="text" name="cost_price" class="form-control cost_price"
                                value="{{ $batch->cost_price }}">
                        </div>

                        <div class="col-4 mb-3">
                            <label class="form-label">Preço de Venda</label>
                            <input type="text" name="sale_price" class="form-control sale_price"
                                value="{{ $batch->sale_price }}">
                        </div>

                        <div class="col-4 mb-3">
                            <label class="form-label">Validade</label>
                            <input type="date" name="expiration_date" class="form-control"
                                value="{{ $batch->expiration_date }}">
                        </div>

                        <div class="col-4 mb-3">
                            <label class="form-label">Código do Lote</label>
                            <input type="text" name="batch_code" class="form-control"
                                value="{{ $batch->batch_code }}">
                        </div>

                        <div class="col-4 mb-3">
                            <label class="form-label">Markup (%)</label>
                            <input type="text" class="form-control markup_display" readonly>
                        </div>

                        <div class="row" style="margin-top: 30px;">
                            <div class="col-3">
                                <a href="{{ route('produtos.index') }}" class="btn btn-cancelar w-100"
                                    style="font-size: 18px; font-weight: 500;">Cancelar</a>
                            </div>
                            <div class="col-3">
                                <button type="submit" class="btn btn-purple w-100"
                                    style="font-size: 18px; font-weight: 400;">Salvar Lote</button>
                            </div>
                        </div>
                    </div>
                </form>
            @empty
                <p class="text-muted mt-3">Nenhum lote cadastrado.</p>
            @endforelse

        </div>
    </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.lote').forEach(lote => {
                const cost = parseFloat(lote.querySelector('.cost_price').value) || 0;
                const sale = parseFloat(lote.querySelector('.sale_price').value) || 0;
                const markupField = lote.querySelector('.markup_display');

                if (cost > 0 && sale > 0) {
                    markupField.value = (((sale - cost) / cost) * 100).toFixed(2) + ' %';
                } else {
                    markupField.value = '0 %';
                }
            });
        });
    </script>
@endsection

@section('styles')
    <style>
        #produtoTabs .nav-link.active {
            background-color: #7212e7;
            color: #fff;
            border-radius: 8px 8px 0 0;
        }

        #produtoTabs .nav-link {
            background-color: #7212e71a;
            color: #7212e7;
            margin-left: 10px;
            border-radius: 8px 8px 0 0;
            border: none;
        }
    </style>
@endsection
