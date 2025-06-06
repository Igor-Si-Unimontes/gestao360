@extends('components.layouts.dashboard-layout')

@section('title', 'Funcionários')

@section('content')
    <x-layouts.breadcrumb title="Funcionários" :breadcrumbs="[['name' => 'Funcionários', 'route' => 'employees.index']]">
        <a href="{{ route('employees.create') }}" class="btn btn-purple-main text-white">Novo Funcionário</a>
    </x-layouts.breadcrumb>

    <div class="container bg-white rounded" style="padding: 30px;">
        <table id="employeesTable" class="table table-hover">
            <thead>
                <tr>
                    <th>Funcionário</th>
                    <th>Permissão</th>
                    <th>Email</th>
                    <th>Telefone</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($employees as $employee)
                    @php
                        $roleName = $employee->user->role->name;
                        $roleCode = strtolower(
                            str_replace(
                                ['ç', 'ã', 'á', 'â', 'é', 'ê', 'í', 'ó', 'ô', 'ú', ' '],
                                ['c', 'a', 'a', 'a', 'e', 'e', 'i', 'o', 'o', 'u', '-'],
                                $roleName,
                            ),
                        );
                    @endphp
                    <tr>
                        <td>{{ $employee->name }}</td>
                        <td>
                            <span class="role-badge-text {{ 'role-' . $roleCode }}">
                                {{ $roleName }}
                            </span>
                        </td>
                        <td>{{ $employee->email }}</td>
                        <td>{{ $employee->phone }}</td>
                        <td>
                            <a href="#" class="btn btn-sm" data-bs-toggle="modal" data-bs-target="#showEmployeeModal-{{ $employee->id }}" style="color: #7212E7;" title="Visualizar">
                                <svg xmlns="http://www.w3.org/2000/svg" class="lucide" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#7212E7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="3"></circle>
                                    <path d="M2 12s4-8 10-8 10 8 10 8-4 8-10 8-10-8-10-8z"></path>
                                </svg>
                            </a>
                            <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-sm" style="color: #7212E7;" title="Editar">
                                <svg xmlns="http://www.w3.org/2000/svg" class="lucide" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#7212E7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 20h9"></path>
                                    <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z"></path>
                                </svg>
                            </a>
                            <a href="#" class="btn btn-sm" data-bs-toggle="modal" data-bs-target="#deleteEmployeeModal-{{ $employee->id }}" style="color: #7212E7;" title="Visualizar">
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
                    <div class="modal fade" id="showEmployeeModal-{{ $employee->id }}" tabindex="-1" aria-labelledby="showEmployeeModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title" id="showEmployeeModalLabel">Dados Do Funcionário:<b> {{ $employee->name }} </b> </h5>
                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                              <div>
                                <label for="name" class="form-label text-gray-label mt-4">Nome</label>
                                <input type="text" name="name" id="name" class="form-control p-3" value="{{ $employee->name }}" disabled>

                                <label for="name" class="form-label text-gray-label mt-4">Telefone</label>
                                <input type="text" name="name" id="name" class="form-control p-3" value="{{ $employee->phone }}" disabled>

                                <label for="name" class="form-label text-gray-label mt-4">E-mail</label>
                                <input type="text" name="name" id="name" class="form-control p-3" value="{{ $employee->email }}" disabled>
                              </div>
                            </div>
                            <div class="modal-footer">
                              <button type="button" data-bs-dismiss="modal" class="btn btn-cancelar-vermelho">Cancelar</button>
                            </div>
                          </div>
                        </div>
                    </div>
                    <div class="modal fade" id="deleteEmployeeModal-{{ $employee->id }}" tabindex="-1" aria-labelledby="deleteEmployeeModalLabel-{{ $employee->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteEmployeeModalLabel-{{ $employee->id }}">Confirmar Exclusão</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                                </div>
                                <div class="modal-body">
                                    Tem certeza que deseja excluir o funcionário <strong>{{ $employee->name }}</strong>? Esta ação não poderá ser desfeita.
                                </div>
                                <div class="modal-footer">
                                    <form action="{{ route('employees.destroy', $employee->id) }}" method="POST">
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
        #employeesTable th,
        #employeesTable td {
            padding: 20px 20px;
        }

        .role-badge-text {
            border-radius: 20px;
            padding: 4px 12px;
            font-size: 14px;
            font-weight: 400;
            white-space: nowrap;
            display: inline-block;
        }

        .role-administrador {
            background-color: #333333;
            color: #ffffff;
        }

        .role-garcom {
            background-color: #ECD686;
            color: #222222;
        }

        .role-caixa {
            background-color: #F8DCD3;
            color: #222222;
        }

        .role-atendente {
            background-color: #EABDBD;
            color: #222222;
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
            $('#employeesTable').DataTable({
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
