@extends('components.layouts.dashboard-layout')

@section('title', 'Novo Lote')

@section('content')
    <x-layouts.breadcrumb title="Novo Lote de {{ $product->name }}" :breadcrumbs="[['name' => 'Lotes', 'route' => 'lotes.index'], ['name' => 'Novo Lote de ' . $product->name]]">
    </x-layouts.breadcrumb>

    <div class="container bg-white rounded" style="padding: 30px;">
        <form action="{{ route('lotes.store', $product->id) }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-4 mb-3">
                    <label for="quantity" class="form-label">Quantidade</label>
                    <input type="number" class="form-control" id="quantity" name="quantity" required>
                </div>
                <div class="col-4 mb-3">
                    <label for="cost_price" class="form-label">Custo Unitário</label>
                    <input type="number" class="form-control" id="cost_price" name="cost_price" step="0.01" required>
                </div>
                <div class="col-4 mb-3">
                    <label for="sale_price" class="form-label">Preço de Venda</label>
                    <input type="number" class="form-control" id="sale_price" name="sale_price" step="0.01" required>
                </div>
                <div class="col-4 mb-3">
                    <label for="expiration_date" class="form-label">Data de Validade</label>
                    <input type="date" class="form-control" id="expiration_date" name="expiration_date" required>
                </div>
                <div class="col-4 mb-3">
                    <label for="batch_code" class="form-label">Código do Lote</label>
                    <input type="text" class="form-control" id="batch_code" name="batch_code" required>
                </div>
                <div class="col-4 mb-3">
                    <label class="form-label">Markup Calculado (%)</label>
                    <input type="text" class="form-control" id="markup_display" readonly>
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
        const costInput = document.getElementById('cost_price');
        const saleInput = document.getElementById('sale_price');
        const markupInput = document.getElementById('markup_display');

        function calcularMarkup() {
            const cost = parseFloat(costInput.value) || 0;
            const sale = parseFloat(saleInput.value) || 0;

            if (cost > 0 && sale > 0) {
                const markup = ((sale - cost) / cost) * 100;
                markupInput.value = markup.toFixed(2) + ' %';
            } else {
                markupInput.value = '';
            }
        }

        costInput.addEventListener('input', calcularMarkup);
        saleInput.addEventListener('input', calcularMarkup);
    </script>
@endsection
