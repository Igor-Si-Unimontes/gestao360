@extends('components.layouts.dashboard-layout')

@section('title', 'Registros de Ponto')

@section('content')
    <x-layouts.breadcrumb
        title="Registros de Ponto"
        :breadcrumbs="[
            ['name' => 'Ponto', 'route' => 'pontos.index'],
            ['name' => 'Registros'],
        ]"
    >
    </x-layouts.breadcrumb>

    <div class="container pb-5">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="mb-0 fw-semibold">
                    <i class="fas fa-history me-1" style="color:#7212E7;"></i>
                    Registros de Ponto
                </h6>
            </div>
            <div class="card-body p-4">
                @if ($pontos->isEmpty())
                    <div class="text-center text-muted py-5">
                        <i class="fas fa-clock" style="font-size:2rem; opacity:.3;"></i>
                        <p class="mt-2 small">Nenhum ponto registrado ainda.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table id="pontosTable" class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Funcionário</th>
                                    <th>Entrada</th>
                                    <th>Saída</th>
                                    <th>Horas Trabalhadas</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pontos as $ponto)
                                    <tr>
                                        <td class="text-muted fw-semibold">#{{ $ponto->id }}</td>
                                        <td>{{ $ponto->usuario->name ?? '—' }}</td>
                                        <td>
                                            <div>{{ $ponto->entrada_em->format('d/m/Y') }}</div>
                                            <div class="text-muted small">{{ $ponto->entrada_em->format('H:i') }}</div>
                                        </td>
                                        <td>
                                            @if ($ponto->saida_em)
                                                <div>{{ $ponto->saida_em->format('d/m/Y') }}</div>
                                                <div class="text-muted small">{{ $ponto->saida_em->format('H:i') }}</div>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td class="fw-semibold">{{ $ponto->horasTrabalhadas() }}</td>
                                        <td>
                                            @if ($ponto->emAberto())
                                                <span class="badge bg-success">Aberto</span>
                                            @else
                                                <span class="badge bg-secondary">Fechado</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        #pontosTable th,
        #pontosTable td {
            padding: 16px 20px;
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
        $(document).ready(function () {
            $('#pontosTable').DataTable({
                language: {
                    emptyTable: "Nenhum ponto registrado ainda.",
                    url: 'https://cdn.datatables.net/plug-ins/2.0.0/i18n/pt-BR.json',
                    search: "",
                    searchPlaceholder: "Busque aqui...",
                    lengthMenu: "Linhas _MENU_"
                },
                order: [[0, 'desc']],
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
