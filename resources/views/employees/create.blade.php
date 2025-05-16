@extends('components.layouts.dashboard-layout')

@section('title', 'Funcionários')

@section('content')
    <x-layouts.breadcrumb title="Novo Funcionário" :breadcrumbs="[
        ['name' => 'Funcionários', 'route' => 'employees.index'],
        ['name' => 'Novo Funcionário', 'route' => 'employees.create'],
    ]">
    </x-layouts.breadcrumb>

    <div class="container bg-white rounded" style="padding: 30px;">
        <form action="{{ route('employees.store') }}" method="POST">
            @csrf

            <div class="row">
                <div class="col-6">
                    <label for="name" class="form-label text-gray-label mt-4">Nome*</label>
                    <input type="text" name="name" id="name" required
                        class="form-control p-3 @error('name') is-invalid @enderror"
                        placeholder="Digite o nome do funcionário...">
                    @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col-6">
                    <label for="email" class="form-label text-gray-label mt-4">E-mail*</label>
                    <input type="email" name="email" id="email" required
                        class="form-control p-3 @error('email') is-invalid @enderror"
                        placeholder="Digite o email do funcionário...">
                    @error('email')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-6">
                    <label for="phone" class="form-label text-gray-label mt-4">Telefone*</label>
                    <input type="phone" name="phone" id="phone" required class="form-control p-3"
                        placeholder="Digite o phone do funcionário...">
                </div>
                <div class="col-6">
                    <label for="role_id" class="form-label text-gray-label mt-4">Permissão*</label>
                
                    <div class="position-relative" style="position: relative;">
                        <select name="role_id" id="role_id" class="form-control p-3"
                            style="opacity: 0; position: absolute; width: 100%; height: 100%; top: 0; left: 0; z-index: 2;">
                            <option value="">Selecione a permissão</option>
                            @foreach ($roles as $role)
                                @php
                                    $roleCode = strtolower(str_replace(
                                        ['ç', 'ã', 'á', 'â', 'é', 'ê', 'í', 'ó', 'ô', 'ú', ' '],
                                        ['c', 'a', 'a', 'a', 'e', 'e', 'i', 'o', 'o', 'u', '-'],
                                        $role->name
                                    ));
                                @endphp
                                <option value="{{ $role->id }}" data-role-code="{{ $roleCode }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    
                        <div id="visual-role"
                            class="form-control d-flex align-items-center justify-content-between pe-3"
                            style="pointer-events: none;">
                            <span id="badge-text" class="text-muted">Selecione a permissão</span>
                            <span>v</span>
                        </div>
                    </div>
                    
                </div>           
            </div>

            <div class="row">
                <div class="col-6">
                    <label for="password" class="form-label text-gray-label mt-4">Senha*</label>
                    <input type="password" name="password" id="password" required
                        class="form-control p-3 @error('password') is-invalid @enderror"
                        placeholder="Digite a senha do funcionário...">
                    @error('password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col-6">
                    <label for="password_confirmation" class="form-label text-gray-label mt-4">Confirmar Senha*</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                        class="form-control p-3 @error('password_confirmation') is-invalid @enderror"
                        placeholder="Confirme a senha do funcionário...">
                    @error('password_confirmation')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
            <div class="row" style="margin-top: 80px;">
                <div class="col-3">
                    <button class="btn btn-cancelar w-100" style="font-size: 18px; font-weight: 500;">Cancelar</button>
                </div>
                <div class="col-3">
                    <button type="submit" class="btn btn-purple w-100" style="font-size: 18px; font-weight: 400;">Adicionar</button>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('styles')
<style>
    #visual-role {
        min-height: 58px; 
        padding: 12px 16px;
        font-size: 14px;
        font-weight: 400;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        overflow: hidden;
        line-height: 1;
    }

    .role-badge-text {
        border-radius: 20px;
        padding: 4px 12px;
        font-size: 14px;
        font-weight: 400;
        white-space: nowrap;
    }

    .role-admin {
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
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const select = document.getElementById('role_id');
        const badgeText = document.getElementById('badge-text');

        const roleClasses = {
            'administrador': 'role-admin',
            'garcom': 'role-garcom',
            'caixa': 'role-caixa',
            'atendente': 'role-atendente',
        };

        select.addEventListener('change', function () {
            const selected = select.options[select.selectedIndex];
            const roleCode = selected.getAttribute('data-role-code');
            const roleName = selected.text.trim();

            badgeText.className = 'role-badge-text';

            if (roleCode && roleClasses[roleCode]) {
                badgeText.textContent = roleName;
                badgeText.classList.add(roleClasses[roleCode]);
            } else {
                badgeText.textContent = 'Selecione a permissão';
                badgeText.className = 'text-muted';
            }
        });
    });
</script>
@endsection
