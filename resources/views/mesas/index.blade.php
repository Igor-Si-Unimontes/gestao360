@extends('components.layouts.dashboard-layout')

@section('title', 'Mesas')

@section('content')
    <x-layouts.breadcrumb
        title="Mesas"
        :breadcrumbs="[['name' => 'Mesas']]"
    />

    <div class="container ">

        <div class="d-flex align-items-center gap-4 mb-4">
            <h5 class="mb-0 me-3">Mapa de Mesas</h5>
            <span class="d-flex align-items-center gap-1 text-muted small">
                <span class="rounded-circle d-inline-block" style="width:12px;height:12px;background:#28a745;"></span> Livre
            </span>
            <span class="d-flex align-items-center gap-1 text-muted small">
                <span class="rounded-circle d-inline-block" style="width:12px;height:12px;background:#dc3545;"></span> Ocupada
            </span>
        </div>

        @if ($mesas->isEmpty())
            <div class="alert alert-info">
                Nenhuma mesa cadastrada. Contate o administrador para configurar as mesas.
            </div>
        @else
            <div class="row g-3">
                @foreach ($mesas as $mesa)
                    @php
                        $livre     = $mesa->isLivre();
                        $venda     = $mesa->vendaAberta;
                        $cor       = $livre ? 'success' : 'danger';
                        $corBg     = $livre ? '#f0fff4' : '#fff5f5';
                        $totalFmt  = $venda ? 'R$ ' . number_format($venda->valor_total, 2, ',', '.') : null;
                        $numItens  = $venda ? $venda->itens()->count() : 0;
                    @endphp

                    <div class="col-6 col-sm-4 col-md-3 col-xl-2">
                        <div class="card h-100 shadow-sm"
                            style="border: 2px solid var(--bs-{{ $cor }}); background: {{ $corBg }}; border-radius: 12px;">
                            <div class="card-body d-flex flex-column align-items-center justify-content-between p-3 gap-2">

                                <div class="text-center">
                                    <div style="font-size: 2.2rem; font-weight: 700; color: var(--bs-{{ $cor }});">
                                        {{ $mesa->numero }}
                                    </div>
                                    <div class="text-muted" style="font-size: 0.75rem;">
                                        <i class="fas fa-users me-1"></i>{{ $mesa->capacidade }} lugares
                                    </div>
                                </div>

                                <span class="badge bg-{{ $cor }} px-3 py-1" style="font-size: 0.8rem;">
                                    {{ $livre ? 'Livre' : 'Ocupada' }}
                                </span>

                                @if (!$livre && $venda)
                                    <div class="text-center">
                                        <div class="fw-bold text-danger" style="font-size: 1.05rem;">{{ $totalFmt }}</div>
                                        <div class="text-muted" style="font-size: 0.75rem;">
                                            {{ $numItens }} {{ $numItens === 1 ? 'item' : 'itens' }}
                                        </div>
                                        <div class="text-muted" style="font-size: 0.7rem;">
                                            desde {{ $venda->created_at->format('H:i') }}
                                        </div>
                                    </div>
                                @endif

                                @if ($livre)
                                    <form action="{{ route('mesas.abrir', $mesa) }}" method="POST" class="w-100">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm w-100">
                                            <i class="fas fa-door-open me-1"></i> Abrir Mesa
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('mesas.comanda', $mesa) }}" class="btn btn-danger btn-sm w-100">
                                        <i class="fas fa-clipboard-list me-1"></i> Ver Comanda
                                    </a>
                                @endif

                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </div>
@endsection
