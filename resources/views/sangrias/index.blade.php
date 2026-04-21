@extends('components.layouts.dashboard-layout')

@section('title', 'Sangrias')

@section('content')
    <x-layouts.breadcrumb title="Sangrias" :breadcrumbs="[['name' => 'Caixa', 'route' => 'caixas.index'], ['name' => 'Sangrias', 'route' => 'sangrias.index']]">
        @if ($caixa)
            <a href="{{ route('sangrias.create') }}" class="btn btn-purple-main text-white">
                <i class="fas fa-plus me-1"></i> Nova Sangria
            </a>
        @endif
    </x-layouts.breadcrumb>

    @if (session('error'))
        <div class="container mb-3">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif

    @php $totalSangrias = $sangrias->sum('valor'); @endphp
    <div class="container bg-white rounded mb-4 p-4">
        <div class="card-body d-flex align-items-center justify-content-between">
            @if ($caixa)
                <div class="d-flex align-items-center gap-2">
                    <div class="text-muted small">
                        Espécie disponível (caixa #{{ $caixa->id }})
                    </div>

                    <div class="fw-bold fs-5" style="color:#7212E7;">
                        R$ {{ number_format($caixa->valorEsperadoFechamento(), 2, ',', '.') }}
                    </div>
                </div>
            @else
                <span class="badge bg-secondary">Nenhum caixa aberto</span>
            @endif
        </div>
    </div>

    <div class="container bg-white rounded p-4">
        <table id="sangriasTable" class="table table-hover">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Caixa</th>
                    <th>Data</th>
                    <th>Categoria</th>
                    <th>Valor</th>
                    <th>Observação</th>
                    <th>Registrado por</th>
                    <th class="text-center">Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($sangrias as $sangria)
                    <tr>
                        <td>#{{ $sangria->id }}</td>
                        <td>
                            <span class="badge bg-light text-dark border">#{{ $sangria->caixa_id }}</span>
                            @if ($sangria->caixa?->status === 'ABERTO')
                                <span class="badge bg-success ms-1" style="font-size:.65rem;">Aberto</span>
                            @else
                                <span class="badge bg-secondary ms-1" style="font-size:.65rem;">Fechado</span>
                            @endif
                        </td>
                        <td data-order="{{ $sangria->created_at->timestamp }}">
                            <div>{{ $sangria->created_at->format('d/m/Y') }}</div>
                            <div class="text-muted small">{{ $sangria->created_at->format('H:i') }}</div>
                        </td>
                        <td>{{ \App\Models\Sangria::$categorias[$sangria->categoria] ?? $sangria->categoria }}</td>
                        <td>R$ {{ number_format($sangria->valor, 2, ',', '.') }}</td>
                        <td>{{ $sangria->observacao ?? '—' }}</td>
                        <td>{{ $sangria->usuario->name ?? '—' }}</td>
                        <td class="text-center">
                            @if ($sangria->caixa?->status === 'ABERTO')
                                <a href="{{ route('sangrias.edit', $sangria->id) }}" class="btn btn-sm"
                                    style="color: #7212E7;" title="Editar">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="lucide" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="#7212E7" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M12 20h9"></path>
                                        <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z"></path>
                                    </svg>
                                </a>
                            @else
                                <button type="button" class="btn btn-sm text-muted" title="Caixa fechado" disabled>
                                    <i class="fas fa-lock"></i>
                                </button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

@endsection

@section('styles')
    <style>
        #sangriasTable th,
        #sangriasTable td {
            padding: 16px 20px;
        }

        #sangriasTable th:nth-child(5),
        #sangriasTable td:nth-child(5) {
            white-space: nowrap;
            width: 140px;
        }

        #sangriasTable th:nth-child(7),
        #sangriasTable td:nth-child(7) {
            width: 140px;
            max-width: 140px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.1/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.3.1/js/dataTables.bootstrap5.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.1/css/dataTables.bootstrap5.css">

    <script>
        $(document).ready(function() {
            $('#sangriasTable').DataTable({
                language: {
                    emptyTable: "Nenhuma sangria registrada neste caixa.",
                    url: 'https://cdn.datatables.net/plug-ins/2.0.0/i18n/pt-BR.json',
                    search: "",
                    searchPlaceholder: "Busque aqui...",
                    lengthMenu: "Linhas _MENU_"
                },
                order: [
                    [2, 'desc']
                ],
                paging: true,
                searching: true,
                lengthChange: true,
                pageLength: 10,
                pagingType: "simple_numbers",
                dom: "<'row'<'col-sm-12 d-flex mb-3'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row mt-5'<'col-sm-12 d-flex justify-content-end align-items-center'<'dt-length me-3'l><'dt-pagination'p>>>"
            });
        });
    </script>
@endsection
