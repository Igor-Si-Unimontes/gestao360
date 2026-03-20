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
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#fiscais">
                    Dados Fiscais
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
                </form>

            </div>

            <div class="tab-pane fade" id="fiscais">

                <form
                    action="{{ $produto->fiscal ? route('fiscais.update', $produto->fiscal->id) : route('fiscais.store') }}"
                    method="POST">
                    @csrf
                    @if ($produto->fiscal)
                        @method('PUT')
                    @else
                        <input type="hidden" name="product_id" value="{{ $produto->id }}">
                    @endif

                    <div class="row">
                        <div class="col-4 mb-3">
                            <label class="form-label">Código do Produto (cProd)</label>
                            <input type="text" name="cProd" class="form-control"
                                value="{{ old('cProd', $produto->fiscal->cProd ?? '') }}">
                        </div>
                        <div class="col-4 mb-3">
                            <label class="form-label">Código de Barras (cEAN)</label>
                            <input type="text" name="cEAN" class="form-control"
                                value="{{ old('cEAN', $produto->fiscal->cEAN ?? '') }}">
                        </div>
                        <div class="col-4 mb-3">
                            <label class="form-label">Descrição (xProd)</label>
                            <input type="text" name="xProd" class="form-control"
                                value="{{ old('xProd', $produto->fiscal->xProd ?? '') }}">
                        </div>
                        <div class="col-4 mb-3">
                            <label class="form-label">NCM</label>
                            <input type="text" name="NCM" class="form-control"
                                value="{{ old('NCM', $produto->fiscal->NCM ?? '') }}">
                        </div>
                        <div class="col-4 mb-3">
                            <label class="form-label">CEST</label>
                            <input type="text" name="CEST" class="form-control"
                                value="{{ old('CEST', $produto->fiscal->CEST ?? '') }}">
                        </div>
                        <div class="col-4 mb-3">
                            <label class="form-label">CFOP</label>
                            <input type="text" name="CFOP" class="form-control"
                                value="{{ old('CFOP', $produto->fiscal->CFOP ?? '') }}">
                        </div>
                        <div class="col-4 mb-3">
                            <label class="form-label">cEAN Tributável</label>
                            <input type="text" name="cEANTrib" class="form-control"
                                value="{{ old('cEANTrib', $produto->fiscal->cEANTrib ?? '') }}">
                        </div>
                        <div class="col-4 mb-3">
                            <label class="form-label">CST</label>
                            <input type="text" name="CST" class="form-control"
                                value="{{ old('CST', $produto->fiscal->CST ?? '') }}">
                        </div>
                        <div class="col-4 mb-3">
                            <label class="form-label">% ST</label>
                            <input type="number" step="0.01" name="pST" class="form-control"
                                value="{{ old('pST', $produto->fiscal->pST ?? '') }}">
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-3">
                            <a href="{{ route('produtos.index') }}" class="btn btn-cancelar w-100">Cancelar</a>
                        </div>
                        <div class="col-3">
                            <button type="submit" class="btn btn-purple w-100">Salvar Fiscal</button>
                        </div>
                    </div>

                </form>
            </div>
            <div class="tab-pane fade" id="lotes">

                <div class="d-flex justify-content-end mb-3">
                    <button id="toggleInactiveBatches" class="btn btn-outline-secondary btn-sm">
                        Ver lotes inativos
                    </button>
                </div>

                @forelse ($produto->batches as $batch)
                    <div class="batch-card {{ !$batch->active ? 'batch-inactive d-none' : '' }}">
                        <form action="{{ route('lotes.update', $batch->id) }}" method="POST"
                            class="border rounded p-3 mb-4 lote position-relative">
                            @csrf
                            @method('PUT')

                            <button type="button" data-bs-toggle="modal"
                                data-bs-target="{{ $batch->active ? '#inativarBatchModal-' . $batch->id : '#ativarBatchModal-' . $batch->id }}"
                                style="position:absolute; top:10px; right:10px; background:none; border:none; cursor:pointer;">

                                @if ($batch->active)
                                    <!-- Lixeira -->
                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22"
                                        viewBox="0 0 24 24" fill="none" stroke="#dc3545" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="3 6 5 6 21 6"></polyline>
                                        <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"></path>
                                        <path d="M10 11v6"></path>
                                        <path d="M14 11v6"></path>
                                        <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"></path>
                                    </svg>
                                @else
                                    <!-- Reativar -->
                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22"
                                        viewBox="0 0 24 24" fill="none" stroke="#28a745" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="23 4 23 10 17 10"></polyline>
                                        <polyline points="1 20 1 14 7 14"></polyline>
                                        <path d="M3.51 9a9 9 0 0114.13-3.36L23 10"></path>
                                        <path d="M20.49 15a9 9 0 01-14.13 3.36L1 14"></path>
                                    </svg>
                                @endif
                            </button>

                            <div class="row">

                                <div class="col-4 mb-3">
                                    <label class="form-label">Quantidade</label>
                                    <input type="number" name="quantity" class="form-control"
                                        value="{{ $batch->quantity }}" {{ !$batch->active ? 'disabled' : '' }}>
                                </div>

                                <div class="col-4 mb-3">
                                    <label class="form-label">Preço de Custo</label>
                                    <input type="text" name="cost_price" class="form-control cost_price"
                                        value="{{ $batch->cost_price }}" {{ !$batch->active ? 'disabled' : '' }}>
                                </div>

                                <div class="col-4 mb-3">
                                    <label class="form-label">Preço de Venda</label>
                                    <input type="text" name="sale_price" class="form-control sale_price"
                                        value="{{ $batch->sale_price }}" {{ !$batch->active ? 'disabled' : '' }}>
                                </div>

                                <div class="col-4 mb-3">
                                    <label class="form-label">Validade</label>
                                    <input type="date" name="expiration_date" class="form-control"
                                        value="{{ $batch->expiration_date }}" {{ !$batch->active ? 'disabled' : '' }}>
                                </div>

                                <div class="col-4 mb-3">
                                    <label class="form-label">Código do Lote</label>
                                    <input type="text" name="batch_code" class="form-control"
                                        value="{{ $batch->batch_code }}" {{ !$batch->active ? 'disabled' : '' }}>
                                </div>

                                <div class="col-4 mb-3">
                                    <label class="form-label">Markup (%)</label>
                                    <input type="text" class="form-control markup_display" readonly>
                                </div>

                                @if ($batch->active)
                                    <div class="row mt-4">

                                        <div class="col-3">
                                            <a href="{{ route('produtos.index') }}" class="btn btn-cancelar w-100"
                                                style="font-size:18px;font-weight:500;">
                                                Cancelar
                                            </a>
                                        </div>

                                        <div class="col-3">
                                            <button type="submit" class="btn btn-purple w-100"
                                                style="font-size:18px;font-weight:400;">
                                                Salvar Lote
                                            </button>
                                        </div>

                                    </div>
                                @endif

                            </div>
                        </form>
                    </div>
                    {{-- MODAIS SÃO RENDERIZADOS FORA DO batch-card --}}
                    @if ($batch->active)
                        {{-- MODAL INATIVAR --}}
                        <div class="modal fade" id="inativarBatchModal-{{ $batch->id }}" tabindex="-1"
                            aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Inativar Lote</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        Tem certeza que deseja inativar o lote <strong>{{ $batch->batch_code }}</strong>?
                                        <br><br>
                                        Esta ação poderá ser revertida depois.
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Cancelar</button>
                                        <form action="{{ route('lotes.inativarLote', $batch->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-cancelar-vermelho">Sim,
                                                inativar</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        {{-- MODAL REATIVAR --}}
                        <div class="modal fade" id="ativarBatchModal-{{ $batch->id }}" tabindex="-1"
                            aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Reativar Lote</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        Deseja reativar o lote <strong>{{ $batch->batch_code }}</strong> novamente?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Cancelar</button>
                                        <form action="{{ route('lotes.ativarLote', $batch->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-success">Reativar lote</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @empty
                    <p class="text-muted mt-3">Nenhum lote cadastrado.</p>
                @endforelse

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
        <script>
            document.getElementById('toggleInactiveBatches').addEventListener('click', function() {

                const inativos = document.querySelectorAll('.batch-inactive');

                inativos.forEach(el => {
                    el.classList.toggle('d-none');
                });

                if (this.innerText === 'Ver lotes inativos') {
                    this.innerText = 'Ocultar lotes inativos';
                } else {
                    this.innerText = 'Ver lotes inativos';
                }

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

            .batch-inactive {
                opacity: 0.55;
                border: 1px dashed #ccc;
            }
        </style>
    @endsection
