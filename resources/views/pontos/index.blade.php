@extends('components.layouts.dashboard-layout')

@section('title', 'Ponto Eletrônico')

@section('content')
    <x-layouts.breadcrumb
        title="Bater Ponto"
        :breadcrumbs="[
            ['name' => 'Ponto', 'route' => 'pontos.index'],
            ['name' => 'Bater Ponto'],
        ]"
    >
    </x-layouts.breadcrumb>

    <div class="container pb-5">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div>
                    <div class="text-muted small">Usuário logado</div>
                    <div class="fw-bold fs-5">{{ auth()->user()->name }}</div>
                    @if ($pontoAberto)
                        <div class="text-muted small mt-1">
                            Entrada às {{ $pontoAberto->entrada_em->format('d/m/Y H:i') }}
                            · Trabalhando há {{ $pontoAberto->horasTrabalhadas() }}
                        </div>
                    @else
                        <div class="text-muted small mt-1">Nenhum ponto em aberto no momento.</div>
                    @endif
                </div>

                <div class="d-flex gap-2">
                    @if ($pontoAberto)
                        <form action="{{ route('pontos.fechar') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger">
                                <i class="fas fa-stop-circle me-1"></i> Fechar Ponto
                            </button>
                        </form>
                    @else
                        <form action="{{ route('pontos.abrir') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-purple text-white">
                                <i class="fas fa-play-circle me-1"></i> Abrir Ponto
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="fw-semibold mb-3">
                    <i class="fas fa-info-circle me-1" style="color:#7212E7;"></i>
                    Como funciona
                </h6>
                <p class="text-muted mb-2">
                    Use este botão para registrar o início e o fim do seu expediente.
                </p>
            </div>
        </div>
    </div>
@endsection
