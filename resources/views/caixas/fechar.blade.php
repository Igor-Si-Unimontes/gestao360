@extends('components.layouts.dashboard-layout')

@section('title', 'Fechar Caixa')

@section('content')
    <x-layouts.breadcrumb
        title="Fechar Caixa #{{ $caixa->id }}"
        :breadcrumbs="[
            ['name' => 'Caixa', 'route' => 'caixas.index'],
            ['name' => 'Fechar'],
        ]"
    />

    <div class="container">

        <div class="d-flex align-items-center gap-3 mb-4 p-3 rounded"
             style="background:#f8f5ff; border:1px solid #ddd6fe;">
            <div style="width:40px; height:40px; background:#7212E7; border-radius:50%;
                        display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <i class="fas fa-user text-white"></i>
            </div>
            <div class="flex-grow-1">
                <div class="text-muted small">Caixa aberto por</div>
                <div class="fw-semibold">{{ $caixa->usuario->name ?? auth()->user()->name }}</div>
                <div class="text-muted" style="font-size:.78rem;">
                    Aberto em {{ $caixa->created_at->format('d/m/Y \à\s H:i') }}
                    · {{ $caixa->created_at->diffForHumans() }}
                </div>
            </div>
            <div class="text-end">
                <div class="text-muted small">Responsável pelo fechamento</div>
                <div class="fw-semibold">{{ auth()->user()->name }}</div>
                <div class="text-muted" style="font-size:.78rem;">{{ now()->format('H:i') }}</div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="row g-3 text-center">
                    <div class="col-4">
                        <div class="text-muted small">Abertura</div>
                        <div class="fw-semibold">R$ {{ number_format($caixa->valor_abertura, 2, ',', '.') }}</div>
                    </div>
                    <div class="col-4">
                        <div class="text-muted small">+ Vendas em Dinheiro</div>
                        <div class="fw-semibold text-success">+ R$ {{ number_format($caixa->totalDinheiro(), 2, ',', '.') }}</div>
                    </div>
                    <div class="col-4">
                        <div class="text-muted small">= Espécie esperada</div>
                        <div class="fw-bold" style="color:#7212E7;">
                            R$ {{ number_format($esperado, 2, ',', '.') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <form action="{{ route('caixas.fechar') }}" method="POST" id="form_fechamento">
            @csrf

            <p class="text-muted small mb-3">
                <i class="fas fa-info-circle me-1"></i>
                Conte fisicamente todo o dinheiro no caixa e informe as quantidades abaixo.
                O total deve ser igual ao valor esperado.
            </p>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="mb-0 fw-semibold">
                        <i class="fas fa-money-bill-wave me-1" style="color:#7212E7;"></i>
                        Contagem de Notas
                    </h6>
                </div>
                <div class="card-body">
                    <x-caixa-denominacao-row key="nota_100" label="R$ 100" valor="100,00" :quantidade="0" />
                    <x-caixa-denominacao-row key="nota_50"  label="R$ 50"  valor="50,00"  :quantidade="0" />
                    <x-caixa-denominacao-row key="nota_20"  label="R$ 20"  valor="20,00"  :quantidade="0" />
                    <x-caixa-denominacao-row key="nota_10"  label="R$ 10"  valor="10,00"  :quantidade="0" />
                    <x-caixa-denominacao-row key="nota_5"   label="R$ 5"   valor="5,00"   :quantidade="0" />
                    <x-caixa-denominacao-row key="nota_2"   label="R$ 2"   valor="2,00"   :quantidade="0" />
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="mb-0 fw-semibold">
                        <i class="fas fa-circle me-1" style="color:#7212E7;"></i>
                        Contagem de Moedas
                    </h6>
                </div>
                <div class="card-body">
                    <x-caixa-denominacao-row key="moeda_100" label="R$ 1,00" valor="1,00"  :quantidade="0" />
                    <x-caixa-denominacao-row key="moeda_050" label="R$ 0,50" valor="0,50"  :quantidade="0" />
                    <x-caixa-denominacao-row key="moeda_025" label="R$ 0,25" valor="0,25"  :quantidade="0" />
                    <x-caixa-denominacao-row key="moeda_010" label="R$ 0,10" valor="0,10"  :quantidade="0" />
                    <x-caixa-denominacao-row key="moeda_005" label="R$ 0,05" valor="0,05"  :quantidade="0" />
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4 text-center">
                            <div class="text-muted small">Total contado</div>
                            <div class="fw-bold fs-4" id="total_geral">R$ 0,00</div>
                        </div>
                        <div class="col-md-4 text-center">
                            <div class="text-muted small">Esperado</div>
                            <div class="fw-bold fs-4 text-muted">
                                R$ {{ number_format($esperado, 2, ',', '.') }}
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
                            <div class="text-muted small">Diferença</div>
                            <div class="fw-bold fs-4" id="diferenca">R$ 0,00</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2 justify-content-end">
                <a href="{{ route('caixas.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                <button type="button" class="btn btn-danger px-4"
                        onclick="new bootstrap.Modal(document.getElementById('modalConfirmarFechamento')).show()">
                    <i class="fas fa-lock me-1"></i> Confirmar Fechamento
                </button>
            </div>
        </form>
    </div>

    <div class="modal fade" id="modalConfirmarFechamento" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <h6 class="modal-title fw-semibold text-danger">
                        <i class="fas fa-lock me-2"></i>Fechar Caixa
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <div class="mb-3" style="font-size:2.5rem;color:#dc3545;">
                        <i class="fas fa-cash-register"></i>
                    </div>
                    <p class="mb-1 fw-semibold">Confirma o fechamento do caixa?</p>
                    <p class="text-muted small mb-0">Essa ação encerrará o caixa atual. Novos pedidos não poderão ser realizados até a abertura de um novo caixa.</p>
                </div>
                <div class="modal-footer border-0 pt-0 justify-content-center gap-2">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="button" class="btn btn-danger px-4" onclick="document.getElementById('form_fechamento').submit()">
                        <i class="fas fa-lock me-1"></i> Confirmar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const DENOMINACOES = {
            nota_100:  100.00,
            nota_50:    50.00,
            nota_20:    20.00,
            nota_10:    10.00,
            nota_5:      5.00,
            nota_2:      2.00,
            moeda_100:   1.00,
            moeda_050:   0.50,
            moeda_025:   0.25,
            moeda_010:   0.10,
            moeda_005:   0.05,
        };

        const ESPERADO = {{ $esperado }};
        const brl = (v) => v.toLocaleString('pt-BR', { style:'currency', currency:'BRL' });

        function recalcular() {
            let total = 0;
            for (const [key, valor] of Object.entries(DENOMINACOES)) {
                const qtd = parseInt(document.getElementById('inp_' + key)?.value || 0) || 0;
                const sub = qtd * valor;
                total += sub;

                const subEl = document.getElementById('sub_' + key);
                if (subEl) subEl.textContent = brl(sub);
            }

            const diferenca = Math.round((total - ESPERADO) * 100) / 100;

            document.getElementById('total_geral').textContent = brl(total);

            const difEl = document.getElementById('diferenca');
            difEl.textContent = (diferenca >= 0 ? '+' : '') + brl(diferenca);
            difEl.className = 'fw-bold fs-4 '
                + (diferenca === 0 ? 'text-success'
                   : diferenca > 0  ? 'text-warning'
                   :                  'text-danger');
        }

        document.querySelectorAll('.btn-mais').forEach(btn => {
            btn.addEventListener('click', () => {
                const inp = document.getElementById('inp_' + btn.dataset.key);
                inp.value = Math.max(0, parseInt(inp.value || 0) + 1);
                recalcular();
            });
        });
        document.querySelectorAll('.btn-menos').forEach(btn => {
            btn.addEventListener('click', () => {
                const inp = document.getElementById('inp_' + btn.dataset.key);
                inp.value = Math.max(0, parseInt(inp.value || 0) - 1);
                recalcular();
            });
        });
        document.querySelectorAll('.inp-qtd').forEach(inp => {
            inp.addEventListener('input', recalcular);
        });

        recalcular();
    </script>
@endsection
