@extends('components.layouts.dashboard-layout')

@section('title', 'Caixa')

@section('content')
<x-layouts.breadcrumb title="Caixa" :breadcrumbs="[['name' => 'Caixa', 'route' => 'caixas.index']]" />

<div style="max-width: 400px; margin-top: 20px;">

    @if(session('success'))
        <div style="color: green; margin-bottom: 10px;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="color: red; margin-bottom: 10px;">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('caixas.abrir') }}" method="POST" style="margin-bottom: 15px;">
        @csrf

        <div style="margin-bottom: 10px;">
            <label>Valor de Abertura</label>
            <input type="number" name="valor_abertura" class="form-control" placeholder="Ex: 200" required>
        </div>

        <button type="submit" class="btn btn-primary">
            Abrir Caixa
        </button>
    </form>


    <form action="{{ route('caixas.fechar') }}" method="POST">
        @csrf

        <button type="submit" class="btn btn-secondary">
            Fechar Caixa
        </button>
    </form>

</div>
@endsection