@extends('components.layouts.dashboard-layout')

@section('title', 'Dashboard')

@section('content')
    <x-layouts.breadcrumb title="Dashboard" :breadcrumbs="[['name' => 'Dashboard']]" />

    <div class="container pb-5">

        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-4">
            <p class="text-muted small mb-0">
                <i class="fas fa-calendar-day me-1"></i>
                {{ now()->translatedFormat('l, d \d\e F \d\e Y') }}
            </p>
            @if ($caixaAberto)
                <span class="badge fs-6 px-3 py-2" style="background:#d1fae5;color:#065f46;">
                    <span style="display:inline-block;width:9px;height:9px;border-radius:50%;background:#22c55e;margin-right:6px;"></span>
                    Caixa aberto · desde {{ $caixaAberto->created_at->format('H:i') }}
                </span>
            @else
                <span class="badge fs-6 px-3 py-2 bg-danger-subtle text-danger">
                    <span style="display:inline-block;width:9px;height:9px;border-radius:50%;background:#ef4444;margin-right:6px;"></span>
                    Caixa fechado
                </span>
            @endif
        </div>

        <div class="row g-3 mb-4">

            <div class="col-12 col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <span class="text-muted small fw-semibold text-uppercase">Hoje</span>
                            <span class="rounded-circle d-flex align-items-center justify-content-center"
                                  style="width:2.5rem;height:2.5rem;background:#f3efff;">
                                <i class="fas fa-sun" style="color:#7212E7;"></i>
                            </span>
                        </div>
                        <div class="fw-bold" style="font-size:1.7rem;color:#7212E7;">
                            R$ {{ number_format($vendasHoje['total'], 2, ',', '.') }}
                        </div>
                        <div class="text-muted small mt-1">{{ $vendasHoje['quantidade'] }} pedido(s) finalizado(s)</div>
                        @if ($topHoje)
                            <div class="mt-3 pt-3 border-top d-flex align-items-center gap-2">
                                <i class="fas fa-trophy text-warning small"></i>
                                <span class="small"><strong>+ vendido:</strong> {{ $topHoje->produto->name ?? '—' }}
                                    <span class="text-muted">({{ number_format((float)$topHoje->qtd, 0, ',', '.') }}×)</span>
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <span class="text-muted small fw-semibold text-uppercase">Esta semana</span>
                            <span class="rounded-circle d-flex align-items-center justify-content-center"
                                  style="width:2.5rem;height:2.5rem;background:#f3efff;">
                                <i class="fas fa-calendar-week" style="color:#7212E7;"></i>
                            </span>
                        </div>
                        <div class="fw-bold" style="font-size:1.7rem;color:#7212E7;">
                            R$ {{ number_format($vendasSemana['total'], 2, ',', '.') }}
                        </div>
                        <div class="text-muted small mt-1">{{ $vendasSemana['quantidade'] }} pedido(s) finalizado(s)</div>
                        @if ($topSemana)
                            <div class="mt-3 pt-3 border-top d-flex align-items-center gap-2">
                                <i class="fas fa-trophy text-warning small"></i>
                                <span class="small"><strong>+ vendido:</strong> {{ $topSemana->produto->name ?? '—' }}
                                    <span class="text-muted">({{ number_format((float)$topSemana->qtd, 0, ',', '.') }}×)</span>
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <span class="text-muted small fw-semibold text-uppercase">Este mês</span>
                            <span class="rounded-circle d-flex align-items-center justify-content-center"
                                  style="width:2.5rem;height:2.5rem;background:#f3efff;">
                                <i class="fas fa-calendar-alt" style="color:#7212E7;"></i>
                            </span>
                        </div>
                        <div class="fw-bold" style="font-size:1.7rem;color:#7212E7;">
                            R$ {{ number_format($vendasMes['total'], 2, ',', '.') }}
                        </div>
                        <div class="text-muted small mt-1">{{ $vendasMes['quantidade'] }} pedido(s) finalizado(s)</div>
                        @if ($topMes)
                            <div class="mt-3 pt-3 border-top d-flex align-items-center gap-2">
                                <i class="fas fa-trophy text-warning small"></i>
                                <span class="small"><strong>+ vendido:</strong> {{ $topMes->produto->name ?? '—' }}
                                    <span class="text-muted">({{ number_format((float)$topMes->qtd, 0, ',', '.') }}×)</span>
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-12 col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-bottom py-3">
                        <h6 class="mb-0 fw-semibold">
                            <i class="fas fa-chart-bar me-1" style="color:#7212E7;"></i>
                            Vendas por hora — hoje
                        </h6>
                    </div>
                    <div class="card-body">
                        <canvas id="graficoHoras" height="200"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-bottom py-3">
                        <h6 class="mb-0 fw-semibold">
                            <i class="fas fa-chart-line me-1" style="color:#7212E7;"></i>
                            Faturamento diário — esta semana
                        </h6>
                    </div>
                    <div class="card-body">
                        <canvas id="graficoDias" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom py-3 d-flex align-items-center justify-content-between">
                <h6 class="mb-0 fw-semibold">
                    <i class="fas fa-user-clock me-1" style="color:#7212E7;"></i>
                    Ponto dos funcionários — hoje
                </h6>
                <a href="{{ route('pontos.registros') }}" class="btn btn-outline-secondary btn-sm">
                    Ver todos
                </a>
            </div>
            <div class="card-body p-0">
                @if ($pontosHoje->isEmpty())
                    <p class="text-muted p-4 mb-0">Nenhum ponto registrado hoje.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Funcionário</th>
                                    <th class="text-center">Entrada</th>
                                    <th class="text-center">Saída</th>
                                    <th class="text-center">Situação</th>
                                    <th class="text-center">Trabalhado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pontosHoje as $ponto)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <img src="https://ui-avatars.com/api/?name={{ urlencode($ponto->usuario->first_name ?? '?') }}&background=7212E7&color=fff&size=32"
                                                     alt="" class="rounded-circle" width="32" height="32">
                                                <span class="fw-semibold small">{{ $ponto->usuario->name ?? '—' }}</span>
                                            </div>
                                        </td>
                                        <td class="text-center">{{ $ponto->entrada_em->format('H:i') }}</td>
                                        <td class="text-center">{{ $ponto->saida_em ? $ponto->saida_em->format('H:i') : '—' }}</td>
                                        <td class="text-center">
                                            @if ($ponto->emAberto())
                                                <span class="badge" style="background:#d1fae5;color:#065f46;">
                                                    <span style="display:inline-block;width:7px;height:7px;border-radius:50%;background:#22c55e;margin-right:4px;"></span>
                                                    Trabalhando
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">Encerrado</span>
                                            @endif
                                        </td>
                                        <td class="text-center fw-semibold small">{{ $ponto->horasTrabalhadas() }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
    <script>
        const purple = '#7212E7';
        const purpleLight = 'rgba(114,18,231,0.12)';
        const purpleMid  = 'rgba(114,18,231,0.7)';

        const defaultFont = {
            family: "'Montserrat', sans-serif",
            size: 11,
        };

        new Chart(document.getElementById('graficoHoras'), {
            type: 'bar',
            data: {
                labels: @json($horasLabels),
                datasets: [{
                    label: 'Pedidos',
                    data: @json($horasQtd),
                    backgroundColor: purpleMid,
                    borderRadius: 5,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            title: ctx => ctx[0].label,
                            label: ctx => ctx.parsed.y + ' pedido(s)',
                        }
                    }
                },
                scales: {
                    x: { ticks: { font: defaultFont, maxRotation: 45, minRotation: 45 }, grid: { display: false } },
                    y: { ticks: { font: defaultFont, stepSize: 1 }, beginAtZero: true, grid: { color: '#f0f0f0' } },
                }
            }
        });

        new Chart(document.getElementById('graficoDias'), {
            type: 'line',
            data: {
                labels: @json($diasLabels),
                datasets: [{
                    label: 'Faturamento (R$)',
                    data: @json($diasTotal),
                    borderColor: purple,
                    backgroundColor: purpleLight,
                    borderWidth: 2.5,
                    pointBackgroundColor: purple,
                    pointRadius: 4,
                    fill: true,
                    tension: 0.35,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: ctx => 'R$ ' + ctx.parsed.y.toLocaleString('pt-BR', { minimumFractionDigits: 2 }),
                        }
                    }
                },
                scales: {
                    x: { ticks: { font: defaultFont }, grid: { display: false } },
                    y: {
                        ticks: {
                            font: defaultFont,
                            callback: v => 'R$ ' + v.toLocaleString('pt-BR', { minimumFractionDigits: 0 }),
                        },
                        beginAtZero: true,
                        grid: { color: '#f0f0f0' },
                    },
                }
            }
        });
    </script>
@endsection
