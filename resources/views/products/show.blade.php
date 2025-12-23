@extends('components.layouts.dashboard-layout')

@section('title', 'Visualizar Produto')

@section('content')
    <x-layouts.breadcrumb title="Visualizar Produto" :breadcrumbs="[['name' => 'Produtos', 'route' => 'produtos.index'], ['name' => 'Visualizar Produto']]" />
    <div class="container bg-white rounded" style="padding: 30px;">
        <ul class="nav nav-tabs" id="produtoTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="dados-tab" data-bs-toggle="tab" data-bs-target="#dados" type="button"
                    role="tab" aria-controls="dados" aria-selected="true">
                    Dados do Produto
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="lotes-tab" data-bs-toggle="tab" data-bs-target="#lotes" type="button"
                    role="tab" aria-controls="lotes" aria-selected="false">
                    Lotes
                </button>
            </li>
        </ul>

        <div class="tab-content mt-3" id="produtoTabsContent">
            <div class="tab-pane fade show active" id="dados" role="tabpanel" aria-labelledby="dados-tab">
                <div class="row">
                    <div class="col-4 mb-3">
                        <label class="form-label">Nome</label>
                        <input type="text" class="form-control" value="{{ $produto->name }}" readonly>
                    </div>
                    <div class="col-4 mb-3">
                        <label class="form-label">Código do Produto</label>
                        <input type="text" class="form-control" value="{{ $produto->code }}" readonly>
                    </div>
                    <div class="col-4 mb-3">
                        <label class="form-label">Categoria</label>
                        <input type="text" class="form-control" value="{{ $produto->category->name ?? '-' }}" readonly>
                    </div>
                    <div class="col-4 mb-3">
                        <label class="form-label">Fornecedor</label>
                        <input type="text" class="form-control" value="{{ $produto->supplier->name ?? '-' }}" readonly>
                    </div>
                    <div class="col-4 mb-3">
                        <label class="form-label">Produto Retornável</label>
                        <input type="text" class="form-control"
                            value="{{ $produto->returnable_produto ? 'Sim' : 'Não' }}" readonly>
                    </div>
                    <div class="col-4 mb-3">
                        <label class="form-label">Descrição</label>
                        <input type="text" class="form-control" value="{{ $produto->description }}" readonly>
                    </div>
                </div>
            </div>

            <!-- Aba: Lotes -->
            <div class="tab-pane fade" id="lotes" role="tabpanel" aria-labelledby="lotes-tab">
                @if ($produto->batches->isEmpty())
                    <p class="text-muted">Nenhum lote cadastrado para este produto.</p>
                @else
                    <div class="row">
                        @foreach ($produto->batches as $batch)
                        <p>Criado por {{ $batch->created_by ?? 'Desconhecido' }} as {{ \Carbon\Carbon::parse($batch->created_at)->format('d/m/Y H:i') }}</p>
                            <div class="row lote mb-3">
                                <div class="col-4 mb-3">
                                    <label class="form-label">Quantidade</label>
                                    <input type="text" class="form-control" value="{{ $batch->quantity }}" readonly>
                                </div>
                                <div class="col-4 mb-3">
                                    <label class="form-label">Preço de Custo</label>
                                    <input type="text" class="form-control cost_price" value="{{ $batch->cost_price }}"
                                        readonly>
                                </div>
                                <div class="col-4 mb-3">
                                    <label class="form-label">Preço de Venda</label>
                                    <input type="text" class="form-control sale_price" value="{{ $batch->sale_price }}"
                                        readonly>
                                </div>
                                <div class="col-4 mb-3">
                                    <label class="form-label">Data de Validade</label>
                                    <input type="text" class="form-control"
                                        value="{{ \Carbon\Carbon::parse($batch->expiration_date)->format('d/m/Y') }}"
                                        readonly>
                                </div>

                                <div class="col-4 mb-3">
                                    <label class="form-label">Código do Lote</label>
                                    <input type="text" class="form-control" value="{{ $batch->batch_code }}" readonly>
                                </div>
                                <div class="col-4 mb-3">
                                    <label class="form-label">Markup Calculado (%)</label>
                                    <input type="text" class="form-control markup_display" readonly>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-3">
                <a href="{{ route('produtos.index') }}" class="btn btn-cancelar w-100"
                    style="font-size: 18px; font-weight: 500;">
                    Voltar
                </a>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loteRows = document.querySelectorAll('.row.lote');

            loteRows.forEach(row => {
                const costInput = row.querySelector('.cost_price');
                const saleInput = row.querySelector('.sale_price');
                const markupInput = row.querySelector('.markup_display');

                const cost = parseFloat(costInput.value) || 0;
                const sale = parseFloat(saleInput.value) || 0;

                if (cost > 0 && sale > 0) {
                    const markup = ((sale - cost) / cost) * 100;
                    markupInput.value = markup.toFixed(2) + ' %';
                } else {
                    markupInput.value = '0 %';
                }
            });
        });
    </script>
@endsection

@section('styles')
    <style>
        #produtoTabs .nav-link.active {
            background-color: #7212e7;
            color: #ffffff;
            border-radius: 8px 8px 0 0;
        }

        #produtoTabs .nav-link {
            background-color: #7212E71A;
            color: #7212e7;
            border-radius: 8px 8px 0 0;
        }

        #produtoTabs .nav-link {
            border: none;
            margin-left: 10px;
            border-radius: 8px 8px 0 0;
        }

        #produtoTabs .nav-link.active {
            border-bottom: 2px solid #7212e7;
        }
    </style>
@endsection
