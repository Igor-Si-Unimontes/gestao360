<div class="relatorio-periodo-stack">
    @if (in_array('resumo', $ver, true) && $resumo !== null)
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom py-3 d-flex align-items-center gap-2">
                <i class="fas fa-receipt fa-lg" style="color:#7212E7;"></i>
                <h6 class="mb-0 fw-semibold">Resumo de vendas</h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-6 col-md-4 col-lg-2">
                        <div class="border rounded p-3 h-100 bg-light">
                            <div class="text-muted small">Pedidos</div>
                            <div class="fw-bold fs-5">{{ $resumo['quantidade'] }}</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-4 col-lg-2">
                        <div class="border rounded p-3 h-100 bg-light">
                            <div class="text-muted small">Total recebido</div>
                            <div class="fw-bold fs-5" style="color:#7212E7;">R$ {{ number_format($resumo['valor_total'], 2, ',', '.') }}</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-4 col-lg-2">
                        <div class="border rounded p-3 h-100 bg-light">
                            <div class="text-muted small">Dinheiro</div>
                            <div class="fw-bold fs-6 text-success">R$ {{ number_format($resumo['dinheiro'], 2, ',', '.') }}</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-4 col-lg-2">
                        <div class="border rounded p-3 h-100 bg-light">
                            <div class="text-muted small">PIX</div>
                            <div class="fw-bold fs-6" style="color:#7c3aed;">R$ {{ number_format($resumo['pix'], 2, ',', '.') }}</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-4 col-lg-2">
                        <div class="border rounded p-3 h-100 bg-light">
                            <div class="text-muted small">Cartão</div>
                            <div class="fw-bold fs-6 text-primary">R$ {{ number_format($resumo['cartao'], 2, ',', '.') }}</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-4 col-lg-2">
                        <div class="border rounded p-3 h-100 bg-light">
                            <div class="text-muted small">Taxas entrega</div>
                            <div class="fw-bold fs-6 text-warning">R$ {{ number_format($resumo['taxa_entrega'], 2, ',', '.') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if (in_array('top_produtos', $ver, true) && $topProdutos !== null)
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom py-3 d-flex align-items-center gap-2">
                <i class="fas fa-trophy fa-lg" style="color:#7212E7;"></i>
                <h6 class="mb-0 fw-semibold">Produtos mais vendidos</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Produto</th>
                                <th class="text-end">Qtd</th>
                                <th class="text-end">Receita</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($topProdutos as $i => $row)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ optional($row->produto)->name ?? '—' }}</td>
                                    <td class="text-end">{{ number_format((float) $row->quantidade_total, 2, ',', '.') }}</td>
                                    <td class="text-end fw-semibold">R$ {{ number_format((float) $row->receita_total, 2, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">Sem dados no período.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    @if (in_array('pico', $ver, true) && $pico !== null)
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom py-3 d-flex align-items-center gap-2">
                <i class="fas fa-chart-line fa-lg" style="color:#7212E7;"></i>
                <h6 class="mb-0 fw-semibold">Horário de pico</h6>
            </div>
            <div class="card-body">
                @if (count($pico))
                    <p class="text-muted small mb-3">Horas com maior quantidade de vendas finalizadas neste intervalo.</p>
                    <div class="row g-2">
                        @foreach ($pico as $p)
                            <div class="col-6 col-md-4 col-lg-2">
                                <div class="border rounded p-3 text-center h-100" style="background:#f8f5ff;">
                                    <div class="fw-bold fs-4" style="color:#7212E7;">{{ str_pad($p['hora'], 2, '0', STR_PAD_LEFT) }}:00</div>
                                    <div class="text-muted small">{{ $p['total'] }} venda(s)</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted mb-0">Sem vendas finalizadas neste intervalo.</p>
                @endif
            </div>
        </div>
    @endif

    @if (in_array('caixa', $ver, true) && $caixa !== null)
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom py-3 d-flex align-items-center gap-2">
                <i class="fas fa-money-bill-wave fa-lg text-success"></i>
                <h6 class="mb-0 fw-semibold">Caixa (dinheiro): entrada × saída</h6>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Entrada (vendas em dinheiro)</span>
                    <span class="fw-semibold text-success">R$ {{ number_format($caixa['vendas_dinheiro'], 2, ',', '.') }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Saída (sangrias)</span>
                    <span class="fw-semibold text-danger">R$ {{ number_format($caixa['sangrias'], 2, ',', '.') }}</span>
                </div>
                <hr>
                <div class="d-flex justify-content-between mb-2">
                    <span class="fw-semibold">Saldo do movimento</span>
                    <span class="fw-bold" style="color:#7212E7;">R$ {{ number_format($caixa['liquido_movimento'], 2, ',', '.') }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted small">Aberturas de caixa (troco inicial)</span>
                    <span class="small">R$ {{ number_format($caixa['aberturas_caixa'], 2, ',', '.') }}</span>
                </div>
            </div>
        </div>
    @endif

    @if (in_array('markup', $ver, true) && $markup !== null)
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom py-3 d-flex align-items-center gap-2">
                <i class="fas fa-scale-balanced fa-lg" style="color:#7212E7;"></i>
                <h6 class="mb-0 fw-semibold">Faturamento de produtos (bruto × líquido estimado)</h6>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Receita (itens vendidos)</span>
                    <span class="fw-semibold">R$ {{ number_format($markup['receita_produtos'], 2, ',', '.') }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Custo estimado (lotes)</span>
                    <span class="fw-semibold text-danger">R$ {{ number_format($markup['custo_estimado'], 2, ',', '.') }}</span>
                </div>
                <hr>
                <div class="d-flex justify-content-between mb-2">
                    <span class="fw-semibold">Lucro estimado</span>
                    <span class="fw-bold text-success">R$ {{ number_format($markup['lucro_estimado'], 2, ',', '.') }}</span>
                </div>
                <div class="text-muted small">
                    Markup sobre custo:
                    @if ($markup['markup_percent'] !== null)
                        <strong>{{ number_format($markup['markup_percent'], 1, ',', '.') }}%</strong>
                    @else
                        <span>—</span> <span class="text-muted">(sem custo cadastrado)</span>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>
