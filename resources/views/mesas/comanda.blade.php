@extends('components.layouts.dashboard-layout')

@section('title', 'Comanda — Mesa ' . $mesa->numero)

@section('content')
    <x-layouts.breadcrumb
        title="Comanda — Mesa {{ $mesa->numero }}"
        :breadcrumbs="[
            ['name' => 'Mesas', 'route' => 'mesas.index'],
            ['name' => 'Mesa ' . $mesa->numero],
        ]"
    />

    <div class="container">

        <div class="d-flex flex-wrap align-items-center gap-3 mb-4">
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-danger px-3 py-2" style="font-size: 1rem;">
                    <i class="fas fa-utensils me-1"></i> Mesa {{ $mesa->numero }}
                </span>
                <span class="text-muted small">
                    Aberta às {{ $venda->created_at->format('H:i') }}
                    ({{ $venda->created_at->diffForHumans() }})
                </span>
            </div>
            <div class="ms-auto d-flex gap-2">
                <button class="btn btn-outline-danger btn-sm" onclick="confirmarCancelar()">
                    <i class="fas fa-times me-1"></i> Cancelar Mesa
                </button>
                <button class="btn btn-purple btn-sm" onclick="abrirFecharMesa()">
                    <i class="fas fa-check-circle me-1"></i> Fechar Mesa
                </button>
            </div>
        </div>

        <div class="row g-4">

            <div class="col-lg-5">
                <div class="bg-white rounded shadow-sm p-4">
                    <h6 class="mb-3 fw-semibold">
                        <i class="fas fa-plus-circle me-1" style="color:#7212E7;"></i> Adicionar Produto
                    </h6>

                    <div class="mb-3">
                        <label class="form-label">Produto</label>
                        <select id="sel_produto" class="form-select">
                            <option value="">Selecione um produto</option>
                            @foreach ($produtos as $produto)
                                <option value="{{ $produto->id }}">{{ $produto->name }}</option>
                            @endforeach
                        </select>
                        <p id="msg_estoque" class="text-muted small mt-1" style="display:none;"></p>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label">Valor Unit.</label>
                            <input type="text" id="inp_valor" class="form-control" readonly placeholder="R$ 0,00">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Quantidade</label>
                            <input type="number" id="inp_quantidade" class="form-control"
                                min="0.001" step="0.001" value="1">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Subtotal</label>
                        <input type="text" id="inp_total" class="form-control" readonly placeholder="R$ 0,00">
                    </div>

                    <button type="button" class="btn btn-purple w-100" id="btn_adicionar" onclick="adicionarItem()">
                        <i class="fas fa-plus me-1"></i> Adicionar à Comanda
                    </button>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="bg-white rounded shadow-sm p-4">
                    <h6 class="mb-3 fw-semibold">
                        <i class="fas fa-clipboard-list me-1" style="color:#7212E7;"></i> Itens da Comanda
                    </h6>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Produto</th>
                                    <th style="width:110px">Unit.</th>
                                    <th style="width:90px">Qtd.</th>
                                    <th style="width:110px">Total</th>
                                    <th class="text-center" style="width:60px"></th>
                                </tr>
                            </thead>
                            <tbody id="tabela_itens"></tbody>
                        </table>
                    </div>

                    <div class="mt-3 pt-3 border-top d-flex justify-content-between align-items-center">
                        <span class="text-muted">Total da Comanda</span>
                        <span id="res_total" class="fw-bold fs-5" style="color:#7212E7;">R$ 0,00</span>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="modal fade" id="modalFecharMesa" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-check-circle me-1 text-success"></i>
                        Fechar Mesa {{ $mesa->numero }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('mesas.fechar', $mesa) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-light border text-center mb-3">
                            <div class="text-muted small">Total a pagar</div>
                            <div id="modal_total" class="fw-bold fs-4" style="color:#7212E7;">R$ 0,00</div>
                        </div>

                        <label class="form-label fw-semibold">
                            Forma de Pagamento <span class="text-danger">*</span>
                        </label>
                        <div class="d-flex flex-wrap gap-2 mt-1">
                            <input type="radio" class="btn-check" name="forma_pagamento"
                                id="fm_dinheiro" value="DINHEIRO" autocomplete="off">
                            <label class="btn btn-outline-secondary" for="fm_dinheiro">
                                <i class="fas fa-money-bill-wave me-1"></i> Dinheiro
                            </label>

                            <input type="radio" class="btn-check" name="forma_pagamento"
                                id="fm_pix" value="PIX" autocomplete="off">
                            <label class="btn btn-outline-secondary" for="fm_pix">
                                <i class="fas fa-qrcode me-1"></i> PIX
                            </label>

                            <input type="radio" class="btn-check" name="forma_pagamento"
                                id="fm_debito" value="CARTAO_DEBITO" autocomplete="off">
                            <label class="btn btn-outline-secondary" for="fm_debito">
                                <i class="fas fa-credit-card me-1"></i> Débito
                            </label>

                            <input type="radio" class="btn-check" name="forma_pagamento"
                                id="fm_credito" value="CARTAO_CREDITO" autocomplete="off">
                            <label class="btn btn-outline-secondary" for="fm_credito">
                                <i class="fas fa-credit-card me-1"></i> Crédito
                            </label>
                        </div>
                        <div id="fp_modal_erro" class="text-danger small mt-2" style="display:none;">
                            Selecione uma forma de pagamento.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Voltar</button>
                        <button type="submit" class="btn btn-purple" onclick="return validarFechamento()">
                            <i class="fas fa-lock me-1"></i> Confirmar Fechamento
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalCancelar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h6 class="modal-title text-danger">
                        <i class="fas fa-exclamation-triangle me-1"></i> Cancelar Mesa {{ $mesa->numero }}?
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body pt-0">
                    <p class="text-muted small">
                        Todos os itens serão removidos e o estoque será restaurado.
                        Esta ação não pode ser desfeita.
                    </p>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Não</button>
                    <form action="{{ route('mesas.cancelar', $mesa) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm">
                            Sim, cancelar mesa
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        const CSRF_TOKEN  = '{{ csrf_token() }}';
        const produtoData = @json($produtoData);
        const ROUTE_ADD   = '{{ route('mesas.item.add', $mesa) }}';
        const ROUTE_DEL   = (id) => `{{ url('mesas/' . $mesa->id . '/item') }}/${id}`;

        const toastrOpts  = { timeOut: 3500, closeButton: true, progressBar: true };

        let comanda = @json($itensData);

        const brl = (v) => Number(v).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });

        function renderComanda(data) {
            comanda = data;
            const tbody = document.getElementById('tabela_itens');
            tbody.innerHTML = '';

            if (!data.items || data.items.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">
                            <i class="fas fa-clipboard me-2"></i>Nenhum item adicionado ainda.
                        </td>
                    </tr>`;
            } else {
                data.items.forEach(item => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td>${item.name}</td>
                        <td>${brl(item.valor_unitario)}</td>
                        <td>${item.quantidade}</td>
                        <td>${brl(item.valor_total)}</td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-outline-danger" title="Remover"
                                onclick="removerItem(${item.id})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>`;
                    tbody.appendChild(tr);
                });
            }

            const total = data.total_geral || 0;
            document.getElementById('res_total').textContent    = brl(total);
            document.getElementById('modal_total').textContent  = brl(total);
        }

        document.getElementById('sel_produto').addEventListener('change', function () {
            const id      = parseInt(this.value);
            const msgEl   = document.getElementById('msg_estoque');

            if (!id || !produtoData[id]) {
                document.getElementById('inp_valor').value = '';
                document.getElementById('inp_total').value = '';
                document.getElementById('inp_quantidade').value = '1';
                msgEl.style.display = 'none';
                return;
            }

            const { sale_price: preco, available_quantity: estoque } = produtoData[id];
            document.getElementById('inp_valor').value    = brl(preco);
            document.getElementById('inp_quantidade').value = '1';
            document.getElementById('inp_total').value    = brl(preco);
            document.getElementById('inp_quantidade').max = estoque;
            msgEl.textContent    = `Estoque disponível: ${estoque}`;
            msgEl.style.display  = 'block';
        });

        document.getElementById('inp_quantidade').addEventListener('input', function () {
            const id  = parseInt(document.getElementById('sel_produto').value);
            if (!id || !produtoData[id]) return;
            const qtd = parseFloat(this.value) || 0;
            document.getElementById('inp_total').value = brl(produtoData[id].sale_price * qtd);
        });

        function adicionarItem() {
            const id  = parseInt(document.getElementById('sel_produto').value);
            const qtd = parseFloat(document.getElementById('inp_quantidade').value) || 0;

            if (!id) {
                toastr.warning('Selecione um produto.', 'Atenção', toastrOpts);
                return;
            }
            if (qtd <= 0) {
                toastr.warning('Informe uma quantidade válida.', 'Atenção', toastrOpts);
                return;
            }

            const btn = document.getElementById('btn_adicionar');
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Adicionando…';

            fetch(ROUTE_ADD, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ produto_id: id, quantidade: qtd }),
            })
            .then(async res => {
                const data = await res.json();
                if (!res.ok) throw new Error(data.error || 'Erro ao adicionar item.');
                return data;
            })
            .then(data => {
                renderComanda(data);

                produtoData[id].available_quantity -= qtd;
                if (produtoData[id].available_quantity < 0) produtoData[id].available_quantity = 0;

                document.getElementById('sel_produto').value    = '';
                document.getElementById('inp_valor').value      = '';
                document.getElementById('inp_quantidade').value = '1';
                document.getElementById('inp_total').value      = '';
                document.getElementById('msg_estoque').style.display = 'none';

                const nome = document.getElementById('sel_produto').options[0]?.text ?? 'Produto';
                toastr.success('Item adicionado à comanda!', 'Sucesso', { ...toastrOpts, timeOut: 2000 });
            })
            .catch(err => toastr.error(err.message, 'Erro', toastrOpts))
            .finally(() => {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-plus me-1"></i> Adicionar à Comanda';
            });
        }

        function removerItem(itemId) {
            fetch(ROUTE_DEL(itemId), {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'Accept': 'application/json',
                },
            })
            .then(async res => {
                const data = await res.json();
                if (!res.ok) throw new Error(data.error || 'Erro ao remover item.');
                return data;
            })
            .then(data => {
                renderComanda(data);
                toastr.info('Item removido da comanda.', '', { ...toastrOpts, timeOut: 2000 });
            })
            .catch(err => toastr.error(err.message, 'Erro', toastrOpts));
        }

        function abrirFecharMesa() {
            if (!comanda.items || comanda.items.length === 0) {
                toastr.warning('Adicione ao menos um produto antes de fechar a mesa.', 'Atenção', toastrOpts);
                return;
            }
            document.getElementById('fp_modal_erro').style.display = 'none';
            document.querySelectorAll('input[name="forma_pagamento"]').forEach(r => r.checked = false);
            new bootstrap.Modal(document.getElementById('modalFecharMesa')).show();
        }

        function validarFechamento() {
            const fp = document.querySelector('input[name="forma_pagamento"]:checked');
            if (!fp) {
                document.getElementById('fp_modal_erro').style.display = 'block';
                return false;
            }
            return true;
        }

        function confirmarCancelar() {
            new bootstrap.Modal(document.getElementById('modalCancelar')).show();
        }

        renderComanda(comanda);
    </script>
@endsection
