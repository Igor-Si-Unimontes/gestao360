@extends('components.layouts.dashboard-layout')

@section('title', 'Nova Sangria')

@section('content')
    <x-layouts.breadcrumb title="Nova Sangria" :breadcrumbs="[
        ['name' => 'Caixa',    'route' => 'caixas.index'],
        ['name' => 'Sangrias', 'route' => 'sangrias.index'],
        ['name' => 'Nova Sangria'],
    ]" />

    <div class="container bg-white rounded p-4 shadow-sm">

        <div class="alert d-flex align-items-center gap-3 mb-4"
             style="background:#f0fdf4; border:1.5px solid #86efac; border-radius:12px; padding:14px 18px;">
            <div style="width:40px; height:40px; background:#22c55e; border-radius:50%;
                        display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <i class="fas fa-coins text-white"></i>
            </div>
            <div>
                <div class="fw-semibold" style="color:#166534;">Espécie disponível no caixa</div>
                <div class="small text-muted">O valor da sangria não pode exceder esse montante.</div>
            </div>
            <div class="ms-auto fw-bold fs-5" style="color:#16a34a;">
                R$ {{ number_format($disponivelEspecie, 2, ',', '.') }}
            </div>
        </div>

        <form action="{{ route('sangrias.store') }}" method="POST">
            @csrf

            <div class="row">
                <div class="col-md-4">
                    <label for="data_retirada" class="form-label text-gray-label mt-4">
                        Data de Retirada <span class="text-danger">*</span>
                    </label>
                    <input type="date" name="data_retirada" id="data_retirada"
                           value="{{ old('data_retirada', date('Y-m-d')) }}"
                           required
                           class="form-control p-3 @error('data_retirada') is-invalid @enderror">
                    @error('data_retirada')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="categoria" class="form-label text-gray-label mt-4">
                        Categoria <span class="text-danger">*</span>
                    </label>
                    <select name="categoria" id="categoria" required
                            class="form-select p-3 @error('categoria') is-invalid @enderror">
                        <option value="">Selecione...</option>
                        @foreach (\App\Models\Sangria::$categorias as $key => $label)
                            <option value="{{ $key }}" {{ old('categoria') === $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('categoria')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="valor" class="form-label text-gray-label mt-4">
                        Valor (R$) <span class="text-danger">*</span>
                    </label>
                    <input type="number" name="valor" id="valor"
                           value="{{ old('valor') }}"
                           min="0.01" step="0.01"
                           required
                           class="form-control p-3 @error('valor') is-invalid @enderror"
                           placeholder="0,00">
                    @error('valor')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label for="observacao" class="form-label text-gray-label mt-4">
                        Observação <span class="text-muted fw-normal">(opcional)</span>
                    </label>
                    <input type="text" name="observacao" id="observacao"
                           value="{{ old('observacao') }}"
                           class="form-control p-3 @error('observacao') is-invalid @enderror"
                           placeholder="Ex: pagamento de fornecedor, recolhimento para cofre...">
                    @error('observacao')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mt-5">
                <div class="col-3">
                    <a href="{{ route('sangrias.index') }}" class="btn btn-cancelar w-100"
                       style="font-size: 18px; font-weight: 500;">Cancelar</a>
                </div>
                <div class="col-3">
                    <button type="submit" class="btn btn-purple w-100"
                            style="font-size: 18px; font-weight: 400;">Registrar</button>
                </div>
            </div>
        </form>
    </div>
@endsection
