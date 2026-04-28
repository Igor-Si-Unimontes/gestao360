@extends('components.layouts.cardapio-vitrine')

@section('title', $cardapio->nome)

@section('content')
    <header class="vitrine-hero py-5 mb-4">
        <div class="container text-center">
            <p class="text-white-50 small mb-2 text-uppercase tracking-wide">Cardápio</p>
            <h1 class="display-6 fw-bold mb-2">{{ $cardapio->nome }}</h1>
            @if ($cardapio->descricao)
                <p class="lead mb-0 opacity-90 mx-auto" style="max-width: 42rem;">{{ $cardapio->descricao }}</p>
            @endif
        </div>
    </header>

    <main class="container pb-5">
        @php
            $grupos = $itens->groupBy('categoria');
            $ordemCats = array_keys(\App\Models\CardapioItem::$categorias);
        @endphp
        @if ($itens->isEmpty())
            <div class="text-center text-muted py-5">
                <i class="fas fa-utensils fa-3x mb-3 opacity-50"></i>
                <p class="mb-0">Nenhum item disponível no momento.</p>
            </div>
        @else
            @foreach ($ordemCats as $cat)
                @php $lista = $grupos->get($cat, collect()); @endphp
                @if ($lista->isNotEmpty())
                    <h2 class="h4 fw-bold mb-3 mt-4" style="color:#4c1d95;">
                        {{ \App\Models\CardapioItem::$categorias[$cat] ?? $cat }}
                    </h2>
                    <div class="row g-4 mb-2">
                        @foreach ($lista as $item)
                            <div class="col-md-6 col-xl-4">
                                <article class="card border-0 shadow-sm h-100 overflow-hidden">
                                    <div class="ratio ratio-4x3 bg-light">
                                        <img src="{{ $item->urlImagemExibicao() }}" alt="{{ $item->nome }}"
                                             class="object-fit-cover" loading="lazy">
                                    </div>
                                    <div class="card-body d-flex flex-column">
                                        <h3 class="h5 fw-bold mb-2" style="color:#4c1d95;">{{ $item->nome }}</h3>
                                        @if ($item->descricao)
                                            <p class="text-muted small flex-grow-1 mb-3">{{ $item->descricao }}</p>
                                        @endif
                                        <div class="d-flex justify-content-between align-items-end mt-auto pt-2 border-top">
                                            <span class="text-muted small">
                                                <i class="fas fa-users me-1"></i>
                                                @if ($item->serve_pessoas === 1)
                                                    Serve 1 pessoa
                                                @else
                                                    Serve {{ $item->serve_pessoas }} pessoas
                                                @endif
                                            </span>
                                            <span class="fw-bold fs-5" style="color:#7212E7;">
                                                R$ {{ number_format((float) $item->valor, 2, ',', '.') }}
                                            </span>
                                        </div>
                                    </div>
                                </article>
                            </div>
                        @endforeach
                    </div>
                @endif
            @endforeach
        @endif

    </main>
@endsection
