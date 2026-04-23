@extends('components.layouts.dashboard-layout')

@section('title', 'Relatórios')

@section('content')
    <x-layouts.breadcrumb
        title="Relatórios"
        :breadcrumbs="[['name' => 'Relatórios']]"
    />

    <div class="container pb-5">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <h6 class="fw-semibold mb-3">
                    <i class="fas fa-sliders-h me-1" style="color:#7212E7;"></i> Montar relatório
                </h6>
                <form method="get" action="{{ route('relatorios.index') }}" id="form-relatorios" class="row g-4">
                    <div class="col-12">
                        <label class="form-label small text-muted mb-2 d-block">Período analisado</label>
                        <div class="d-flex flex-wrap gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="periodo" id="periodo-semana" value="semana"
                                       {{ $periodo === 'semana' ? 'checked' : '' }}>
                                <label class="form-check-label" for="periodo-semana">Somente semana</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="periodo" id="periodo-mes" value="mes"
                                       {{ $periodo === 'mes' ? 'checked' : '' }}>
                                <label class="form-check-label" for="periodo-mes">Somente mês</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="periodo" id="periodo-ambos" value="ambos"
                                       {{ $periodo === 'ambos' ? 'checked' : '' }}>
                                <label class="form-check-label" for="periodo-ambos">Semana e mês <span class="text-muted">(em abas)</span></label>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small text-muted mb-1" for="inp-semana">Referência da semana</label>
                        <input type="date" name="semana" id="inp-semana" value="{{ $refSemana }}" class="form-control">
                        <div class="form-text small">
                            Intervalo: {{ $inicioSemana->format('d/m/Y') }} — {{ $fimSemana->format('d/m/Y') }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small text-muted mb-1" for="inp-mes">Mês de referência</label>
                        <input type="month" name="mes" id="inp-mes" value="{{ $refMes }}" class="form-control">
                        <div class="form-text small">
                            Intervalo: {{ $inicioMes->format('d/m/Y') }} — {{ $fimMes->format('d/m/Y') }}
                        </div>
                    </div>

                    <div class="col-12">
                        <style>
                            .relatorio-opt-list { max-width: 42rem; }
                            .relatorio-opt-item {
                                display: flex;
                                align-items: stretch;
                                gap: 0.875rem;
                                margin-bottom: 0.5rem;
                                padding: 0.85rem 1rem;
                                border: 2px solid #e8e6ef;
                                border-radius: 0.75rem;
                                background: #fff;
                                cursor: pointer;
                                transition: border-color .18s ease, background .18s ease, box-shadow .18s ease, transform .12s ease;
                            }
                            .relatorio-opt-item:hover {
                                border-color: #c9b8f5;
                                box-shadow: 0 4px 12px rgba(114, 18, 231, 0.06);
                            }
                            .relatorio-opt-item:active { transform: scale(0.992); }
                            .relatorio-opt-item:has(.relatorio-opt-input:checked) {
                                border-color: #7212E7;
                                background: linear-gradient(135deg, #faf8ff 0%, #ffffff 55%);
                                box-shadow: 0 4px 16px rgba(114, 18, 231, 0.1);
                            }
                            .relatorio-opt-item:has(.relatorio-opt-input:focus-visible) {
                                outline: 2px solid #7212E7;
                                outline-offset: 2px;
                            }
                            .relatorio-opt-icon {
                                width: 2.75rem;
                                height: 2.75rem;
                                border-radius: 0.65rem;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                flex-shrink: 0;
                                background: #f3efff;
                                color: #7212E7;
                                font-size: 1.1rem;
                            }
                            .relatorio-opt-item:has(.relatorio-opt-input:checked) .relatorio-opt-icon {
                                background: #7212E7;
                                color: #fff;
                            }
                            .relatorio-opt-input {
                                width: 1.35rem;
                                height: 1.35rem;
                                margin: 0;
                                cursor: pointer;
                                accent-color: #7212E7;
                                flex-shrink: 0;
                            }
                            .relatorio-opt-toggle {
                                width: 2.75rem;
                                align-self: center;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                            }
                        </style>
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
                            <label class="form-label small text-muted mb-0">O que exibir</label>
                            <div class="btn-group btn-group-sm" role="group" aria-label="Selecionar blocos">
                                <button type="button" class="btn btn-outline-secondary" id="relatorio-ver-todos">Marcar todos</button>
                                <button type="button" class="btn btn-outline-secondary" id="relatorio-ver-nenhum">Desmarcar</button>
                            </div>
                        </div>
                        <p class="text-muted small mb-3">Clique em qualquer lugar do card para incluir ou remover o bloco do relatório.</p>
                        <div class="relatorio-opt-list">
                            <label class="relatorio-opt-item" for="ver-resumo">
                                <span class="relatorio-opt-icon"><i class="fas fa-receipt"></i></span>
                                <span class="flex-grow-1 min-w-0">
                                    <span class="fw-semibold d-block">Resumo de vendas</span>
                                    <span class="text-muted small d-block">Pedidos, total recebido, dinheiro, PIX, cartão e taxas de entrega.</span>
                                </span>
                                <span class="relatorio-opt-toggle">
                                    <input class="form-check-input relatorio-opt-input" type="checkbox" name="ver[]" value="resumo" id="ver-resumo"
                                           {{ in_array('resumo', $ver, true) ? 'checked' : '' }}>
                                </span>
                            </label>
                            <label class="relatorio-opt-item" for="ver-top">
                                <span class="relatorio-opt-icon"><i class="fas fa-trophy"></i></span>
                                <span class="flex-grow-1 min-w-0">
                                    <span class="fw-semibold d-block">Produtos mais vendidos</span>
                                    <span class="text-muted small d-block">Ranking por quantidade com receita por produto.</span>
                                </span>
                                <span class="relatorio-opt-toggle">
                                    <input class="form-check-input relatorio-opt-input" type="checkbox" name="ver[]" value="top_produtos" id="ver-top"
                                           {{ in_array('top_produtos', $ver, true) ? 'checked' : '' }}>
                                </span>
                            </label>
                            <label class="relatorio-opt-item" for="ver-pico">
                                <span class="relatorio-opt-icon"><i class="fas fa-chart-line"></i></span>
                                <span class="flex-grow-1 min-w-0">
                                    <span class="fw-semibold d-block">Horário de pico</span>
                                    <span class="text-muted small d-block">Horas do dia com mais vendas finalizadas no período.</span>
                                </span>
                                <span class="relatorio-opt-toggle">
                                    <input class="form-check-input relatorio-opt-input" type="checkbox" name="ver[]" value="pico" id="ver-pico"
                                           {{ in_array('pico', $ver, true) ? 'checked' : '' }}>
                                </span>
                            </label>
                            <label class="relatorio-opt-item" for="ver-caixa">
                                <span class="relatorio-opt-icon"><i class="fas fa-money-bill-wave"></i></span>
                                <span class="flex-grow-1 min-w-0">
                                    <span class="fw-semibold d-block">Caixa em dinheiro</span>
                                    <span class="text-muted small d-block">Entrada (vendas em dinheiro), sangrias e aberturas de caixa.</span>
                                </span>
                                <span class="relatorio-opt-toggle">
                                    <input class="form-check-input relatorio-opt-input" type="checkbox" name="ver[]" value="caixa" id="ver-caixa"
                                           {{ in_array('caixa', $ver, true) ? 'checked' : '' }}>
                                </span>
                            </label>
                            <label class="relatorio-opt-item" for="ver-markup">
                                <span class="relatorio-opt-icon"><i class="fas fa-scale-balanced"></i></span>
                                <span class="flex-grow-1 min-w-0">
                                    <span class="fw-semibold d-block">Faturamento e markup</span>
                                    <span class="text-muted small d-block">Receita dos itens, custo estimado pelos lotes e lucro aproximado.</span>
                                </span>
                                <span class="relatorio-opt-toggle">
                                    <input class="form-check-input relatorio-opt-input" type="checkbox" name="ver[]" value="markup" id="ver-markup"
                                           {{ in_array('markup', $ver, true) ? 'checked' : '' }}>
                                </span>
                            </label>
                        </div>
                    </div>

                    <div class="col-12 d-flex flex-wrap gap-2 align-items-center">
                        <button type="submit" name="filtros_submit" value="1" class="btn btn-purple text-white px-4">
                            <i class="fas fa-play me-1"></i> Gerar relatório
                        </button>
                        <a href="{{ route('relatorios.index') }}" class="btn btn-outline-secondary">Limpar filtros</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="alert alert-light border small mb-4" role="alert">
            <i class="fas fa-info-circle me-1 text-secondary"></i>
            Consideramos apenas vendas <strong>FINALIZADAS</strong>. Custo e markup usam o custo médio dos <strong>lotes ativos</strong> (estimativa).
        </div>

        @if ($periodo === 'semana')
            <div class="border-start border-4 ps-3 mb-3" style="border-color:#7212E7 !important;">
                <h5 class="fw-semibold mb-0">Semana</h5>
                <p class="text-muted small mb-0">{{ $inicioSemana->format('d/m/Y') }} — {{ $fimSemana->format('d/m/Y') }}</p>
            </div>
            @include('relatorios.partials.conteudo-periodo', [
                'ver' => $ver,
                'resumo' => $resumoSemana,
                'topProdutos' => $topProdutosSemana,
                'pico' => $picoSemana,
                'caixa' => $caixaSemana,
                'markup' => $markupSemana,
            ])
        @elseif ($periodo === 'mes')
            <div class="border-start border-4 ps-3 mb-3" style="border-color:#7212E7 !important;">
                <h5 class="fw-semibold mb-0">Mês</h5>
                <p class="text-muted small mb-0">{{ $inicioMes->format('d/m/Y') }} a {{ $fimMes->format('d/m/Y') }}</p>
            </div>
            @include('relatorios.partials.conteudo-periodo', [
                'ver' => $ver,
                'resumo' => $resumoMes,
                'topProdutos' => $topProdutosMes,
                'pico' => $picoMes,
                'caixa' => $caixaMes,
                'markup' => $markupMes,
            ])
        @else
            <div class="card border-0 shadow-sm overflow-hidden">
                <ul class="nav nav-tabs px-2 pt-2 mb-0 border-bottom bg-white" id="relTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active fw-semibold px-4 py-3" id="tab-semana-btn" data-bs-toggle="tab" data-bs-target="#tab-semana-pane" type="button" role="tab">
                            <i class="fas fa-calendar-week me-2" style="color:#7212E7;"></i>Semana
                            <span class="d-block small fw-normal text-muted">{{ $inicioSemana->format('d/m') }} — {{ $fimSemana->format('d/m/Y') }}</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-semibold px-4 py-3" id="tab-mes-btn" data-bs-toggle="tab" data-bs-target="#tab-mes-pane" type="button" role="tab">
                            <i class="fas fa-calendar-alt me-2" style="color:#7212E7;"></i>Mês
                            <span class="d-block small fw-normal text-muted">{{ $inicioMes->format('m/Y') }}</span>
                        </button>
                    </li>
                </ul>
                <div class="tab-content bg-white p-4">
                <div class="tab-pane fade show active" id="tab-semana-pane" role="tabpanel">
                    @include('relatorios.partials.conteudo-periodo', [
                        'ver' => $ver,
                        'resumo' => $resumoSemana,
                        'topProdutos' => $topProdutosSemana,
                        'pico' => $picoSemana,
                        'caixa' => $caixaSemana,
                        'markup' => $markupSemana,
                    ])
                </div>
                <div class="tab-pane fade" id="tab-mes-pane" role="tabpanel">
                    @include('relatorios.partials.conteudo-periodo', [
                        'ver' => $ver,
                        'resumo' => $resumoMes,
                        'topProdutos' => $topProdutosMes,
                        'pico' => $picoMes,
                        'caixa' => $caixaMes,
                        'markup' => $markupMes,
                    ])
                </div>
                </div>
            </div>
        @endif
    </div>

    <script>
        (function () {
            const form = document.getElementById('form-relatorios');
            if (!form) return;
            const boxes = () => [...form.querySelectorAll('input[name="ver[]"]')];
            document.getElementById('relatorio-ver-todos')?.addEventListener('click', function () {
                boxes().forEach(function (c) { c.checked = true; });
            });
            document.getElementById('relatorio-ver-nenhum')?.addEventListener('click', function () {
                boxes().forEach(function (c) { c.checked = false; });
            });
        })();
    </script>
@endsection
