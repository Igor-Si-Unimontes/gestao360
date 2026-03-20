@extends('components.layouts.dashboard-layout')

@section('title', 'Novo Produto')

@section('content')
    <x-layouts.breadcrumb title="Novo Produto" :breadcrumbs="[['name' => 'Produtos', 'route' => 'produtos.index'], ['name' => 'Novo Produto']]">
    </x-layouts.breadcrumb>

    <div class="container bg-white rounded" style="padding: 30px;">
        <form action="{{ route('produtos.store') }}" method="POST">
            @csrf
            <h5 style="margin-bottom: 20px;">Dados do Produto</h5>
            <div class="row">
                <div class="col-4 mb-3">
                    <label for="name" class="form-label">Nome</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                </div>
                <div class="col-4 mb-3">
                    <label for="code" class="form-label">Código do Produto</label>
                    <input type="text" class="form-control" id="code" name="code" value="{{ old('code') }}" required>
                </div>
                <div class="col-4 mb-3">
                    <label for="category_id" class="form-label">Categoria</label>
                    <select class="form-select" id="category_id" name="category_id" required>
                        <option value="" disabled {{ old('category_id') ? '' : 'selected' }}>Selecione uma categoria</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-4 mb-3">
                    <label for="supplier_id" class="form-label">Fornecedor</label>
                    <select class="form-select" id="supplier_id" name="supplier_id" required>
                        <option value="" disabled {{ old('supplier_id') ? '' : 'selected' }}>Selecione um fornecedor</option>
                        @foreach ($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                {{ $supplier->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
               <div class="col-4 mb-3">
                    <label for="returnable_product" class="form-label">Produto Retornável</label>
                    <select class="form-select" id="returnable_product" name="returnable_product" required>
                        <option value="0" @selected(old('returnable_product') == '0')>Não</option>
                        <option value="1" @selected(old('returnable_product') == '1')>Sim</option>
                    </select>
                </div>
                <div class="col-4 mb-3">
                    <label for="description" class="form-label">Descrição</label>
                    <input type="text" class="form-control" id="description" name="description" value="{{ old('description') }}">
                </div>
            </div>
            <hr style="margin: 30px 0;">

            <h5 style="margin-bottom: 20px;">Dados Fiscais</h5>

            <div class="row">
                <div class="col-4 mb-3">
                    <label class="form-label">Código do Produto (cProd)</label>
                    <input type="text" name="cProd" class="form-control" value="{{ old('cProd') }}">
                </div>

                <div class="col-4 mb-3">
                    <label class="form-label">Código de Barras (cEAN)</label>
                    <input type="text" name="cEAN" class="form-control" value="{{ old('cEAN') }}">
                </div>

                <div class="col-4 mb-3">
                    <label class="form-label">Descrição do Produto (xProd)</label>
                    <input type="text" name="xProd" class="form-control" value="{{ old('xProd') }}">
                </div>

                <div class="col-4 mb-3">
                    <label class="form-label">Nomenclatura Comum do Mercosul (NCM)</label>
                    <input type="text" name="NCM" class="form-control" value="{{ old('NCM') }}">
                </div>

                <div class="col-4 mb-3">
                    <label class="form-label">Código Substituição Tributária (CEST)</label>
                    <input type="text" name="CEST" class="form-control" value="{{ old('CEST') }}">
                </div>

                <div class="col-4 mb-3">
                    <label class="form-label">Código Fiscal de Operações (CFOP)</label>
                    <input type="text" name="CFOP" class="form-control" value="{{ old('CFOP') }}">
                </div>

                <div class="col-4 mb-3">
                    <label class="form-label">Código de Barras Tributável (cEANTrib)</label>
                    <input type="text" name="cEANTrib" class="form-control" value="{{ old('cEANTrib') }}">
                </div>

                <div class="col-4 mb-3">
                    <label class="form-label">Código de situação (CST)</label>
                    <input type="text" name="CST" class="form-control" value="{{ old('CST') }}">
                </div>

                <div class="col-4 mb-3">
                    <label class="form-label">Alíquota suportada pelo Consumidor (pST)</label>
                    <input type="number" step="0.01" name="pST" class="form-control" value="{{ old('pST') }}">
                </div>
            </div>
            <div class="row" style="margin-top: 30px;">
                <div class="col-3">
                    <a href="{{ route('produtos.index') }}" class="btn btn-cancelar w-100"
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
@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const code = document.getElementById('code');
        const description = document.getElementById('description');
        const name = document.getElementById('name');

        const cProd = document.querySelector('input[name="cProd"]');
        const xProd = document.querySelector('input[name="xProd"]');

        let isUpdating = false;

        function sync(source, target) {
            if (isUpdating) return;
            isUpdating = true;

            target.value = source.value;

            isUpdating = false;
        }

        code.addEventListener('input', () => sync(code, cProd));
        cProd.addEventListener('input', () => sync(cProd, code));

        description.addEventListener('input', () => sync(description, xProd));
        xProd.addEventListener('input', () => sync(xProd, description));

        name.addEventListener('input', function () {
            if (!description.value && !isUpdating) {
                sync(name, xProd);
            }
        });
    });
</script>
@endsection