@extends('components.layouts.dashboard-layout')

@section('title', 'Novo Pedido - Balcão')

@section('content')
    <x-layouts.breadcrumb title="Novo Pedido - Balcão" :breadcrumbs="[['name' => 'Vendas', 'route' => 'balcao'], ['name' => 'Novo Pedido - Balcão']]" />

    <div class="container bg-white rounded" style="padding: 30px;" <form id="form-add-produto">
        @csrf

        <h5 class="mb-4">Detalhes do Produto</h5>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Forma de Entrega</label>
                <select id="forma_entrega" class="form-select" name="forma_entrega">
                    <option value="">Selecione a forma de entrega</option>
                    <option value="balcao">Balcão</option>
                    <option value="entrega">Entrega</option>
                    <option value="mesa">Mesa</option>
                    <option value="retirada">Retirada</option>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Tipo de Pedido</label>
                <select id="tipo_pedido" class="form-select" name="tipo_pedido">
                    <option value="">Selecione o tipo de pedido</option>
                    <option value="delivey">Delivery</option>
                    <option value="retirada">retirada</option>
                    <option value="mesa">Mesa</option>
                </select>
            </div>
        </div>
        <div class="row align-items-end">

            <h5 class="mb-4">Produtos</h5>
            
            <div class="col-md-4 mb-3">
                <label class="form-label">Produto</label>
                <select id="nome_produto" class="form-select" name="nome_produto" onchange="buscarProduto(this.value)">
                    <option value="">Selecione um produto</option>
                    @foreach ($produtos as $produto)
                        <option value="{{ $produto->id }}">
                            {{ $produto->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2 mb-3">
                <label class="form-label">Valor</label>
                <input type="number" step="0.01" id="valor" name="valor" class="form-control" readonly>
            </div>

            <div class="col-md-2 mb-3">
                <label class="form-label">Quantidade</label>
                <input type="number" step="0.001" id="quantidade" name="quantidade" class="form-control"
                    oninput="calcularValorTotal()" readonly>
            </div>

            <div class="col-md-2 mb-3">
                <label class="form-label">Total</label>
                <input type="number" step="0.01" id="valor_total" name="valor_total" class="form-control" readonly>
            </div>

            <div class="col-md-2 mb-3">
                <button type="button" class="btn btn-purple w-100" onclick="submitProduto()" id="btn">
                    Adicionar
                </button>
            </div>

        </div>
        </form>

        <hr class="my-4">

        <h5 class="mb-3">Itens Adicionados</h5>

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Produto</th>
                        <th>Valor Unitário</th>
                        <th>Quantidade</th>
                        <th>Valor Total</th>
                        <th class="text-center">Opções</th>
                    </tr>
                </thead>

                <tbody id="tabela_produtos"></tbody>

            </table>
        </div>
        <div class="row mt-4 mb-4">
            <h5 class="mb-4">Resumo do Pedido</h5>

            <div class="col-md-3">
                <label for="valor_produtos">Produtos</label>
            </div>
            <div class="col-md-3">
                <label for="valor_entrega">Entrega</label>
            </div>
            <div class="col-md-3">
                <label for="valor_desconto">Desconto</label>
            </div>
            <div class="col-md-3">
                <label for="valor_total_geral">Total Geral</label>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-md-3">
                <button class="btn btn-cancelar w-100">Cancelar</button>
            </div>
            <div class="col-md-3">
                <button class="btn btn-purple w-100">Finalizar Pedido</button>
            </div>

        </div>
    </div>
@endsection
