@extends('components.layouts.dashboard-layout')

@section('title', 'Categorias')

@section('content')
    <x-layouts.breadcrumb title="Categorias" :breadcrumbs="[['name' => 'Categorias', 'route' => 'categories.index']]">
        <a href="{{ route('categories.create') }}" class="btn btn-purple-main text-white">Nova Categoria</a>
    </x-layouts.breadcrumb>

    <div class="container bg-white rounded" style="padding: 30px;">
        <table id="categoriesTable" class="table table-hover">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Nome</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($categories as $category)
                    <tr>
                        <td>#{{ $category->id }}</td>
                        <td>{{ $category->name }}</td>
                        <td class="text-center">
                            <label class="custom-switch">
                              <input type="checkbox" name="status" data-id="{{ $category->id }}" class="status-switch"
                                     {{ $category->status == 1 ? 'checked' : '' }}>
                              <span class="slider">
                                <span class="text"></span>
                              </span>
                            </label>
                        </td>                                                                       
                        <td class="text-center">
                            <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-sm" style="color: #7212E7;" title="Editar">
                                <svg xmlns="http://www.w3.org/2000/svg" class="lucide" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#7212E7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 20h9"></path>
                                    <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z"></path>
                                </svg>
                            </a>
                            <a href="#" class="btn btn-sm" data-bs-toggle="modal" data-bs-target="#deleteCategoryModal-{{ $category->id }}" style="color: #7212E7;" title="Visualizar">
                                <svg xmlns="http://www.w3.org/2000/svg" class="lucide" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#7212E7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="3 6 5 6 21 6"></polyline>
                                    <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"></path>
                                    <path d="M10 11v6"></path>
                                    <path d="M14 11v6"></path>
                                    <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"></path>
                                </svg>
                            </a>
                        </td>                                                                                           
                    </tr>
                    <div class="modal fade" id="deleteCategoryModal-{{ $category->id }}" tabindex="-1" aria-labelledby="deleteCategoryModalLabel-{{ $category->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteCategoryModalLabel-{{ $category->id }}">Confirmar Exclusão</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                                </div>
                                <div class="modal-body">
                                    Tem certeza que deseja excluir a categoria <strong>{{ $category->name }}</strong>? Esta ação não poderá ser desfeita.
                                </div>
                                <div class="modal-footer">
                                    <form action="{{ route('categories.destroy', $category->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                        <button type="submit" class="btn btn-cancelar-vermelho">Sim, excluir</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
@section('styles')
    <style>
        #categoriesTable th,
        #categoriesTable td {
            padding: 20px 20px;
        }
    </style>
   <style>
        .custom-switch {
        position: relative;
        display: inline-block;
        width: 80px;
        height: 34px;
        }
        .custom-switch input {
        opacity: 0;
        width: 0;
        height: 0;
        }
        .slider {
        position: absolute;
        cursor: pointer;
        background-color: #ccc;
        border-radius: 34px;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        transition: background-color 0.4s;
        }
        .slider::before {
        position: absolute;
        content: "";
        height: 0px;
        width: 0px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        border-radius: 50%;
        transition: transform 0.4s;
        }
        .slider .text {
        position: absolute;
        width: 100%;
        height: 100%;
        color: black;
        font-size: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        user-select: none;
        }
        .custom-switch input:checked + .slider {
        background-color: #28a745;
        }
        .custom-switch input:checked + .slider::before {
        transform: translateX(46px);
        }
        .custom-switch input:checked + .slider .text {
        content: "Ativo";
        }
        .custom-switch input:not(:checked) + .slider .text::before {
        content: "Inativo";
        }
        .custom-switch input:checked + .slider .text::before {
        content: "Ativo";
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
            $('#categoriesTable').DataTable({
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
    <script>
        $(document).ready(function() {
            $('.status-switch').on('change', function() {
                var categoryId = $(this).data('id');
                var status = $(this).is(':checked') ? 1 : 0;
    
                $.ajax({
                    url: '/categories/' + categoryId + '/status',
                    type: 'PATCH',
                    data: {
                        status: status,
                        _token: '{{ csrf_token() }}'
                    },
                });
            });
        });
    </script>
@endsection