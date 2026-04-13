@extends('components.layouts.dashboard-layout')

@section('title', 'Novo Pedido - Balcão')

@section('content')
    <x-layouts.breadcrumb title="Novo Pedido - Balcão" :breadcrumbs="[['name' => 'Vendas', 'route' => 'balcao'], ['name' => 'Novo Pedido - Balcão']]" />


    <div class="container bg-white rounded mb-5" style="padding: 30px;">

        <h5 class="mb-4">Detalhes do Pedido</h5>

        <div class="row mb-3 g-3">
            <div class="col-md-6">
                <label class="form-label">Forma de Entrega</label>
                <select id="forma_entrega" class="form-select" name="forma_entrega" onchange="alternarEntrega(this.value)">
                    <option value="balcao" selected>Balcão</option>
                    <option value="entrega">Entrega (Delivery)</option>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">
                    Forma de Pagamento <span class="text-danger">*</span>
                </label>
                <div class="d-flex flex-wrap gap-2 mt-1" id="forma_pagamento_group">
                    <input type="radio" class="btn-check" name="forma_pagamento_ui" id="fp_dinheiro" value="DINHEIRO"
                        autocomplete="off">
                    <label class="btn btn-outline-secondary btn-sm" for="fp_dinheiro">
                        <i class="fas fa-money-bill-wave me-1"></i> Dinheiro
                    </label>

                    <input type="radio" class="btn-check" name="forma_pagamento_ui" id="fp_pix" value="PIX"
                        autocomplete="off">
                    <label class="btn btn-outline-secondary btn-sm" for="fp_pix">
                        <i class="fas fa-qrcode me-1"></i> PIX
                    </label>

                    <input type="radio" class="btn-check" name="forma_pagamento_ui" id="fp_debito" value="CARTAO_DEBITO"
                        autocomplete="off">
                    <label class="btn btn-outline-secondary btn-sm" for="fp_debito">
                        <i class="fas fa-credit-card me-1"></i> Débito
                    </label>

                    <input type="radio" class="btn-check" name="forma_pagamento_ui" id="fp_credito" value="CARTAO_CREDITO"
                        autocomplete="off">
                    <label class="btn btn-outline-secondary btn-sm" for="fp_credito">
                        <i class="fas fa-credit-card me-1"></i> Crédito
                    </label>
                </div>
                <div id="fp_erro" class="text-danger small mt-1" style="display:none;">
                    Selecione uma forma de pagamento.
                </div>
            </div>
        </div>

        <div id="bloco_delivery" style="display:none;">
            <hr class="my-3">
            <h6 class="mb-3 text-muted">
                <i class="fas fa-motorcycle me-1"></i> Dados da Entrega
            </h6>
            <div class="row g-3 mb-2">
                <div class="col-md-7">
                    <label class="form-label">Endereço completo <span class="text-danger">*</span></label>
                    <input type="text" id="inp_endereco" class="form-control"
                        placeholder="Ex: Rua das Flores, 123, Apto 4">
                </div>
                <div class="col-md-5">
                    <label class="form-label">Bairro <span class="text-danger">*</span></label>
                    <select id="sel_bairro" class="form-select" onchange="selecionarBairro(this)">
                        <option value="">Selecione o bairro</option>
                        @foreach ($bairros as $bairro)
                            <option value="{{ $bairro->id }}" data-taxa="{{ $bairro->taxa }}">
                                {{ $bairro->nome }}
                                — R$ {{ number_format($bairro->taxa, 2, ',', '.') }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <hr class="my-4">

        <h5 class="mb-3">Adicionar Produto</h5>

        <div class="row align-items-end g-2">
            <div class="col-md-4">
                <label class="form-label">Produto</label>
                <select id="sel_produto" class="form-select">
                    <option value="">Selecione um produto</option>
                    @foreach ($produtos as $produto)
                        <option value="{{ $produto->id }}">{{ $produto->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <label class="form-label">Valor Unit.</label>
                <input type="text" id="inp_valor" class="form-control" readonly placeholder="R$ 0,00">
            </div>

            <div class="col-md-2">
                <label class="form-label">Quantidade</label>
                <input type="number" id="inp_quantidade" class="form-control" min="0.001" step="0.001" value="1"
                    placeholder="1">
            </div>

            <div class="col-md-2">
                <label class="form-label">Total</label>
                <input type="text" id="inp_total" class="form-control" readonly placeholder="R$ 0,00">
            </div>

            <div class="col-md-2">
                <button type="button" class="btn btn-purple w-100" onclick="adicionarProduto()">
                    <i class="fas fa-plus me-1"></i> Adicionar
                </button>
            </div>
        </div>

        <p id="msg_estoque" class="text-muted small mt-1" style="display:none;"></p>

        <hr class="my-4">

        <h5 class="mb-3">Itens do Pedido</h5>

        <div class="table-responsive rounded-3 overflow-hidden border">
            <table class="table table-bordered table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Produto</th>
                        <th style="width:140px">Valor Unit.</th>
                        <th style="width:140px">Quantidade</th>
                        <th style="width:140px">Valor Total</th>
                        <th class="text-center" style="width:100px">Opções</th>
                    </tr>
                </thead>
                <tbody id="tabela_itens">
                    <tr id="linha_vazia">
                        <td colspan="5" class="text-center text-muted py-4">
                            <i class="fas fa-shopping-cart me-2"></i>Nenhum produto adicionado ainda.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="row mt-4 mb-2">
            <h5 class="mb-3">Resumo do Pedido</h5>

            <div class="col-md-3">
                <div class="card border-0 bg-light p-3">
                    <span class="text-muted small">Produtos</span>
                    <span id="res_produtos" class="fw-bold fs-5">R$ 0,00</span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 bg-light p-3">
                    <span class="text-muted small">Entrega</span>
                    <span id="res_entrega" class="fw-bold fs-5">R$ 0,00</span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 bg-light p-3">
                    <span class="text-muted small">Desconto</span>
                    <span id="res_desconto" class="fw-bold fs-5">R$ 0,00</span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 p-3" style="background:#f3e9fd;">
                    <span class="text-muted small">Total Geral</span>
                    <span id="res_total" class="fw-bold fs-5" style="color:#7212E7;">R$ 0,00</span>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-3">
                <a href="{{ route('balcao') }}" class="btn btn-cancelar w-100">
                    Cancelar
                </a>
            </div>
            <div class="col-md-3">
                <button type="button" class="btn btn-purple w-100" onclick="finalizarPedido()">
                    <i class="fas fa-check me-1"></i> Finalizar Pedido
                </button>
            </div>
        </div>

        <form id="form_finalizar" action="{{ route('vendas.store') }}" method="POST" style="display:none;">
            @csrf
            <input type="hidden" name="produtos" id="hidden_produtos">
            <input type="hidden" name="forma_pagamento" id="hidden_forma_pagamento">
            <input type="hidden" name="forma_entrega" id="hidden_forma_entrega" value="balcao">
            <input type="hidden" name="endereco" id="hidden_endereco">
            <input type="hidden" name="bairro_id" id="hidden_bairro_id">
        </form>

    </div>

    <div class="modal fade" id="modalEditarQtd" tabindex="-1" aria-labelledby="modalEditarQtdLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="modalEditarQtdLabel">Editar Quantidade</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label class="form-label">Produto: <strong id="modal_nome_produto"></strong></label>
                    <input type="number" id="modal_quantidade" class="form-control" min="0.001" step="0.001">
                    <p class="text-muted small mt-1">Disponível em estoque: <span id="modal_estoque_disponivel"></span>
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm"
                        data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-purple btn-sm" onclick="confirmarEdicao()">Salvar</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const produtoData = @json($produtoData);

        let itens = [];
        let editandoIndex = null;
        let taxaEntregaAtual = 0;
        let isDelivery = false;

        function formatBRL(valor) {
            return valor.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
        }

        const toastrOpts = { timeOut: 4000, closeButton: true, progressBar: true };

        function alternarEntrega(valor) {
            isDelivery = (valor === 'entrega');
            document.getElementById('bloco_delivery').style.display = isDelivery ? 'block' : 'none';
            document.getElementById('hidden_forma_entrega').value = valor;

            if (!isDelivery) {
                taxaEntregaAtual = 0;
                document.getElementById('inp_endereco').value     = '';
                document.getElementById('sel_bairro').value       = '';
                document.getElementById('hidden_endereco').value  = '';
                document.getElementById('hidden_bairro_id').value = '';
            }

            atualizarResumo();
        }

        function selecionarBairro(sel) {
            const opt = sel.options[sel.selectedIndex];
            taxaEntregaAtual = sel.value ? parseFloat(opt.dataset.taxa) || 0 : 0;
            document.getElementById('hidden_bairro_id').value = sel.value;
            atualizarResumo();
        }

        document.getElementById('sel_produto').addEventListener('change', function() {
            const id = parseInt(this.value);
            const inpValor = document.getElementById('inp_valor');
            const inpQtd = document.getElementById('inp_quantidade');
            const inpTotal = document.getElementById('inp_total');
            const msgEstoque = document.getElementById('msg_estoque');

            if (!id || !produtoData[id]) {
                inpValor.value = '';
                inpTotal.value = '';
                inpQtd.value = '1';
                msgEstoque.style.display = 'none';
                return;
            }

            const preco = produtoData[id].sale_price;
            const estoque = produtoData[id].available_quantity;

            inpValor.value = formatBRL(preco);
            inpQtd.value = '1';
            inpTotal.value = formatBRL(preco * 1);
            inpQtd.max = estoque;

            msgEstoque.textContent = `Estoque disponível: ${estoque}`;
            msgEstoque.style.display = 'block';
        });

        document.getElementById('inp_quantidade').addEventListener('input', function() {
            const id = parseInt(document.getElementById('sel_produto').value);
            if (!id || !produtoData[id]) return;

            const qtd = parseFloat(this.value) || 0;
            const preco = produtoData[id].sale_price;
            document.getElementById('inp_total').value = formatBRL(preco * qtd);
        });

        function adicionarProduto() {
            const sel = document.getElementById('sel_produto');
            const id = parseInt(sel.value);
            const nome = sel.options[sel.selectedIndex]?.text;
            const qtd = parseFloat(document.getElementById('inp_quantidade').value) || 0;

            if (!id) {
                toastr.warning('Selecione um produto antes de adicionar.', 'Atenção', toastrOpts);
                return;
            }
            if (qtd <= 0) {
                toastr.warning('Informe uma quantidade válida.', 'Atenção', toastrOpts);
                return;
            }

            const {
                sale_price: preco,
                available_quantity: estoque
            } = produtoData[id];

            const existente = itens.find(i => i.id === id);
            const qtdJaAdicionada = existente ? existente.quantidade : 0;

            if (qtdJaAdicionada + qtd > estoque) {
                toastr.error(`Quantidade total excede o estoque disponível (${estoque}).`, 'Estoque insuficiente',
                    toastrOpts);
                return;
            }

            if (existente) {
                existente.quantidade += qtd;
                existente.valor_total = existente.valor_unitario * existente.quantidade;
            } else {
                itens.push({
                    id,
                    name: nome,
                    valor_unitario: preco,
                    quantidade: qtd,
                    valor_total: preco * qtd,
                });
            }

            toastr.success(`"${nome}" adicionado ao pedido.`, 'Produto adicionado', {
                ...toastrOpts,
                timeOut: 2500
            });

            sel.value = '';
            document.getElementById('inp_valor').value = '';
            document.getElementById('inp_quantidade').value = '1';
            document.getElementById('inp_total').value = '';
            document.getElementById('msg_estoque').style.display = 'none';

            renderTabela();
            atualizarResumo();
        }

        function renderTabela() {
            const tbody = document.getElementById('tabela_itens');
            tbody.innerHTML = '';

            if (itens.length === 0) {
                tbody.innerHTML = `
                    <tr id="linha_vazia">
                        <td colspan="5" class="text-center text-muted py-4">
                            <i class="fas fa-shopping-cart me-2"></i>Nenhum produto adicionado ainda.
                        </td>
                    </tr>`;
                return;
            }

            itens.forEach((item, idx) => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${item.name}</td>
                    <td>${formatBRL(item.valor_unitario)}</td>
                    <td>${item.quantidade}</td>
                    <td>${formatBRL(item.valor_total)}</td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-outline-secondary me-1" title="Editar quantidade"
                            onclick="abrirEdicao(${idx})">
                            <i class="fas fa-pencil-alt"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger" title="Remover produto"
                            onclick="removerItem(${idx})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>`;
                tbody.appendChild(tr);
            });
        }

        function removerItem(idx) {
            itens.splice(idx, 1);
            renderTabela();
            atualizarResumo();
        }

        function abrirEdicao(idx) {
            editandoIndex = idx;
            const item = itens[idx];
            document.getElementById('modal_nome_produto').textContent = item.name;
            document.getElementById('modal_quantidade').value = item.quantidade;
            document.getElementById('modal_quantidade').max = produtoData[item.id].available_quantity;
            document.getElementById('modal_estoque_disponivel').textContent = produtoData[item.id].available_quantity;

            const modal = new bootstrap.Modal(document.getElementById('modalEditarQtd'));
            modal.show();
        }

        function confirmarEdicao() {
            const novaQtd = parseFloat(document.getElementById('modal_quantidade').value) || 0;

            if (novaQtd <= 0) {
                toastr.warning('Informe uma quantidade válida.', 'Atenção', toastrOpts);
                return;
            }

            const item = itens[editandoIndex];
            const estoque = produtoData[item.id].available_quantity;

            if (novaQtd > estoque) {
                toastr.error(`Quantidade excede o estoque disponível (${estoque}).`, 'Estoque insuficiente', toastrOpts);
                return;
            }

            item.quantidade = novaQtd;
            item.valor_total = item.valor_unitario * novaQtd;

            bootstrap.Modal.getInstance(document.getElementById('modalEditarQtd')).hide();
            renderTabela();
            atualizarResumo();
        }

        function atualizarResumo() {
            const totalProdutos = itens.reduce((acc, i) => acc + i.valor_total, 0);
            const entrega  = taxaEntregaAtual;
            const desconto = 0;
            const total    = totalProdutos + entrega - desconto;

            document.getElementById('res_produtos').textContent = formatBRL(totalProdutos);
            document.getElementById('res_entrega').textContent  = formatBRL(entrega);
            document.getElementById('res_desconto').textContent = formatBRL(desconto);
            document.getElementById('res_total').textContent    = formatBRL(total);
        }

        function finalizarPedido() {
            if (itens.length === 0) {
                toastr.warning('Adicione ao menos um produto ao pedido.', 'Pedido vazio', toastrOpts);
                return;
            }

            const fpSelecionado = document.querySelector('input[name="forma_pagamento_ui"]:checked');
            if (!fpSelecionado) {
                document.getElementById('fp_erro').style.display = 'block';
                toastr.warning('Selecione a forma de pagamento.', 'Atenção', toastrOpts);
                document.getElementById('forma_pagamento_group').scrollIntoView({ behavior: 'smooth', block: 'center' });
                return;
            }
            document.getElementById('fp_erro').style.display = 'none';

            if (isDelivery) {
                const endereco = document.getElementById('inp_endereco').value.trim();
                const bairroId = document.getElementById('sel_bairro').value;

                if (!endereco) {
                    toastr.warning('Informe o endereço de entrega.', 'Atenção', toastrOpts);
                    document.getElementById('inp_endereco').focus();
                    return;
                }
                if (!bairroId) {
                    toastr.warning('Selecione o bairro de entrega.', 'Atenção', toastrOpts);
                    document.getElementById('sel_bairro').focus();
                    return;
                }

                document.getElementById('hidden_endereco').value = endereco;
            }

            const payload = itens.map(i => ({ id: i.id, quantidade: i.quantidade }));

            document.getElementById('hidden_produtos').value        = JSON.stringify(payload);
            document.getElementById('hidden_forma_pagamento').value = fpSelecionado.value;
            document.getElementById('form_finalizar').submit();
        }

        document.querySelectorAll('input[name="forma_pagamento_ui"]').forEach(function(radio) {
            radio.addEventListener('change', function() {
                document.getElementById('fp_erro').style.display = 'none';
            });
        });
    </script>
@endsection
