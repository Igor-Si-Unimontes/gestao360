@extends('components.layouts.dashboard-layout')

@section('title', 'Cozinha')

@section('content')

    <div class="cozinha-topbar px-4 py-3 d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-3">
            <i class="fas fa-fire-burner fs-4" style="color:#f59e0b;"></i>
            <div>
                <div class="fw-bold fs-5" style="color:#1e293b;">Cozinha</div>
                <div class="text-muted small" id="topbar-info">Carregando...</div>
            </div>
        </div>
        <div class="d-flex align-items-center gap-3">
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <span class="dot dot-novo"></span><span class="small text-muted">Novo</span>
                <span class="dot dot-atencao ms-2"></span><span class="small text-muted">Aguardando</span>
                <span class="dot dot-warning ms-2"></span><span class="small text-muted">Atrasado</span>
                <span class="dot dot-danger ms-2"></span><span class="small text-muted">Urgente</span>
            </div>
            <div class="text-muted small" id="ultimo-update"></div>
            <button class="btn btn-outline-secondary btn-sm" onclick="carregarPedidos()">
                <i class="fas fa-sync-alt me-1"></i> Atualizar
            </button>
        </div>
    </div>

    <div class="px-4 pb-5 mt-3">
        <div id="grid-pedidos" class="row g-3"></div>
        <div id="estado-vazio" class="text-center py-5" style="display:none;">
            <i class="fas fa-check-circle" style="font-size:3.5rem; color:#22c55e; opacity:.7;"></i>
            <p class="mt-3 fw-semibold fs-5" style="color:#166534;">Tudo em dia! Nenhum pedido em preparo.</p>
        </div>
    </div>

    <template id="tpl-card">
        <div class="col-12 col-md-6 col-xl-4 pedido-card" data-id="" data-card-type="">
            <div class="card h-100 shadow-sm card-pedido">
                <div class="card-header d-flex align-items-center justify-content-between py-2 px-3">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge-num">#<span class="c-id"></span></span>
                        <span class="badge-tipo-card c-tipo"></span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge-tempo">
                            <i class="fas fa-clock me-1"></i>
                            <span class="c-minutos"></span> min
                        </span>
                        <span class="badge-alerta c-alerta"></span>
                    </div>
                </div>

                <div class="card-body px-3 py-2">
                    <ul class="lista-itens mb-0"></ul>
                    <div class="obs-box c-obs" style="display:none;">
                        <i class="fas fa-comment-alt me-1"></i>
                        <span class="c-obs-texto"></span>
                    </div>
                </div>

                <div class="card-footer d-flex gap-2 px-3 py-2">
                    <button class="btn btn-success btn-sm flex-fill btn-pronto">
                        <i class="fas fa-check me-1"></i> Pronto
                    </button>
                </div>
            </div>
        </div>
    </template>

    <script>
        const CSRF        = '{{ csrf_token() }}';
        const URL_PEDIDOS = '{{ route('cozinha.pedidos') }}';
        const URL_PRONTO  = (id) => `/cozinha/${id}/pronto`;

        const TIPO_MAP = {
            RAPIDA:   { icon: 'fa-cash-register', label: 'Balcão',   cls: 'tipo-balcao' },
            DELIVERY: { icon: 'fa-motorcycle',    label: 'Delivery', cls: 'tipo-delivery' },
            MESA:     { icon: 'fa-chair',         label: 'Mesa',     cls: 'tipo-mesa' },
        };

        const ALERTA_MAP = {
            normal:   { cls: 'badge-novo',     label: 'Novo' },
            atencao:  { cls: 'badge-atencao',  label: 'Aguardando' },
            atrasado: { cls: 'badge-atrasado', label: 'Atrasado' },
            urgente:  { cls: 'badge-urgente',  label: 'Urgente' },
        };

        const COR_BORDA = {
            normal:   '#7212E7',
            atencao:  '#0ea5e9',
            atrasado: '#f59e0b',
            urgente:  '#ef4444',
        };

        function criarCard(p) {
            const tpl   = document.getElementById('tpl-card');
            const clone = tpl.content.cloneNode(true);
            const div   = clone.querySelector('.pedido-card');

            div.dataset.id       = p.id;
            div.dataset.cardType = p.card_type;

            const card = div.querySelector('.card-pedido');
            card.style.borderLeft = `5px solid ${COR_BORDA[p.alerta]}`;
            if (p.alerta === 'urgente') card.classList.add('pulso');

            div.querySelector('.c-id').textContent = p.id;

            const tipoInfo = TIPO_MAP[p.tipo] || TIPO_MAP.RAPIDA;
            const tipoEl   = div.querySelector('.c-tipo');
            tipoEl.className = `badge-tipo-card ${tipoInfo.cls}`;

            let tipoLabel = `<i class="fas ${tipoInfo.icon} me-1"></i>${tipoInfo.label}`;
            if (p.mesa) tipoLabel += ` ${p.mesa}`;

            if (p.card_type === 'mesa_itens') {
                tipoLabel += `&nbsp;<span style="opacity:.7;font-size:.72rem;">(itens em preparo)</span>`;
            }
            tipoLabel += `&nbsp;·&nbsp;<span style="opacity:.7;font-size:.75rem;">${p.criado_em}</span>`;
            tipoEl.innerHTML = tipoLabel;

            div.querySelector('.c-minutos').textContent = p.minutos;

            const alertaInfo = ALERTA_MAP[p.alerta] || ALERTA_MAP.normal;
            const alertaEl   = div.querySelector('.c-alerta');
            alertaEl.className   = `badge-alerta ${alertaInfo.cls}`;
            alertaEl.textContent = alertaInfo.label;

            const ul = div.querySelector('.lista-itens');
            p.itens.forEach(item => {
                const li = document.createElement('li');
                li.innerHTML = `<span class="qtd">${item.quantidade}x</span> ${item.name}`;
                ul.appendChild(li);
            });

            if (p.observacao) {
                div.querySelector('.c-obs').style.display  = '';
                div.querySelector('.c-obs-texto').textContent = p.observacao;
            }

            div.querySelector('.btn-pronto').addEventListener('click', function () {
                marcarPronto(p.id, div);
            });

            return div;
        }

        function carregarPedidos() {
            fetch(URL_PEDIDOS, { headers: { Accept: 'application/json' } })
                .then(r => r.json())
                .then(lista => {
                    const grid   = document.getElementById('grid-pedidos');
                    const vazio  = document.getElementById('estado-vazio');
                    const novosIds = new Set(lista.map(p => p.id));

                    grid.querySelectorAll('.pedido-card').forEach(el => {
                        if (!novosIds.has(parseInt(el.dataset.id))) {
                            el.classList.add('saindo');
                            setTimeout(() => el.remove(), 400);
                        }
                    });

                    lista.forEach(p => {
                        const existente = grid.querySelector(`.pedido-card[data-id="${p.id}"]`);
                        if (existente) {
                            existente.querySelector('.c-minutos').textContent = p.minutos;
                            const ai = ALERTA_MAP[p.alerta] || ALERTA_MAP.normal;
                            const ae = existente.querySelector('.c-alerta');
                            ae.className   = `badge-alerta ${ai.cls}`;
                            ae.textContent = ai.label;
                            existente.querySelector('.card-pedido').style.borderLeft = `5px solid ${COR_BORDA[p.alerta]}`;
                            if (p.alerta === 'urgente') existente.querySelector('.card-pedido').classList.add('pulso');
                            else existente.querySelector('.card-pedido').classList.remove('pulso');
                        } else {
                            const card = criarCard(p);
                            card.classList.add('entrando');
                            grid.appendChild(card);
                            setTimeout(() => card.classList.remove('entrando'), 400);
                        }
                    });

                    vazio.style.display = lista.length === 0 ? 'block' : 'none';

                    const n = lista.length;
                    document.getElementById('topbar-info').textContent =
                        n === 0 ? 'Nenhum pedido em preparo'
                                : `${n} pedido${n > 1 ? 's' : ''} em preparo`;

                    const agora = new Date();
                    document.getElementById('ultimo-update').textContent =
                        'Atualizado às ' + agora.toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
                })
                .catch(() => {
                    document.getElementById('topbar-info').textContent = 'Erro ao atualizar. Verifique a conexão.';
                });
        }

        function marcarPronto(id, cardEl) {
            const btn = cardEl.querySelector('.btn-pronto');
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Finalizando…';

            fetch(URL_PRONTO(id), {
                method: 'PATCH',
                headers: { 'X-CSRF-TOKEN': CSRF, Accept: 'application/json' },
            })
            .then(async r => {
                const data = await r.json();
                if (!r.ok) throw new Error(data.error || 'Erro ao finalizar.');
                return data;
            })
            .then(() => {
                cardEl.classList.add('saindo');
                setTimeout(() => {
                    cardEl.remove();
                    carregarPedidos();
                }, 400);
            })
            .catch(err => {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-check me-1"></i> Pronto';
                alert(err.message);
            });
        }

        carregarPedidos();
        setInterval(carregarPedidos, 30000);
    </script>

@endsection

@section('styles')
<style>
    .cozinha-topbar {
        background: #fff;
        border-bottom: 1px solid #e2e8f0;
        position: sticky; top: 0; z-index: 10;
    }

    .dot { width:10px; height:10px; border-radius:50%; display:inline-block; }
    .dot-novo     { background:#7212E7; }
    .dot-atencao  { background:#0ea5e9; }
    .dot-warning  { background:#f59e0b; }
    .dot-danger   { background:#ef4444; }

    .card-pedido {
        border-radius:12px; border:1px solid #e2e8f0;
        transition: box-shadow .2s;
    }
    .card-pedido:hover { box-shadow:0 4px 20px rgba(0,0,0,.10) !important; }
    .card-header {
        background:#f8fafc; border-bottom:1px solid #e2e8f0;
        border-radius:12px 12px 0 0 !important;
    }

    .badge-num { font-size:1.1rem; font-weight:700; color:#1e293b; }

    .badge-tipo-card {
        border-radius:20px; padding:2px 10px;
        font-size:.78rem; font-weight:600; white-space:nowrap;
    }
    .tipo-balcao   { background:#ede9fe; color:#6d28d9; }
    .tipo-delivery { background:#ffedd5; color:#c2410c; }
    .tipo-mesa     { background:#dbeafe; color:#1d4ed8; }

    .badge-tempo { font-size:.82rem; font-weight:600; color:#475569; }

    .badge-alerta {
        border-radius:20px; padding:3px 10px;
        font-size:.75rem; font-weight:700; white-space:nowrap;
    }
    .badge-novo      { background:#ede9fe; color:#6d28d9; }
    .badge-atencao   { background:#e0f2fe; color:#0369a1; }
    .badge-atrasado  { background:#fef3c7; color:#b45309; }
    .badge-urgente   { background:#fee2e2; color:#b91c1c; }

    .lista-itens { list-style:none; padding:0; margin:6px 0; }
    .lista-itens li {
        padding:6px 0; border-bottom:1px solid #f1f5f9;
        font-size:.95rem; color:#1e293b;
        display:flex; align-items:center; gap:8px;
    }
    .lista-itens li:last-child { border-bottom:none; }
    .qtd {
        background:#f1f5f9; color:#475569;
        border-radius:6px; padding:1px 7px;
        font-size:.82rem; font-weight:700;
        min-width:32px; text-align:center;
    }

    .obs-box {
        margin-top:8px; background:#fffbeb;
        border:1px solid #fde68a; border-radius:8px;
        padding:7px 12px; font-size:.85rem; color:#92400e;
        display:flex; align-items:flex-start; gap:6px;
    }

    @keyframes pulsar {
        0%,100% { box-shadow:0 0 0 0 rgba(239,68,68,.4); }
        50%      { box-shadow:0 0 0 8px rgba(239,68,68,0); }
    }
    .pulso { animation:pulsar 1.5s infinite; }

    .entrando { animation:slideIn .35s ease forwards; }
    @keyframes slideIn {
        from { opacity:0; transform:translateY(-12px); }
        to   { opacity:1; transform:translateY(0); }
    }
    .saindo { animation:fadeOut .35s ease forwards; }
    @keyframes fadeOut { to { opacity:0; transform:scale(.95); } }
</style>
@endsection
