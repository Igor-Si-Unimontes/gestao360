@extends('components.layouts.dashboard-layout')

@section('title', 'Abrir Caixa')

@section('content')
    <x-layouts.breadcrumb
        title="Abrir Caixa"
        :breadcrumbs="[
            ['name' => 'Caixa', 'route' => 'caixas.index'],
            ['name' => 'Abrir'],
        ]"
    />

    <div class="container" style="max-width:680px;">

        <div class="d-flex align-items-center gap-3 mb-4 p-3 rounded"
             style="background:#f8f5ff; border:1px solid #ddd6fe;">
            <div style="width:40px; height:40px; background:#7212E7; border-radius:50%;
                        display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <i class="fas fa-user text-white"></i>
            </div>
            <div>
                <div class="text-muted small">Responsável pela abertura</div>
                <div class="fw-semibold">{{ auth()->user()->name }}</div>
                <div class="text-muted" style="font-size:.78rem;">
                    {{ now()->format('d/m/Y \à\s H:i') }}
                </div>
            </div>
        </div>

        <form action="{{ route('caixas.abrir') }}" method="POST" id="form_abertura">
            @csrf

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="mb-0 fw-semibold">
                        <i class="fas fa-money-bill-wave me-1" style="color:#7212E7;"></i>
                        Contagem de Notas
                    </h6>
                </div>
                <div class="card-body">
                    <x-caixa-denominacao-row key="nota_100" label="R$ 100" valor="100,00" />
                    <x-caixa-denominacao-row key="nota_50"  label="R$ 50"  valor="50,00"  />
                    <x-caixa-denominacao-row key="nota_20"  label="R$ 20"  valor="20,00"  />
                    <x-caixa-denominacao-row key="nota_10"  label="R$ 10"  valor="10,00"  />
                    <x-caixa-denominacao-row key="nota_5"   label="R$ 5"   valor="5,00"   />
                    <x-caixa-denominacao-row key="nota_2"   label="R$ 2"   valor="2,00"   />
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
                    <x-caixa-denominacao-row key="moeda_100" label="R$ 1,00"  valor="1,00"  />
                    <x-caixa-denominacao-row key="moeda_050" label="R$ 0,50"  valor="0,50"  />
                    <x-caixa-denominacao-row key="moeda_025" label="R$ 0,25"  valor="0,25"  />
                    <x-caixa-denominacao-row key="moeda_010" label="R$ 0,10"  valor="0,10"  />
                    <x-caixa-denominacao-row key="moeda_005" label="R$ 0,05"  valor="0,05"  />
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4" style="border-left:4px solid #7212E7 !important;">
                <div class="card-body d-flex align-items-center justify-content-between py-3 px-4">
                    <div class="fw-semibold fs-5">Total em Espécie</div>
                    <div class="fw-bold fs-3" style="color:#7212E7;" id="total_geral">R$ 0,00</div>
                </div>
            </div>

            <div class="d-flex gap-2 justify-content-end">
                <a href="{{ route('caixas.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                <button type="submit" class="btn btn-purple text-white px-4">
                    <i class="fas fa-lock-open me-1"></i> Confirmar Abertura
                </button>
            </div>
        </form>
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
            document.getElementById('total_geral').textContent = brl(total);
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
