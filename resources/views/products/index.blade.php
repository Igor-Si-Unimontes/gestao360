@extends('components.layouts.dashboard-layout')

@section('title', 'Produtos')

@section('content')
    <x-layouts.breadcrumb title="Produtos" :breadcrumbs="[['name' => 'Produtos', 'route' => 'produtos.index']]">
        <a href="{{ route('produtos.create') }}" class="btn btn-purple-main text-white">Novo Produto</a>
    </x-layouts.breadcrumb>

    <div class="container bg-white rounded" style="padding: 30px;">
        <table id="productsTable" class="table table-hover">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Produto</th>
                    <th>Valor</th>
                    <th>Em Estoque</th>
                    <th>Categoria</th>
                    <th>Situação</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                    <tr>
                        <td class="text-center">{{ $product->code }}</td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->last_sale_price ?? 'Adicione lotes' }}</td>
                        <td class="text-center">{{ $product->total_quantity }}</td>
                        <td>{{ $product->category->name }}</td>
                        <td>
                            @if ($product->total_quantity <= 0)
                                <span class="badge bg-danger text-white">Sem Estoque</span>
                            @elseif($product->total_quantity <= 20 && $product->total_quantity > 0)
                                <span class="badge bg-warning text-black">Acabando</span>
                            @else
                                <span class="badge bg-success text-white">Em Estoque</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('produtos.show', $product->id) }}" class="btn btn-sm" " style="color: #7212E7;"
                                title="Visualizar">
                                <svg xmlns="http://www.w3.org/2000/svg" class="lucide" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="#7212E7" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="3"></circle>
                                    <path d="M2 12s4-8 10-8 10 8 10 8-4 8-10 8-10-8-10-8z"></path>
                                </svg>
                            </a>
                            <a href="{{ route('produtos.edit', $product->id) }}" class="btn btn-sm" style="color: #7212E7;"
                                title="Editar">
                                <svg xmlns="http://www.w3.org/2000/svg" class="lucide" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="#7212E7" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 20h9"></path>
                                    <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z"></path>
                                </svg>
                            </a>
                            <a href="{{ route('lotes.create', $product->id) }}" class="btn btn-sm" style="color: #7212E7;"
                                title="Adicionar Lote">
                                <svg xmlns="http://www.w3.org/2000/svg" class="lucide" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="#7212E7" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 5v14"></path>
                                    <path d="M5 12h14"></path>
                                </svg>
                            </a>

                            <a href="#" class="btn btn-sm" data-bs-toggle="modal"
                                data-bs-target="#deleteProductModal-{{ $product->id }}" style="color: #7212E7;"
                                title="Visualizar">
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
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

@section('styles')
    <style>
        #productsTable th,
        #productsTable td {
            padding: 20px 20px;
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
            $('#productsTable').DataTable({
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/2.0.0/i18n/pt-BR.json',
                    search: "",
                    searchPlaceholder: "Busque aqui...",
                    lengthMenu: "Linhas _MENU_"
                },
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
