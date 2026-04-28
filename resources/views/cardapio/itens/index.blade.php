@extends('components.layouts.dashboard-layout')

@section('title', 'Itens do cardápio')

@section('content')
    <x-layouts.breadcrumb title="Itens do cardápio" :breadcrumbs="[['name' => 'Itens do cardápio']]">
        <div class="d-flex flex-wrap gap-2 justify-content-end">
            <a href="{{ route('cardapio.dados') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-heading me-1"></i> Título e descrição
            </a>
            <a href="{{ route('cardapio.menu') }}" class="btn btn-outline-primary btn-sm" target="_blank"
                rel="noopener noreferrer">
                <i class="fas fa-tv me-1"></i> Vitrine pública
            </a>
            <a href="{{ route('cardapio.itens.create') }}" class="btn btn-purple-main text-white btn-sm">
                <i class="fas fa-plus me-1"></i> Novo item
            </a>
        </div>
    </x-layouts.breadcrumb>

    <div class="container bg-white rounded p-4">
        @if ($itens->isEmpty())
            <p class="text-muted mb-0">Nenhum item. Clique em <strong>Novo item</strong>.</p>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width:88px;">Foto</th>
                            <th>Nome</th>
                            <th>Categoria</th>
                            <th class="d-none d-md-table-cell">Descrição</th>
                            <th class="text-center">Serve</th>
                            <th class="text-end">Valor</th>
                            <th class="text-center">Vitrine</th>
                            <th class="text-end">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($itens as $item)
                            <tr>
                                <td>
                                    <img src="{{ $item->urlImagemExibicao() }}" alt="" class="rounded"
                                        style="width:72px;height:54px;object-fit:cover;">
                                </td>
                                <td class="fw-semibold">{{ $item->nome }}</td>
                                <td><span class="badge bg-light text-dark border">{{ $item->labelCategoria() }}</span></td>
                                <td class="d-none d-md-table-cell text-muted small">
                                    {{ \Illuminate\Support\Str::limit($item->descricao ?? '—', 60) }}</td>
                                <td class="text-center">{{ $item->serve_pessoas }}</td>
                                <td class="text-end">R$ {{ number_format((float) $item->valor, 2, ',', '.') }}</td>
                                <td class="text-center">
                                    @if ($item->visivel)
                                        <span class="badge bg-success">Sim</span>
                                    @else
                                        <span class="badge bg-secondary">Não</span>
                                    @endif
                                </td>
                                <td class="text-end text-nowrap">
                                    <a href="{{ route('cardapio.itens.edit', $item) }}" class="btn btn-sm"
                                        style="color:#7212E7;" title="Editar">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="lucide" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="#7212E7" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M12 20h9"></path>
                                            <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z"></path>
                                        </svg>
                                    </a>
                                    <a href="#" class="btn btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#deleteItemModal-{{ $item->id }}" style="color: #7212E7;"
                                        title="Deletar">

                                        <svg xmlns="http://www.w3.org/2000/svg" class="lucide" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="#7212E7" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="3 6 5 6 21 6"></polyline>
                                            <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"></path>
                                            <path d="M10 11v6"></path>
                                            <path d="M14 11v6"></path>
                                            <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"></path>
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                            <div class="modal fade" id="deleteItemModal-{{ $item->id }}" tabindex="-1"
                                aria-labelledby="deleteItemModalLabel-{{ $item->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteItemModalLabel-{{ $item->id }}">
                                                Confirmar Exclusão</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Fechar"></button>
                                        </div>
                                        <div class="modal-body">
                                            Tem certeza que deseja excluir o item
                                            <strong>{{ $item->nome }}</strong>? Esta ação não poderá ser desfeita.
                                        </div>
                                        <div class="modal-footer">
                                            <form action="{{ route('cardapio.itens.destroy', $item->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Cancelar</button>
                                                <button type="submit" class="btn btn-cancelar-vermelho">Sim,
                                                    excluir</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <script>
        function copyCardapioMenuLink() {
            var el = document.getElementById('link-vitrine-cardapio');
            if (!el) return;
            navigator.clipboard.writeText(el.value).then(function() {
                if (typeof toastr !== 'undefined') {
                    toastr.success('Link copiado.');
                } else {
                    alert('Link copiado.');
                }
            }).catch(function() {
                el.select();
                document.execCommand('copy');
                if (typeof toastr !== 'undefined') {
                    toastr.success('Link copiado.');
                } else {
                    alert('Link copiado.');
                }
            });
        }
    </script>
@endsection
