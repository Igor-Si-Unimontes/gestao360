@extends('components.layouts.dashboard-layout')

@section('title', 'Histórico de Pedidos')

@section('content')
    <x-layouts.breadcrumb
        title="Histórico de Pedidos"
        :breadcrumbs="[['name' => 'Pedidos', 'route' => 'pedidos.index']]"
    >
        <div class="d-flex align-items-center gap-2">
            @if (!$verTodos)
                <span class="text-muted small me-1">
                    <i class="fas fa-calendar-day me-1"></i>
                    Exibindo pedidos de hoje
                </span>
                <a href="{{ route('pedidos.index', ['ver' => 'todos']) }}"
                   class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-history me-1"></i> Ver todos
                </a>
            @else
                <span class="text-muted small me-1">
                    <i class="fas fa-history me-1"></i>
                    Exibindo todos os pedidos
                </span>
                <a href="{{ route('pedidos.index') }}" class="btn btn-purple-main btn-sm text-white">
                    <i class="fas fa-calendar-day me-1"></i> Somente hoje
                </a>
            @endif
        </div>
    </x-layouts.breadcrumb>

    <div class="container bg-white rounded" style="padding: 30px;">

        @if ($pedidos->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="fas fa-receipt" style="font-size:2.5rem; opacity:.3;"></i>
                <p class="mt-3">
                    {{ $verTodos ? 'Nenhum pedido encontrado.' : 'Nenhum pedido realizado hoje ainda.' }}
                </p>
                @if (!$verTodos)
                    <a href="{{ route('pedidos.index', ['ver' => 'todos']) }}"
                       class="btn btn-outline-secondary btn-sm mt-1">
                        Ver pedidos anteriores
                    </a>
                @endif
            </div>
        @else

        <table id="pedidosTable" class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Realizado</th>
                    <th>Tipo</th>
                    <th>Detalhes</th>
                    <th>Taxa Entrega</th>
                    <th>Total</th>
                    <th>Pagamento</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pedidos as $pedido)
                    <tr>
                        <td class="fw-semibold text-muted">#{{ $pedido->id }}</td>

                        <td>
                            @if (!$verTodos)
                                <div class="fw-semibold">{{ $pedido->created_at->format('H:i') }}</div>
                                <div class="text-muted" style="font-size:0.78rem;">
                                    {{ $pedido->created_at->diffForHumans() }}
                                </div>
                            @else
                                <div>{{ $pedido->created_at->format('d/m/Y') }}</div>
                                <div class="text-muted" style="font-size:0.78rem;">
                                    {{ $pedido->created_at->format('H:i') }}
                                    · {{ $pedido->created_at->diffForHumans() }}
                                </div>
                            @endif
                        </td>

                        <td>
                            @if ($pedido->tipo === 'MESA')
                                <span class="badge-tipo tipo-mesa">
                                    <i class="fas fa-chair me-1"></i> Mesa
                                </span>
                            @elseif ($pedido->tipo === 'DELIVERY')
                                <span class="badge-tipo tipo-delivery">
                                    <i class="fas fa-motorcycle me-1"></i> Delivery
                                </span>
                            @else
                                <span class="badge-tipo tipo-balcao">
                                    <i class="fas fa-cash-register me-1"></i> Balcão
                                </span>
                            @endif
                        </td>

                        <td>
                            @if ($pedido->tipo === 'MESA' && $pedido->mesa)
                                Mesa {{ $pedido->mesa->numero }}
                            @elseif ($pedido->tipo === 'DELIVERY')
                                <div style="max-width:180px;" class="text-truncate" title="{{ $pedido->endereco }}">
                                    {{ $pedido->endereco }}
                                </div>
                                @if ($pedido->bairro)
                                    <div class="text-muted" style="font-size:0.78rem;">{{ $pedido->bairro->nome }}</div>
                                @endif
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>

                        <td>
                            @if ($pedido->taxa_entrega > 0)
                                R$ {{ number_format($pedido->taxa_entrega, 2, ',', '.') }}
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>

                        <td class="fw-semibold">
                            R$ {{ number_format($pedido->valor_total, 2, ',', '.') }}
                        </td>

                        <td>
                            @php
                                $pgLabels = [
                                    'DINHEIRO'      => ['💵', 'Dinheiro'],
                                    'PIX'           => ['<i class="fas fa-qrcode"></i>', 'PIX'],
                                    'CARTAO_DEBITO' => ['<i class="fas fa-credit-card"></i>', 'Débito'],
                                    'CARTAO_CREDITO'=> ['<i class="fas fa-credit-card"></i>', 'Crédito'],
                                ];
                                $pg = $pgLabels[$pedido->forma_pagamento] ?? null;
                            @endphp
                            @if ($pg)
                                <span>{!! $pg[0] !!} {{ $pg[1] }}</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>

                        <td>
                            <span class="badge-status status-{{ strtolower(str_replace('_', '-', $pedido->status)) }}">
                                @switch($pedido->status)
                                    @case('ABERTA')      <i class="fas fa-circle-dot me-1"></i> Aberta       @break
                                    @case('EM_PREPARO')  <i class="fas fa-fire-burner me-1"></i> Em Preparo  @break
                                    @case('FINALIZADA')  <i class="fas fa-check-circle me-1"></i> Finalizado  @break
                                    @case('CANCELADA')   <i class="fas fa-ban me-1"></i> Cancelado           @break
                                @endswitch
                            </span>
                            @if ($pedido->status === 'ABERTA')
                                @php $qtdCozinha = $pedido->itens->where('status', 'EM_PREPARO')->count(); @endphp
                                @if ($qtdCozinha > 0)
                                    <br>
                                    <span class="badge bg-warning text-dark mt-1" style="font-size:.7rem;">
                                        <i class="fas fa-fire-burner me-1"></i>{{ $qtdCozinha }} na cozinha
                                    </span>
                                @endif
                            @endif
                        </td>

                        <td>
                            <button class="btn btn-sm btn-icon" title="Ver detalhes"
                                onclick="verDetalhes({{ $pedido->id }})">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                    fill="none" stroke="#7212E7" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="3"></circle>
                                    <path d="M2 12s4-8 10-8 10 8 10 8-4 8-10 8-10-8-10-8z"></path>
                                </svg>
                            </button>

                            @if (in_array($pedido->status, ['ABERTA', 'EM_PREPARO']))
                                <button class="btn btn-sm btn-icon" title="Atualizar status"
                                    data-bs-toggle="modal" data-bs-target="#modalStatus-{{ $pedido->id }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                        fill="none" stroke="#7212E7" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path d="M12 20h9"></path>
                                        <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z"></path>
                                    </svg>
                                </button>
                            @endif

                            @if ($pedido->status === 'CANCELADA')
                                <button class="btn btn-sm btn-icon" title="Excluir"
                                    data-bs-toggle="modal" data-bs-target="#modalDelete-{{ $pedido->id }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                        fill="none" stroke="#dc3545" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <polyline points="3 6 5 6 21 6"></polyline>
                                        <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"></path>
                                        <path d="M10 11v6"></path><path d="M14 11v6"></path>
                                        <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"></path>
                                    </svg>
                                </button>
                            @endif
                        </td>
                    </tr>

                    @if (in_array($pedido->status, ['ABERTA', 'EM_PREPARO']))
                        <div class="modal fade" id="modalStatus-{{ $pedido->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-sm">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h6 class="modal-title">Pedido #{{ $pedido->id }} — Atualizar Status</h6>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('pedidos.status', $pedido) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <div class="modal-body">
                                            <div class="mb-2 text-muted small">Status atual:
                                                <strong>{{ $pedido->status }}</strong>
                                            </div>
                                            <label class="form-label">Novo status</label>
                                            <select name="status" class="form-select form-select-sm">
                                                @if ($pedido->status === 'ABERTA')
                                                    <option value="EM_PREPARO">🔥 Em Preparo (cozinha)</option>
                                                    <option value="CANCELADA">❌ Cancelar pedido</option>
                                                @elseif ($pedido->status === 'EM_PREPARO')
                                                    <option value="FINALIZADA">✅ Finalizado (pago)</option>
                                                    <option value="CANCELADA">❌ Cancelar pedido</option>
                                                @endif
                                            </select>

                                            @if ($pedido->status === 'EM_PREPARO' && !$pedido->forma_pagamento)
                                                <div id="pgDiv-{{ $pedido->id }}" class="mt-3" style="display:none;">
                                                    <label class="form-label">Forma de Pagamento</label>
                                                    <select name="forma_pagamento" class="form-select form-select-sm">
                                                        <option value="DINHEIRO">💵 Dinheiro</option>
                                                        <option value="PIX">QR PIX</option>
                                                        <option value="CARTAO_DEBITO">Cartão Débito</option>
                                                        <option value="CARTAO_CREDITO">Cartão Crédito</option>
                                                    </select>
                                                </div>
                                                <script>
                                                    document.querySelector('#modalStatus-{{ $pedido->id }} select[name="status"]')
                                                        .addEventListener('change', function () {
                                                            document.getElementById('pgDiv-{{ $pedido->id }}').style.display =
                                                                this.value === 'FINALIZADA' ? 'block' : 'none';
                                                        });
                                                </script>
                                            @endif
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-secondary btn-sm"
                                                data-bs-dismiss="modal">Voltar</button>
                                            <button type="submit" class="btn btn-purple btn-sm">Confirmar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($pedido->status === 'CANCELADA')
                        <div class="modal fade" id="modalDelete-{{ $pedido->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-sm">
                                <div class="modal-content">
                                    <div class="modal-header border-0">
                                        <h6 class="modal-title text-danger">Excluir Pedido #{{ $pedido->id }}?</h6>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body pt-0">
                                        <p class="text-muted small">Esta ação não pode ser desfeita.</p>
                                    </div>
                                    <div class="modal-footer border-0 pt-0">
                                        <button class="btn btn-outline-secondary btn-sm"
                                            data-bs-dismiss="modal">Não</button>
                                        <form action="{{ route('pedidos.destroy', $pedido) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Sim, excluir</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                @endforeach
            </tbody>
        </table>

        @endif 

    </div>

    <div class="modal fade" id="modalDetalhes" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDetalhesTitle">Detalhes do Pedido</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="modalDetalhesBody">
                    <div class="text-center py-4">
                        <div class="spinner-border text-purple" role="status"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.1/css/dataTables.bootstrap5.css">
    <style>
        #pedidosTable th, #pedidosTable td { padding: 16px 20px; }

        .btn-icon { padding: 4px 6px; border: none; background: transparent; }
        .btn-icon:hover { background: #f3e9fd; border-radius: 6px; }

        .badge-tipo {
            border-radius: 20px; padding: 4px 12px;
            font-size: 13px; font-weight: 500;
            white-space: nowrap; display: inline-block;
        }
        .tipo-balcao  { background: #ede9fe; color: #6d28d9; }
        .tipo-mesa    { background: #dbeafe; color: #1d4ed8; }
        .tipo-delivery{ background: #ffedd5; color: #c2410c; }

        .badge-status {
            border-radius: 20px; padding: 5px 12px;
            font-size: 13px; font-weight: 500;
            white-space: nowrap; display: inline-flex;
            align-items: center; gap: 4px;
        }
        .status-aberta     { background: #dbeafe; color: #1d4ed8; }
        .status-em-preparo { background: #fef3c7; color: #b45309; }
        .status-finalizada { background: #d1fae5; color: #065f46; }
        .status-cancelada  { background: #fee2e2; color: #b91c1c; }
    </style>
@endsection

@section('scripts')
    <script src="https://cdn.datatables.net/2.3.1/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.3.1/js/dataTables.bootstrap5.js"></script>
    <script>
        $(document).ready(function () {
            if (!$('#pedidosTable').length) return;
            $('#pedidosTable').DataTable({
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/2.0.0/i18n/pt-BR.json',
                    search: '',
                    searchPlaceholder: 'Busque aqui...',
                    lengthMenu: 'Linhas _MENU_',
                },
                order: [[0, 'desc']],
                paging: true,
                searching: true,
                lengthChange: true,
                pageLength: 15,
                pagingType: 'simple_numbers',
                dom: "<'row'<'col-sm-12 d-flex mb-3'f>>" +
                     "<'row'<'col-sm-12'tr>>" +
                     "<'row mt-5'<'col-sm-12 d-flex justify-content-end align-items-center'" +
                     "<'dt-length me-3'l><'dt-pagination'p>>>",
            });
        });

        function verDetalhes(id) {
            document.getElementById('modalDetalhesTitle').textContent = 'Pedido #' + id;
            document.getElementById('modalDetalhesBody').innerHTML =
                '<div class="text-center py-4"><div class="spinner-border" style="color:#7212E7;" role="status"></div></div>';

            const modal = new bootstrap.Modal(document.getElementById('modalDetalhes'));
            modal.show();

            fetch(`/pedidos/${id}`, { headers: { Accept: 'application/json' } })
                .then(r => r.json())
                .then(d => {
                    const tipoLabel = { RAPIDA: 'Balcão', MESA: 'Mesa', DELIVERY: 'Delivery' };
                    const statusLabel = {
                        ABERTA: '<span class="badge-status status-aberta">Aberta</span>',
                        EM_PREPARO: '<span class="badge-status status-em-preparo">Em Preparo</span>',
                        FINALIZADA: '<span class="badge-status status-finalizada">Finalizado</span>',
                        CANCELADA: '<span class="badge-status status-cancelada">Cancelado</span>',
                    };

                    let itensHtml = d.itens.map(i => `
                        <tr>
                            <td>${i.name}</td>
                            <td>${i.quantidade}</td>
                            <td>R$ ${i.valor_unitario}</td>
                            <td class="fw-semibold">R$ ${i.valor_total}</td>
                        </tr>`).join('');

                    document.getElementById('modalDetalhesBody').innerHTML = `
                        <div class="row g-3 mb-4">
                            <div class="col-6 col-md-3">
                                <div class="text-muted small">Tipo</div>
                                <div class="fw-semibold">${tipoLabel[d.tipo] ?? d.tipo}</div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="text-muted small">Status</div>
                                <div>${statusLabel[d.status] ?? d.status}</div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="text-muted small">Data</div>
                                <div>${d.criado_em}</div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="text-muted small">Pagamento</div>
                                <div>${d.forma_pagamento ?? '—'}</div>
                            </div>
                            ${d.tipo === 'MESA' ? `
                            <div class="col-6 col-md-3">
                                <div class="text-muted small">Mesa</div>
                                <div>Mesa ${d.mesa}</div>
                            </div>` : ''}
                            ${d.tipo === 'DELIVERY' ? `
                            <div class="col-12 col-md-6">
                                <div class="text-muted small">Endereço</div>
                                <div>${d.endereco ?? '—'}</div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="text-muted small">Bairro</div>
                                <div>${d.bairro ?? '—'}</div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="text-muted small">Taxa Entrega</div>
                                <div>R$ ${d.taxa_entrega}</div>
                            </div>` : ''}
                        </div>
                        <h6 class="mb-2">Itens</h6>
                        <table class="table table-sm table-bordered">
                            <thead class="table-light">
                                <tr><th>Produto</th><th>Qtd.</th><th>Unit.</th><th>Total</th></tr>
                            </thead>
                            <tbody>${itensHtml}</tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end fw-semibold">Total Geral</td>
                                    <td class="fw-bold" style="color:#7212E7;">R$ ${d.valor_total}</td>
                                </tr>
                            </tfoot>
                        </table>`;
                })
                .catch(() => {
                    document.getElementById('modalDetalhesBody').innerHTML =
                        '<div class="alert alert-danger">Erro ao carregar detalhes.</div>';
                });
        }
    </script>
@endsection
