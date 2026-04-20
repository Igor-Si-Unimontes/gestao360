@extends('components.layouts.dashboard-layout')

@section('title', 'Caixa')

@section('content')
    <x-layouts.breadcrumb
        title="Caixa"
        :breadcrumbs="[['name' => 'Caixa', 'route' => 'caixas.index']]"
    >
        @if ($caixaAberto)
            <a href="{{ route('caixas.fechar.form') }}" class="btn btn-outline-danger btn-sm">
                <i class="fas fa-lock me-1"></i> Fechar Caixa
            </a>
        @else
            <a href="{{ route('caixas.abrir.form') }}" class="btn btn-purple btn-sm text-white">
                <i class="fas fa-lock-open me-1"></i> Abrir Caixa
            </a>
        @endif
    </x-layouts.breadcrumb>

    <div class="container-fluid px-4 pb-5">

        @if ($caixaAberto)
            @php
                $dinheiro = $caixaAberto->totalDinheiro();
                $cartao   = $caixaAberto->totalCartao();
                $pix      = $caixaAberto->totalPix();
                $totalVendas = $dinheiro + $cartao + $pix;
            @endphp

            <div class="alert d-flex align-items-center gap-3 mb-4"
                 style="background:#f0fdf4; border:1.5px solid #86efac; border-radius:12px; padding:16px 20px;">
                <div style="width:44px; height:44px; background:#22c55e; border-radius:50%;
                            display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <i class="fas fa-cash-register text-white fs-5"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="fw-bold" style="color:#166534; font-size:1rem;">
                        Caixa #{{ $caixaAberto->id }} — ABERTO
                    </div>
                    <div class="text-muted small">
                        Aberto {{ $caixaAberto->tempoAberto() }}
                        · {{ $caixaAberto->created_at->format('d/m/Y \à\s H:i') }}
                        · por {{ $caixaAberto->usuario->name ?? '—' }}
                    </div>
                </div>
                <div class="text-end">
                    <div class="text-muted small">Saldo de abertura</div>
                    <div class="fw-bold fs-5" style="color:#166534;">
                        R$ {{ number_format($caixaAberto->valor_abertura, 2, ',', '.') }}
                    </div>
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="card border-0 shadow-sm h-100" style="border-left:4px solid #22c55e !important;">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="metric-icon" style="background:#dcfce7; color:#16a34a;">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                            <div>
                                <div class="text-muted small">Dinheiro</div>
                                <div class="fw-bold fs-5">R$ {{ number_format($dinheiro, 2, ',', '.') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="card border-0 shadow-sm h-100" style="border-left:4px solid #3b82f6 !important;">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="metric-icon" style="background:#dbeafe; color:#2563eb;">
                                <i class="fas fa-credit-card"></i>
                            </div>
                            <div>
                                <div class="text-muted small">Cartão</div>
                                <div class="fw-bold fs-5">R$ {{ number_format($cartao, 2, ',', '.') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="card border-0 shadow-sm h-100" style="border-left:4px solid #8b5cf6 !important;">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="metric-icon" style="background:#ede9fe; color:#7c3aed;">
                                <i class="fas fa-qrcode"></i>
                            </div>
                            <div>
                                <div class="text-muted small">PIX</div>
                                <div class="fw-bold fs-5">R$ {{ number_format($pix, 2, ',', '.') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="card border-0 shadow-sm h-100" style="border-left:4px solid #f59e0b !important;">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="metric-icon" style="background:#fef3c7; color:#d97706;">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <div>
                                <div class="text-muted small">Total Vendido</div>
                                <div class="fw-bold fs-5">R$ {{ number_format($totalVendas, 2, ',', '.') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h6 class="fw-semibold mb-3">
                        <i class="fas fa-calculator me-1" style="color:#7212E7;"></i>
                        Projeção de Fechamento
                    </h6>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="bg-light rounded p-3 text-center">
                                <div class="text-muted small">Abertura (espécie)</div>
                                <div class="fw-semibold">R$ {{ number_format($caixaAberto->valor_abertura, 2, ',', '.') }}</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="bg-light rounded p-3 text-center">
                                <div class="text-muted small">+ Vendas em Dinheiro</div>
                                <div class="fw-semibold text-success">+ R$ {{ number_format($dinheiro, 2, ',', '.') }}</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 text-center rounded" style="background:#ede9fe;">
                                <div class="text-muted small">= Espécie esperada no caixa</div>
                                <div class="fw-bold fs-5" style="color:#7212E7;">
                                    R$ {{ number_format($caixaAberto->valorEsperadoFechamento(), 2, ',', '.') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        @else
            <div class="alert d-flex align-items-center gap-3 mb-4"
                 style="background:#fef2f2; border:1.5px solid #fca5a5; border-radius:12px; padding:16px 20px;">
                <div style="width:44px; height:44px; background:#ef4444; border-radius:50%;
                            display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <i class="fas fa-lock text-white fs-5"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="fw-bold" style="color:#991b1b;">Nenhum caixa aberto</div>
                    <div class="text-muted small">Pedidos não podem ser realizados sem um caixa aberto.</div>
                </div>
                <a href="{{ route('caixas.abrir.form') }}" class="btn btn-purple btn-sm text-white">
                    <i class="fas fa-lock-open me-1"></i> Abrir Caixa
                </a>
            </div>
        @endif

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom d-flex align-items-center justify-content-between py-3">
                <h6 class="mb-0 fw-semibold">
                    <i class="fas fa-history me-1" style="color:#7212E7;"></i> Histórico de Caixas
                </h6>
            </div>
            <div class="card-body p-0">
                @if ($historico->isEmpty())
                    <div class="text-center text-muted py-5">
                        <i class="fas fa-cash-register" style="font-size:2rem; opacity:.3;"></i>
                        <p class="mt-2 small">Nenhum caixa registrado ainda.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Abertura</th>
                                    <th>Fechamento</th>
                                    <th>Responsável</th>
                                    <th>Abertura (R$)</th>
                                    <th>Fechamento (R$)</th>
                                    <th>Status</th>
                                    <th>Diferença</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($historico as $cx)
                                    <tr>
                                        <td class="text-muted fw-semibold">#{{ $cx->id }}</td>
                                        <td>
                                            <div>{{ $cx->created_at->format('d/m/Y') }}</div>
                                            <div class="text-muted small">{{ $cx->created_at->format('H:i') }}</div>
                                        </td>
                                        <td>
                                            @if ($cx->fechado_em)
                                                <div>{{ $cx->fechado_em->format('d/m/Y') }}</div>
                                                <div class="text-muted small">{{ $cx->fechado_em->format('H:i') }}</div>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td>{{ $cx->usuario->name ?? '—' }}</td>
                                        <td>R$ {{ number_format($cx->valor_abertura, 2, ',', '.') }}</td>
                                        <td>
                                            @if ($cx->valor_fechamento !== null)
                                                R$ {{ number_format($cx->valor_fechamento, 2, ',', '.') }}
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($cx->status === 'ABERTO')
                                                <span class="badge bg-success">Aberto</span>
                                            @else
                                                <span class="badge bg-secondary">Fechado</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($cx->status === 'FECHADO' && $cx->valor_fechamento !== null)
                                                @php
                                                    $esperadoHist = round((float)$cx->valor_abertura + $cx->vendas()->where('status','FINALIZADA')->where('forma_pagamento','DINHEIRO')->sum('valor_total'), 2);
                                                    $difHist = round((float)$cx->valor_fechamento - $esperadoHist, 2);
                                                @endphp
                                                @if ($difHist == 0)
                                                    <span class="text-success fw-semibold">R$ 0,00 ✓</span>
                                                @elseif ($difHist > 0)
                                                    <span class="text-warning fw-semibold">+ R$ {{ number_format($difHist, 2, ',', '.') }}</span>
                                                @else
                                                    <span class="text-danger fw-semibold">- R$ {{ number_format(abs($difHist), 2, ',', '.') }}</span>
                                                @endif
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

    </div>
@endsection

@section('styles')
<style>
    .metric-icon {
        width: 44px; height: 44px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.1rem; flex-shrink: 0;
    }
</style>
@endsection
