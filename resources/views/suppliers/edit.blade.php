@extends('components.layouts.dashboard-layout')

@section('title', 'Fornecedores')

@section('content')
    <x-layouts.breadcrumb title="Editar Dados Do Fornecedor" :breadcrumbs="[
        ['name' => 'Fornecedores', 'route' => 'suppliers.index'],
        ['name' => 'Editar Dados Do Fornecedor'],
    ]">
    </x-layouts.breadcrumb>

    <div class="container bg-white rounded" style="padding: 30px;">
        <form action="{{ route('suppliers.update', $supplier->id) }}" method="POST">
            @method('PATCH')
            @csrf
            <div class="row">
                <div class="col-4">
                    <label for="name" class="form-label text-gray-label mt-4">Nome*</label>
                    <input type="text" name="name" id="name" required
                        class="form-control p-3 @error('name') is-invalid @enderror"
                        placeholder="Digite o nome do fornecedor..." value="{{ old('name', $supplier->name) }}">
                    @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col-4">
                    <label for="phone" class="form-label text-gray-label mt-4">Telefone*</label>
                    <input type="phone" name="phone" id="phone" required class="form-control p-3 @error('phone') is-invalid @enderror"
                        placeholder="Digite o telefone do fornecedor..." value="{{ old('phone', $supplier->phone) }}">
                    @error('phone')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col-4">
                    <label for="cnpj" class="form-label text-gray-label mt-4">CNPJ*</label>
                    <input type="cnpj" name="cnpj" id="cnpj" required class="form-control p-3 @error('cnpj') is-invalid @enderror"
                        placeholder="Digite o CNPJ do fornecedor..." value="{{ old('cnpj', $supplier->cnpj) }}">
                    @error('cnpj')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            <div class="row" style="margin-top: 80px;">
                <div class="col-3">
                    <a href="{{ route('suppliers.index') }}" class="btn btn-cancelar w-100"
                        style="font-size: 18px; font-weight: 500;">Cancelar</a>
                </div>
                <div class="col-3">
                    <button type="submit" class="btn btn-purple w-100"
                        style="font-size: 18px; font-weight: 400;">Atualizar</button>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const phoneInput = document.getElementById('phone');

            phoneInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');

                if (value.length > 11) value = value.slice(0, 11);

                if (value.length > 2 && value.length <= 6) {
                    value = `(${value.slice(0,2)}) ${value.slice(2)}`;
                } else if (value.length > 6) {
                    value = `(${value.slice(0,2)}) ${value.slice(2,7)}-${value.slice(7)}`;
                }
                e.target.value = value;
            });
            const cnpjInput = document.getElementById('cnpj');

            cnpjInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');

                if (value.length > 14) value = value.slice(0, 14);

                if (value.length >= 3 && value.length <= 5) {
                    value = `${value.slice(0,2)}.${value.slice(2)}`;
                } else if (value.length > 5 && value.length <= 8) {
                    value = `${value.slice(0,2)}.${value.slice(2,5)}.${value.slice(5)}`;
                } else if (value.length > 8 && value.length <= 12) {
                    value = `${value.slice(0,2)}.${value.slice(2,5)}.${value.slice(5,8)}/${value.slice(8)}`;
                } else if (value.length > 12) {
                    value = `${value.slice(0,2)}.${value.slice(2,5)}.${value.slice(5,8)}/${value.slice(8,12)}-${value.slice(12)}`;
                }

                e.target.value = value;
            });
        });
    </script>
@endsection
